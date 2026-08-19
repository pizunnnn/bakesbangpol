<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\VehicleRepair;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class VehicleRepairController extends Controller
{
    public function store(Request $request, Asset $asset): RedirectResponse
    {
        $validated = $request->validate([
            'license_plate' => ['nullable', 'string', 'max:50'],
            'repair_date' => ['required', 'date'],
            'damage_type' => ['nullable', 'string', 'max:255'],
            'repair_description' => ['required', 'string'],
            'workshop_name' => ['nullable', 'string', 'max:255'],
            'cost' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'string', 'max:255'],
            'receipt_number' => ['nullable', 'string', 'max:255'],
            'receipt_file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'notes' => ['nullable', 'string'],
        ]);

        if ($request->hasFile('receipt_file')) {
            $validated['receipt_file'] = $request->file('receipt_file')->store('nota_perbaikan_kendaraan', 'public');
        }

        $repair = $asset->vehicleRepairs()->create($validated);

        // Record History
        $asset->logHistory(
            'Perbaikan Kendaraan',
            "Perbaikan kendaraan (Nopol: " . ($validated['license_plate'] ?: '-') . ") dicatat. Bengkel: " . ($validated['workshop_name'] ?: '-') . ", Biaya: Rp " . number_format((float)$validated['cost'], 0, ',', '.'),
            [
                'repair_id' => $repair->id,
                'license_plate' => $validated['license_plate'],
                'cost' => $validated['cost'],
                'has_receipt' => !empty($validated['receipt_file']),
            ]
        );

        return redirect()->route('assets.show', $asset)->with('success', 'Data perbaikan kendaraan berhasil ditambahkan.');
    }

    public function destroy(VehicleRepair $repair): RedirectResponse
    {
        $asset = $repair->asset;

        if ($repair->receipt_file && Storage::disk('public')->exists($repair->receipt_file)) {
            Storage::disk('public')->delete($repair->receipt_file);
        }

        $repair->delete();

        $asset->logHistory('Penghapusan Perbaikan Kendaraan', "Catatan perbaikan kendaraan tanggal {$repair->repair_date?->format('d/m/Y')} dihapus.");

        return redirect()->route('assets.show', $asset)->with('success', 'Data perbaikan kendaraan berhasil dihapus.');
    }

    public function downloadReceipt(VehicleRepair $repair): StreamedResponse|RedirectResponse
    {
        if (!$repair->receipt_file || !Storage::disk('public')->exists($repair->receipt_file)) {
            return redirect()->back()->with('error', 'File nota perbaikan tidak ditemukan.');
        }

        return Storage::disk('public')->download($repair->receipt_file);
    }
}
