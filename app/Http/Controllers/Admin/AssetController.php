<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAssetRequest;
use App\Http\Requests\UpdateAssetRequest;
use App\Models\Asset;
use App\Models\AssetCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class AssetController extends Controller
{
  public function index(): View
  {
    $assets = Asset::with('categoryRelation')->latest()->paginate(10);

    return view('assets.index', compact('assets'));
  }

  public function create(): View
  {
    return view('assets.create', [
      'categories' => AssetCategory::orderBy('name')->get(),
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
    $data['status'] = 'Menunggu Approval';

    // Default jumlah unit minimal 1
    $data['jumlah_unit'] = $request->input('jumlah_unit', 1);

    // Fallback asset_code dari kode_barang jika tidak diisi
    if (empty($data['asset_code'])) {
      $data['asset_code'] = $data['kode_barang'] ?? ('AST-' . Str::random(8));
    }

    Asset::create($data);

    return redirect()->route('assets.index')->with('success', 'Pengajuan pengadaan barang berhasil ditambahkan.');
  }

  public function edit(Asset $asset): View
  {
    return view('assets.edit', [
      'asset' => $asset,
      'categories' => AssetCategory::orderBy('name')->get(),
    ]);
  }

  public function update(UpdateAssetRequest $request, Asset $asset): RedirectResponse
  {
    $asset->update($request->validated());

    return redirect()->route('assets.index')->with('success', 'Data aset berhasil diperbarui.');
  }

  public function destroy(Asset $asset): RedirectResponse
  {
    $asset->delete();

    return redirect()->route('assets.index')->with('success', 'Data aset berhasil dihapus.');
  }
}
