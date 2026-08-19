<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\AssetMaintenance;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AssetMaintenanceController extends Controller
{
    public function store(Request $request, Asset $asset): RedirectResponse
    {
        $validated = $request->validate([
            'maintenance_date' => ['required', 'date'],
            'maintenance_type' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'condition_before' => ['nullable', 'string', 'max:255'],
            'condition_after' => ['nullable', 'string', 'max:255'],
            'vendor_name' => ['nullable', 'string', 'max:255'],
            'cost' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'string', 'max:255'],
            'receipt_number' => ['nullable', 'string', 'max:255'],
            'receipt_file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'notes' => ['nullable', 'string'],
        ]);

        if ($request->hasFile('receipt_file')) {
            $validated['receipt_file'] = $request->file('receipt_file')->store('nota_pemeliharaan', 'public');
        }

        $maintenance = $asset->maintenances()->create($validated);

        // Record History
        $asset->logHistory(
            'Pemeliharaan Aset',
            "Pemeliharaan ({$validated['maintenance_type']}) dicatat. Vendor: " . ($validated['vendor_name'] ?: '-') . ", Biaya: Rp " . number_format((float)$validated['cost'], 0, ',', '.'),
            [
                'maintenance_id' => $maintenance->id,
                'type' => $validated['maintenance_type'],
                'cost' => $validated['cost'],
                'has_receipt' => !empty($validated['receipt_file']),
            ]
        );

        return redirect()->route('assets.show', $asset)->with('success', 'Data pemeliharaan aset berhasil ditambahkan.');
    }

    public function destroy(AssetMaintenance $maintenance): RedirectResponse
    {
        $asset = $maintenance->asset;

        if ($maintenance->receipt_file && Storage::disk('public')->exists($maintenance->receipt_file)) {
            Storage::disk('public')->delete($maintenance->receipt_file);
        }

        $maintenance->delete();

        $asset->logHistory('Penghapusan Pemeliharaan', "Catatan pemeliharaan aset tanggal {$maintenance->maintenance_date?->format('d/m/Y')} dihapus.");

        return redirect()->route('assets.show', $asset)->with('success', 'Data pemeliharaan berhasil dihapus.');
    }

    public function downloadReceipt(AssetMaintenance $maintenance): StreamedResponse|RedirectResponse
    {
        if (!$maintenance->receipt_file || !Storage::disk('public')->exists($maintenance->receipt_file)) {
            return redirect()->back()->with('error', 'File nota tidak ditemukan.');
        }

        return Storage::disk('public')->download($maintenance->receipt_file);
    }
}
