<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\EmployeeTraining;
use App\Models\EmployeeSalaryHistory;
use App\Models\EmployeeRankHistory;
use App\Models\EmployeePositionHistory;
use App\Models\EmployeeRetirement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmployeeHistoryController extends Controller
{
    // ==================== RIWAYAT PELATIHAN ====================
    public function storeTraining(Request $request, Employee $employee): RedirectResponse
    {
        $validated = $request->validate([
            'nama_pelatihan' => ['required', 'string', 'max:255'],
            'jenis_pelatihan' => ['nullable', 'string', 'max:255'],
            'penyelenggara' => ['nullable', 'string', 'max:255'],
            'tanggal_mulai' => ['nullable', 'date'],
            'tanggal_selesai' => ['nullable', 'date'],
            'lokasi' => ['nullable', 'string', 'max:255'],
            'nomor_sertifikat' => ['nullable', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string'],
            'file_sertifikat' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ]);

        if ($request->hasFile('file_sertifikat')) {
            $validated['file_sertifikat'] = $request->file('file_sertifikat')->store('sertifikat_pelatihan', 'public');
        }

        $employee->trainings()->create($validated);

        return redirect()->route('employees.show', $employee)->with('success', 'Riwayat pelatihan berhasil ditambahkan.');
    }

    public function destroyTraining(EmployeeTraining $training): RedirectResponse
    {
        $employeeId = $training->employee_id;
        if ($training->file_sertifikat) {
            Storage::disk('public')->delete($training->file_sertifikat);
        }
        $training->delete();

        return redirect()->route('employees.show', $employeeId)->with('success', 'Riwayat pelatihan berhasil dihapus.');
    }

    // ==================== GAJI BERKALA ====================
    public function storeSalary(Request $request, Employee $employee): RedirectResponse
    {
        $validated = $request->validate([
            'tanggal_mulai_berlaku' => ['required', 'date'],
            'gaji_pokok' => ['nullable', 'numeric'],
            'pangkat_golongan' => ['nullable', 'string', 'max:255'],
            'nomor_sk' => ['nullable', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string'],
            'dokumen_sk' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ]);

        if (!isset($validated['gaji_pokok']) || $validated['gaji_pokok'] === null) {
            $validated['gaji_pokok'] = 0;
        }

        if ($request->hasFile('dokumen_sk')) {
            $validated['dokumen_sk'] = $request->file('dokumen_sk')->store('sk_gaji_berkala', 'public');
        }

        $employee->salaryHistories()->create($validated);

        return redirect()->route('employees.show', $employee)->with('success', 'Riwayat kenaikan gaji berkala berhasil ditambahkan.');
    }

    /**
     * Proses otomatis Kenaikan Gaji Berkala (KGB 2 Tahun) ke tabel history tanpa mengubah data utama pegawai
     */
    public function autoKgb(Request $request, Employee $employee): RedirectResponse
    {
        $nextKgbDate = $employee->tanggal_kgb_berikutnya ?? now();

        $employee->salaryHistories()->create([
            'tanggal_mulai_berlaku' => $nextKgbDate,
            'pangkat_golongan' => $employee->pangkat_golongan,
            'keterangan' => 'Kenaikan Gaji Berkala (KGB) otomatis 2 tahunan.',
        ]);

        return redirect()->route('employees.show', $employee)->with('success', 'Kenaikan Gaji Berkala (KGB) otomatis berhasil ditambahkan ke riwayat.');
    }

    public function destroySalary(EmployeeSalaryHistory $salary): RedirectResponse
    {
        $employeeId = $salary->employee_id;
        if ($salary->dokumen_sk) {
            Storage::disk('public')->delete($salary->dokumen_sk);
        }
        $salary->delete();

        return redirect()->route('employees.show', $employeeId)->with('success', 'Riwayat gaji berkala berhasil dihapus.');
    }

    // ==================== KENAIKAN PANGKAT ====================
    public function storeRank(Request $request, Employee $employee): RedirectResponse
    {
        $validated = $request->validate([
            'pangkat_lama' => ['nullable', 'string', 'max:255'],
            'pangkat_baru' => ['required', 'string', 'max:255'],
            'tanggal_kenaikan' => ['required', 'date'],
            'nomor_sk' => ['nullable', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string'],
            'dokumen_sk' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ]);

        if ($request->hasFile('dokumen_sk')) {
            $validated['dokumen_sk'] = $request->file('dokumen_sk')->store('sk_kenaikan_pangkat', 'public');
        }

        $employee->rankHistories()->create($validated);

        return redirect()->route('employees.show', $employee)->with('success', 'Riwayat kenaikan pangkat berhasil ditambahkan.');
    }

    /**
     * Proses otomatis Kenaikan Pangkat (4 Tahun) ke tabel history
     */
    public function autoRank(Request $request, Employee $employee): RedirectResponse
    {
        $validated = $request->validate([
            'pangkat_baru' => ['required', 'string', 'max:255'],
        ]);

        $nextRankDate = $employee->tanggal_kenaikan_pangkat_berikutnya ?? now();

        $employee->rankHistories()->create([
            'pangkat_lama' => $employee->pangkat_golongan,
            'pangkat_baru' => $validated['pangkat_baru'],
            'tanggal_kenaikan' => $nextRankDate,
            'keterangan' => 'Kenaikan Pangkat otomatis 4 tahunan.',
        ]);

        return redirect()->route('employees.show', $employee)->with('success', 'Kenaikan Pangkat berhasil ditambahkan ke riwayat.');
    }

    public function destroyRank(EmployeeRankHistory $rank): RedirectResponse
    {
        $employeeId = $rank->employee_id;
        if ($rank->dokumen_sk) {
            Storage::disk('public')->delete($rank->dokumen_sk);
        }
        $rank->delete();

        return redirect()->route('employees.show', $employeeId)->with('success', 'Riwayat kenaikan pangkat berhasil dihapus.');
    }

    // ==================== RIWAYAT JABATAN & UNIT KERJA ====================
    public function storePosition(Request $request, Employee $employee): RedirectResponse
    {
        $validated = $request->validate([
            'nama_jabatan' => ['required', 'string', 'max:255'],
            'unit_kerja' => ['nullable', 'string', 'max:255'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['nullable', 'date'],
            'nomor_sk' => ['nullable', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string'],
        ]);

        $employee->positionHistories()->create($validated);

        return redirect()->route('employees.show', $employee)->with('success', 'Riwayat jabatan & unit kerja berhasil ditambahkan.');
    }

    public function destroyPosition(EmployeePositionHistory $position): RedirectResponse
    {
        $employeeId = $position->employee_id;
        $position->delete();

        return redirect()->route('employees.show', $employeeId)->with('success', 'Riwayat jabatan berhasil dihapus.');
    }

    // ==================== PENSIUN ====================
    public function storeRetirement(Request $request, Employee $employee): RedirectResponse
    {
        $validated = $request->validate([
            'tanggal_pensiun' => ['required', 'date'],
            'status_pensiun' => ['required', 'string', 'max:255'],
            'nomor_sk' => ['nullable', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string'],
            'dokumen_sk' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ]);

        if ($request->hasFile('dokumen_sk')) {
            $validated['dokumen_sk'] = $request->file('dokumen_sk')->store('sk_pensiun', 'public');
        }

        $employee->retirements()->create($validated);

        return redirect()->route('employees.show', $employee)->with('success', 'Data/proses pensiun berhasil ditambahkan.');
    }

    public function destroyRetirement(EmployeeRetirement $retirement): RedirectResponse
    {
        $employeeId = $retirement->employee_id;
        if ($retirement->dokumen_sk) {
            Storage::disk('public')->delete($retirement->dokumen_sk);
        }
        $retirement->delete();

        return redirect()->route('employees.show', $employeeId)->with('success', 'Data pensiun berhasil dihapus.');
    }
}
