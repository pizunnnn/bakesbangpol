<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\EmployeeAllowance;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmployeeAllowanceController extends Controller
{
    /**
     * Simpan atau Perbarui Data Pribadi Pemilik Tunjangan & Komponen Gaji
     */
    public function storeOrUpdate(Request $request, Employee $employee): RedirectResponse
    {
        $validated = $request->validate([
            'periode_bulan' => ['nullable', 'string', 'max:255'],
            'status_kawin' => ['required', 'string', 'max:10'],
            'kd_jiwa' => ['nullable', 'string', 'max:20'],
            'jml_jiwa' => ['required', 'integer', 'min:1'],
            'npwp' => ['nullable', 'string', 'max:50'],
            'nomor_rekening' => ['nullable', 'string', 'max:50'],
            'nama_bank' => ['nullable', 'string', 'max:50'],
            'masker' => ['nullable', 'string', 'max:50'],
            'tmt_sk' => ['nullable', 'date'],
            'gaji_pokok' => ['nullable', 'numeric', 'min:0'],
            'tunjangan_suami_istri' => ['nullable', 'numeric', 'min:0'],
            'tunjangan_anak' => ['nullable', 'numeric', 'min:0'],
            'tunjangan_umum' => ['nullable', 'numeric', 'min:0'],
            'tambahan_tunjangan_umum' => ['nullable', 'numeric', 'min:0'],
            'tunjangan_struktural' => ['nullable', 'numeric', 'min:0'],
            'tunjangan_fungsional' => ['nullable', 'numeric', 'min:0'],
            'tunjangan_beras' => ['nullable', 'numeric', 'min:0'],
            'tunjangan_pph' => ['nullable', 'numeric', 'min:0'],
            'pembulatan' => ['nullable', 'numeric', 'min:0'],
            'potongan_beras' => ['nullable', 'numeric', 'min:0'],
            'potongan_iwp_8' => ['nullable', 'numeric', 'min:0'],
            'potongan_iwp_1' => ['nullable', 'numeric', 'min:0'],
            'potongan_pph' => ['nullable', 'numeric', 'min:0'],
            'potongan_sewa_rumah' => ['nullable', 'numeric', 'min:0'],
            'potongan_hutang' => ['nullable', 'numeric', 'min:0'],
            'potongan_tabungan_rumah' => ['nullable', 'numeric', 'min:0'],
            'potongan_lain' => ['nullable', 'numeric', 'min:0'],
            'catatan' => ['nullable', 'string'],
        ]);

        $allowance = $employee->allowance ?? new EmployeeAllowance(['employee_id' => $employee->id]);
        $allowance->fill($validated);
        $allowance->recalculate();
        $allowance->save();

        return redirect()->route('employees.show', ['employee' => $employee, 'tab' => 'allowance'])
            ->with('success', 'Data tunjangan dan gaji pegawai berhasil disimpan.');
    }

    /**
     * Preview Cetak Slip Gaji & Tunjangan Perorangan
     */
    public function printSlip(Employee $employee): View
    {
        $employee->load(['department', 'position', 'allowance']);
        $allowance = $employee->allowance;

        return view('employees.allowances.slip_print', compact('employee', 'allowance'));
    }

    /**
     * Preview / Cetak Daftar Pegawai Penerima Tunjangan Bakesbangpol
     */
    public function printPayrollReport(Request $request): View
    {
        $periode = $request->query('periode', 'Desember 2024');
        $filterType = $request->query('filter', '');
        $search = $request->query('search', '');

        $allEmployees = Employee::with(['department', 'position', 'allowance'])
            ->orderBy('id', 'asc')
            ->get();

        // Rekapitulasi Jumlah Penerima per Jenis Tunjangan
        $countSuamiIstri = $allEmployees->filter(fn($e) => $e->allowance?->has_tj_suami_istri)->count();
        $countAnak = $allEmployees->filter(fn($e) => $e->allowance?->has_tj_anak)->count();
        $countStruktural = $allEmployees->filter(fn($e) => $e->allowance?->has_tj_struktural)->count();
        $countFungsional = $allEmployees->filter(fn($e) => $e->allowance?->has_tj_fungsional)->count();
        $countBeras = $allEmployees->filter(fn($e) => $e->allowance?->has_tj_beras)->count();

        // Filter daftar pegawai jika ada filter tipe tunjangan aktif
        $employees = $allEmployees->filter(function ($emp) use ($filterType, $search) {
            $al = $emp->allowance;

            if ($search) {
                $matchSearch = str_contains(strtolower($emp->full_name), strtolower($search))
                    || str_contains(strtolower((string)$emp->employee_number), strtolower($search))
                    || str_contains(strtolower((string)($emp->position?->name ?? '')), strtolower($search));
                if (!$matchSearch) {
                    return false;
                }
            }

            if ($filterType === 'suami_istri') {
                return (bool) $al?->has_tj_suami_istri;
            } elseif ($filterType === 'anak') {
                return (bool) $al?->has_tj_anak;
            } elseif ($filterType === 'struktural') {
                return (bool) $al?->has_tj_struktural;
            } elseif ($filterType === 'fungsional') {
                return (bool) $al?->has_tj_fungsional;
            } elseif ($filterType === 'beras') {
                return (bool) $al?->has_tj_beras;
            }

            return true;
        });

        return view('employees.allowances.payroll_report', compact(
            'employees',
            'periode',
            'filterType',
            'search',
            'countSuamiIstri',
            'countAnak',
            'countStruktural',
            'countFungsional',
            'countBeras'
        ));
    }
}
