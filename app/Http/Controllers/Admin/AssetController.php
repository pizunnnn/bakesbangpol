<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAssetRequest;
use App\Http\Requests\UpdateAssetRequest;
use App\Models\Asset;
use App\Models\AssetCatalog;
use App\Models\AssetCategory;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssetController extends Controller
{
    protected const BIDANG_LIST = [
        'Ideologi dan Wawasan Bangsa (IDWASBANG)',
        'Ketahanan Ekonomi, Seni Budaya, Agama, dan Kemasyarakatan (KESBAK)',
        'Kewaspadaan Daerah (WASDA)',
        'Politik Dalam Negeri (POLDAGRI)',
        'Sekretariat',
    ];

    public function index(): View
    {
        $search = request('search');
        $status = request('status');
        $bidang = request('bidang');
        $categoryId = request('category_id');
        $bidangList = self::BIDANG_LIST;

        $assets = Asset::with(['categoryRelation', 'currentEmployee'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_barang', 'like', "%{$search}%")
                        ->orWhere('kode_barang', 'like', "%{$search}%")
                        ->orWhere('asset_code', 'like', "%{$search}%")
                        ->orWhere('merk_tipe', 'like', "%{$search}%")
                        ->orWhere('category', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%")
                        ->orWhere('bidang', 'like', "%{$search}%");
                });
            })
            ->when($status, function ($query) use ($status) {
                if ($status === 'eligible_10_years') {
                    $cutoffDate = Carbon::now()->subYears(10)->format('Y-m-d');
                    $cutoffYear = (int) Carbon::now()->subYears(10)->year;
                    $query->where(function ($q) use ($cutoffDate, $cutoffYear) {
                        $q->where('purchase_date', '<=', $cutoffDate)
                          ->orWhere('tahun_perolehan', '<=', $cutoffYear)
                          ->orWhere('status', 'Dapat Dihapus');
                    });
                } else {
                    $query->where('status', $status);
                }
            })
            ->when($bidang, function ($query) use ($bidang) {
                $query->where('bidang', $bidang);
            })
            ->when($categoryId, function ($query) use ($categoryId) {
                $query->where('asset_category_id', $categoryId);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $categories = AssetCategory::orderBy('name')->get();

        return view('assets.index', compact('assets', 'search', 'status', 'bidang', 'categoryId', 'bidangList', 'categories'));
    }

    public function show(Asset $asset): View
    {
        $asset->load([
            'categoryRelation',
            'currentEmployee',
            'maintenances',
            'vehicleRepairs',
            'histories.user',
            'assignments.employee',
        ]);

        return view('assets.show', compact('asset'));
    }

    public function create(): View
    {
        return view('assets.create', [
            'categories' => AssetCategory::orderBy('name')->get(),
            'employees' => Employee::orderBy('full_name')->get(),
            'bidangList' => self::BIDANG_LIST,
            'catalogs' => AssetCatalog::orderBy('nama_barang')->get(),
        ]);
    }

    public function store(StoreAssetRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $triwulan = $request->input('triwulan', 'TW II');
        $tahunAnggaran = $request->input('tahun_anggaran', now()->year);
        $data['cara_perolehan'] = 'Belanja Modal '.$triwulan.' '.$tahunAnggaran;
        $data['tahun_perolehan'] = $tahunAnggaran;

        if (empty($data['purchase_date']) && !empty($tahunAnggaran)) {
            $data['purchase_date'] = Carbon::createFromDate((int)$tahunAnggaran, 1, 1)->format('Y-m-d');
        }

        $data['status'] = $request->input('status', 'Aktif');
        $data['current_employee_id'] = $request->input('current_employee_id');
        $data['jumlah_unit'] = $request->input('jumlah_unit', 1);

        if (empty($data['asset_code'])) {
            $base = $data['kode_barang'] ?? 'AST';
            $data['asset_code'] = $this->generateUniqueAssetCode($base);
        }

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('assets/photos', 'public');
        }

        $asset = Asset::create($data);

        $asset->logHistory('Aset Dibuat', "Aset baru {$asset->nama_barang} (Kode: {$asset->asset_code}) berhasil didaftarkan ke sistem.");

        return redirect()->route('assets.index')->with('success', 'Data aset berhasil ditambahkan.');
    }

    public function edit(Asset $asset): View
    {
        return view('assets.edit', [
            'asset' => $asset,
            'categories' => AssetCategory::orderBy('name')->get(),
            'employees' => Employee::orderBy('full_name')->get(),
            'bidangList' => self::BIDANG_LIST,
            'catalogs' => AssetCatalog::orderBy('nama_barang')->get(),
        ]);
    }

    public function update(UpdateAssetRequest $request, Asset $asset): RedirectResponse
    {
        $data = $request->validated();
        $oldLocation = $asset->location;
        $oldEmployee = $asset->current_employee_id;
        $oldCondition = $asset->condition ?? $asset->keadaan;

        if ($request->hasFile('photo')) {
            if ($asset->photo && Storage::disk('public')->exists($asset->photo)) {
                Storage::disk('public')->delete($asset->photo);
            }
            $data['photo'] = $request->file('photo')->store('assets/photos', 'public');
        }

        $asset->update($data);

        if ($oldLocation !== $asset->location) {
            $asset->logHistory('Perpindahan Lokasi', "Lokasi aset diubah dari '" . ($oldLocation ?: '-') . "' menjadi '" . ($asset->location ?: '-') . "'.");
        }

        if ($oldEmployee !== $asset->current_employee_id) {
            $empName = $asset->currentEmployee?->full_name ?: 'Tidak Ada';
            $asset->logHistory('Perubahan Penanggung Jawab', "Penanggung jawab aset diperbarui menjadi {$empName}.");
        }

        if ($oldCondition !== ($asset->condition ?? $asset->keadaan)) {
            $asset->logHistory('Perubahan Kondisi', "Kondisi aset diperbarui menjadi '" . ($asset->condition ?? $asset->keadaan) . "'.");
        }

        $asset->logHistory('Perubahan Data Aset', "Data spesifikasi / atribut aset {$asset->nama_barang} diperbarui.");

        return redirect()->route('assets.index')->with('success', 'Data aset berhasil diperbarui.');
    }

    public function destroy(Asset $asset): RedirectResponse
    {
        $asset->logHistory('Aset Dihapus', "Data aset {$asset->nama_barang} dihapus dari sistem.");

        if ($asset->photo && Storage::disk('public')->exists($asset->photo)) {
            Storage::disk('public')->delete($asset->photo);
        }

        $asset->delete();

        return redirect()->route('assets.index')->with('success', 'Data aset berhasil dihapus.');
    }

    public function deletable(Request $request): View
    {
        $search = $request->query('search');
        $tahunPerolehan = $request->query('tahun_perolehan');
        $categoryId = $request->query('category_id');
        $bidang = $request->query('bidang');
        $condition = $request->query('condition');
        $status = $request->query('status');

        $query = Asset::with(['categoryRelation', 'currentEmployee'])
            ->where(function ($q) {
                $cutoffDate = Carbon::now()->subYears(10)->format('Y-m-d');
                $cutoffYear = (int) Carbon::now()->subYears(10)->year;

                $q->where('purchase_date', '<=', $cutoffDate)
                  ->orWhere('tahun_perolehan', '<=', $cutoffYear)
                  ->orWhere('status', 'Dapat Dihapus');
            })
            ->when($search, function ($q) use ($search) {
                $q->where(function ($s) use ($search) {
                    $s->where('nama_barang', 'like', "%{$search}%")
                      ->orWhere('kode_barang', 'like', "%{$search}%")
                      ->orWhere('asset_code', 'like', "%{$search}%");
                });
            })
            ->when($tahunPerolehan, function ($q) use ($tahunPerolehan) {
                $q->where('tahun_perolehan', $tahunPerolehan);
            })
            ->when($categoryId, function ($q) use ($categoryId) {
                $q->where('asset_category_id', $categoryId);
            })
            ->when($bidang, function ($q) use ($bidang) {
                $q->where('bidang', $bidang);
            })
            ->when($condition, function ($q) use ($condition) {
                $q->where('condition', $condition)->orWhere('keadaan', $condition);
            })
            ->when($status, function ($q) use ($status) {
                $q->where('status', $status);
            })
            ->latest();

        $assets = $query->paginate(15)->withQueryString();
        $categories = AssetCategory::orderBy('name')->get();
        $bidangList = self::BIDANG_LIST;

        return view('assets.deletable', compact('assets', 'search', 'tahunPerolehan', 'categoryId', 'bidang', 'condition', 'status', 'categories', 'bidangList'));
    }

    public function verifyDisposal(Request $request, Asset $asset): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:Dapat Dihapus,Sudah Dihapus,Aktif'],
            'notes' => ['nullable', 'string'],
        ]);

        $newStatus = $validated['status'];
        $asset->update(['status' => $newStatus]);

        $asset->logHistory(
            'Verifikasi Penghapusan',
            "Status penghapusan aset diverifikasi menjadi '{$newStatus}'. Catatan: " . ($validated['notes'] ?: 'Tidak ada catatan tambahan.'),
            ['new_status' => $newStatus, 'notes' => $validated['notes'] ?? '']
        );

        return redirect()->back()->with('success', "Status penghapusan aset {$asset->nama_barang} berhasil diperbarui menjadi '{$newStatus}'.");
    }

    /**
     * Import Excel / CSV Data Aset
     */
    public function importExcel(Request $request): RedirectResponse
    {
        $request->validate([
            'file_excel' => ['required', 'file', 'mimes:csv,txt,xlsx,xls', 'max:10240'],
        ]);

        $file = $request->file('file_excel');
        $path = $file->getRealPath();

        $count = 0;
        if (($handle = fopen($path, 'r')) !== false) {
            // Read header row
            $header = fgetcsv($handle, 2000, ',');
            if (!$header || count($header) < 3) {
                rewind($handle);
            }

            while (($row = fgetcsv($handle, 2000, ',')) !== false) {
                if (empty($row[0]) && empty($row[1])) {
                    continue;
                }

                $kodeBarang = trim($row[0] ?? $row[1] ?? 'AST');
                $noReg = trim($row[1] ?? '1');
                $namaBarang = trim($row[2] ?? 'Aset Import');
                $merkTipe = trim($row[3] ?? '');
                $spesifikasi = trim($row[4] ?? '');
                $tahunPerolehan = (int) (trim($row[5] ?? '') ?: date('Y'));
                $nilaiPerolehan = (float) str_replace(['Rp', '.', ' '], '', trim($row[6] ?? '0'));
                $bidang = trim($row[7] ?? 'Sekretariat');

                $assetCode = $this->generateUniqueAssetCode($kodeBarang);

                $asset = Asset::create([
                    'kode_barang' => $kodeBarang,
                    'no_register' => $noReg,
                    'nama_barang' => $namaBarang,
                    'merk_tipe' => $merkTipe,
                    'spesifikasi' => $spesifikasi,
                    'tahun_perolehan' => $tahunPerolehan,
                    'purchase_date' => $tahunPerolehan . '-01-01',
                    'nilai_perolehan' => $nilaiPerolehan,
                    'purchase_price' => $nilaiPerolehan,
                    'keadaan' => 'B',
                    'condition' => 'Baik',
                    'jumlah_unit' => 1,
                    'status' => 'Aktif',
                    'bidang' => $bidang,
                    'asset_code' => $assetCode,
                ]);

                $asset->logHistory('Import Data Excel', "Aset berhasil di-import dari file Excel/CSV {$file->getClientOriginalName()}.");
                $count++;
            }
            fclose($handle);
        }

        return redirect()->route('assets.index')->with('success', "Berhasil meng-import {$count} data aset dari Excel.");
    }

    protected function generateUniqueAssetCode(string $base): string
    {
        $clean = trim($base);
        if ($clean === '') {
            $clean = 'AST';
        }

        if (!Asset::where('asset_code', $clean)->exists()) {
            return $clean;
        }

        $suffix = 1;
        do {
            $candidate = $clean.'/'.$suffix;
            if (!Asset::where('asset_code', $candidate)->exists()) {
                return $candidate;
            }
            $suffix++;
        } while (true);
    }
}
