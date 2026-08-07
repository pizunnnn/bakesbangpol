<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAssetRequest;
use App\Http\Requests\UpdateAssetRequest;
use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\Employee;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class AssetController extends Controller
{
public function index(): View
  {
    $search = request('search');
    $status = request('status');

    $assets = Asset::with(['categoryRelation', 'currentEmployee'])
      ->when($search, function ($query) use ($search) {
        $query->where(function ($q) use ($search) {
          $q->where('nama_barang', 'like', "%{$search}%")
            ->orWhere('kode_barang', 'like', "%{$search}%")
            ->orWhere('asset_code', 'like', "%{$search}%")
            ->orWhere('merk_tipe', 'like', "%{$search}%")
            ->orWhere('category', 'like', "%{$search}%")
            ->orWhere('status', 'like', "%{$search}%");
        });
      })
      ->when($status, function ($query) use ($status) {
        $query->where('status', $status);
      })
      ->latest()
      ->paginate(10)
      ->withQueryString();

    return view('assets.index', compact('assets', 'search', 'status'));
  }

public function create(): View
  {
    return view('assets.create', [
      'categories' => AssetCategory::orderBy('name')->get(),
      'employees' => Employee::orderBy('full_name')->get(),
    ]);
  }

public function store(StoreAssetRequest $request): RedirectResponse
  {
    $data = $request->validated();

    // Bangun cara perolehan dari triwulan + tahun anggaran
    $triwulan = $request->input('triwulan', 'TW II');
    $tahunAnggaran = $request->input('tahun_anggaran', now()->year);
    $data['cara_perolehan'] = 'Belanja Modal ' . $triwulan . ' ' . $tahunAnggaran;
    $data['tahun_perolehan'] = $tahunAnggaran;

// Default status untuk pengajuan pengadaan baru
    $data['status'] = 'Tersedia';
    $data['current_employee_id'] = $request->input('current_employee_id');

    // Default jumlah unit minimal 1
    $data['jumlah_unit'] = $request->input('jumlah_unit', 1);

    // Fallback asset_code dari kode_barang jika tidak diisi
    if (empty($data['asset_code'])) {
      $data['asset_code'] = $data['kode_barang'] ?? ('AST-' . Str::random(8));
    }

    Asset::create($data);

return redirect()->route('assets.index')->with('success', 'Data aset berhasil ditambahkan.');
  }

public function edit(Asset $asset): View
  {
    return view('assets.edit', [
      'asset' => $asset,
      'categories' => AssetCategory::orderBy('name')->get(),
      'employees' => Employee::orderBy('full_name')->get(),
    ]);
  }

  public function update(UpdateAssetRequest $request, Asset $asset): RedirectResponse
  {
    $data = $request->validated();

    // Jika status bukan "Dipinjam", kosongkan current_employee_id
    if (($data['status'] ?? 'Tersedia') !== 'Dipinjam') {
      $data['current_employee_id'] = null;
    }

    $asset->update($data);

    return redirect()->route('assets.index')->with('success', 'Data aset berhasil diperbarui.');
  }

  public function destroy(Asset $asset): RedirectResponse
  {
    $asset->delete();

    return redirect()->route('assets.index')->with('success', 'Data aset berhasil dihapus.');
  }
}
