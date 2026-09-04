<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmployeePromotionController extends Controller
{
    /**
     * Halaman Monitoring & Notifikasi Kenaikan Pangkat Pegawai Bulanan
     */
    public function index(Request $request): View
    {
        $currentDate = Carbon::now();
        $selectedMonth = (int) $request->query('month', (string)$currentDate->month);
        $selectedYear = (int) $request->query('year', (string)$currentDate->year);
        $search = $request->query('search', '');
        $departmentId = $request->query('department_id', '');
        $preset = $request->query('preset', '');

        $allEmployees = Employee::with(['department', 'position', 'rankHistories'])
            ->where('employment_status', '!=', 'inactive')
            ->orderBy('full_name', 'asc')
            ->get();

        // 1. Hitung Notifikasi Bulan Berjalan (Bulan Sekarang) & Yang Sudah Jatuh Tempo (Overdue)
        $currentMonthKey = $currentDate->format('Y-m');
        $currentMonthCandidates = $allEmployees->filter(function ($emp) use ($currentDate, $currentMonthKey) {
            $next = $emp->tanggal_kenaikan_pangkat_berikutnya;
            if (!$next) return false;
            // Jatuh tempo di bulan berjalan atau sudah lewat jatuh tempo (overdue) tapi belum diproses
            return $next->format('Y-m') === $currentMonthKey || $emp->is_eligible_kenaikan_pangkat;
        });
        $currentMonthCount = $currentMonthCandidates->count();

        // 2. Filter Karyawan Berdasarkan Bulan & Tahun yang Dipilih
        $selectedPeriodEmployees = $allEmployees->filter(function ($emp) use ($selectedMonth, $selectedYear, $preset, $currentDate, $search, $departmentId) {
            $next = $emp->tanggal_kenaikan_pangkat_berikutnya;
            if (!$next) return false;

            // Search filter
            if ($search) {
                $matched = str_contains(strtolower($emp->full_name), strtolower($search))
                    || str_contains(strtolower((string)$emp->employee_number), strtolower($search))
                    || str_contains(strtolower((string)($emp->position?->name ?? '')), strtolower($search));
                if (!$matched) return false;
            }

            // Department filter
            if ($departmentId && $emp->department_id != $departmentId) {
                return false;
            }

            // Preset filter logic
            if ($preset === 'current_month') {
                return $next->format('Y-m') === $currentDate->format('Y-m') || $emp->is_eligible_kenaikan_pangkat;
            } elseif ($preset === 'next_month') {
                $nextMonth = $currentDate->copy()->addMonth();
                return $next->format('Y-m') === $nextMonth->format('Y-m');
            } elseif ($preset === 'next_3_months') {
                $start = $currentDate->copy()->startOfMonth();
                $end = $currentDate->copy()->addMonths(3)->endOfMonth();
                return $next->between($start, $end);
            } elseif ($preset === 'this_year') {
                return (int)$next->year === (int)$currentDate->year;
            }

            // Default: Match exact selected month and year
            return (int)$next->month === $selectedMonth && (int)$next->year === $selectedYear;
        });

        // 3. Statistik Distribusi Berdasarkan Golongan untuk Bulan Terpilih
        $countGolIV = $selectedPeriodEmployees->filter(fn($e) => str_contains((string)$e->pangkat_golongan, 'IV'))->count();
        $countGolIII = $selectedPeriodEmployees->filter(fn($e) => str_contains((string)$e->pangkat_golongan, 'III'))->count();
        $countGolII = $selectedPeriodEmployees->filter(fn($e) => str_contains((string)$e->pangkat_golongan, 'II'))->count();
        $countPppk = $selectedPeriodEmployees->filter(fn($e) => str_contains((string)$e->status_pegawai, 'PPPK') || str_contains((string)$e->pangkat_golongan, 'IX') || str_contains((string)$e->pangkat_golongan, 'X'))->count();

        // 4. Kalender Proyeksi 12 Bulan Kedepan untuk Chart / Bar Timeline
        $timelineProjection = [];
        for ($i = 0; $i < 12; $i++) {
            $monthObj = $currentDate->copy()->addMonths($i);
            $key = $monthObj->format('Y-m');
            $label = $monthObj->translatedFormat('M Y');
            $count = $allEmployees->filter(fn($e) => $e->tanggal_kenaikan_pangkat_berikutnya && $e->tanggal_kenaikan_pangkat_berikutnya->format('Y-m') === $key)->count();
            $timelineProjection[] = [
                'month' => $monthObj->month,
                'year' => $monthObj->year,
                'key' => $key,
                'label' => $label,
                'count' => $count,
            ];
        }

        $departments = Department::orderBy('name')->get();

        $monthNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        return view('employees.promotions.index', compact(
            'selectedPeriodEmployees',
            'currentMonthCandidates',
            'currentMonthCount',
            'selectedMonth',
            'selectedYear',
            'search',
            'departmentId',
            'preset',
            'countGolIV',
            'countGolIII',
            'countGolII',
            'countPppk',
            'timelineProjection',
            'departments',
            'monthNames'
        ));
    }

    /**
     * Cetak Daftar Nominatif Pegawai yang Akan Naik Pangkat Bulanan
     */
    public function print(Request $request): View
    {
        $currentDate = Carbon::now();
        $selectedMonth = (int) $request->query('month', (string)$currentDate->month);
        $selectedYear = (int) $request->query('year', (string)$currentDate->year);
        $preset = $request->query('preset', '');

        $allEmployees = Employee::with(['department', 'position', 'rankHistories'])
            ->where('employment_status', '!=', 'inactive')
            ->orderBy('pangkat_golongan', 'desc')
            ->orderBy('full_name', 'asc')
            ->get();

        $candidates = $allEmployees->filter(function ($emp) use ($selectedMonth, $selectedYear, $preset, $currentDate) {
            $next = $emp->tanggal_kenaikan_pangkat_berikutnya;
            if (!$next) return false;

            if ($preset === 'current_month') {
                return $next->format('Y-m') === $currentDate->format('Y-m') || $emp->is_eligible_kenaikan_pangkat;
            } elseif ($preset === 'this_year') {
                return (int)$next->year === (int)$currentDate->year;
            }

            return (int)$next->month === $selectedMonth && (int)$next->year === $selectedYear;
        });

        $monthNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        $periodeText = $preset === 'this_year' 
            ? "TAHUN {$selectedYear}" 
            : strtoupper($monthNames[$selectedMonth] ?? 'BULAN') . " {$selectedYear}";

        return view('employees.promotions.print', compact('candidates', 'selectedMonth', 'selectedYear', 'periodeText'));
    }
}
