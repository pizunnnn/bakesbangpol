<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCatalogRequest;
use App\Http\Requests\UpdateCatalogRequest;
use App\Models\AssetCatalog;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class CatalogController extends Controller
{
  public function index(): View
  {
    $search = request('search');
    $catalogs = AssetCatalog::query()
      ->when($search, function ($query) use ($search) {
        $query->where(function ($q) use ($search) {
          $q->where('kode_barang', 'like', "%{$search}%")
            ->orWhere('nama_barang', 'like', "%{$search}%");
        });
      })
      ->orderBy('kode_barang')
      ->paginate(15)
      ->withQueryString();

    return view('catalog.index', compact('catalogs', 'search'));
  }

  public function create(): View
  {
    return view('catalog.create');
  }

  public function store(StoreCatalogRequest $request): RedirectResponse
  {
    AssetCatalog::create($request->validated());

    return redirect()->route('catalog.index')->with('success', 'Katalog barang berhasil ditambahkan.');
  }

  public function edit(AssetCatalog $catalog): View
  {
    return view('catalog.edit', compact('catalog'));
  }

  public function update(UpdateCatalogRequest $request, AssetCatalog $catalog): RedirectResponse
  {
    $catalog->update($request->validated());

    return redirect()->route('catalog.index')->with('success', 'Katalog barang berhasil diperbarui.');
  }

  public function destroy(AssetCatalog $catalog): RedirectResponse
  {
    $catalog->delete();

    return redirect()->route('catalog.index')->with('success', 'Katalog barang berhasil dihapus.');
  }
}
