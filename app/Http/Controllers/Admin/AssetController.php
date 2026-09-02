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
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
            'status' => ['required', 'string', 'in:Dapat Dihapus,Sudah Dihapus,Tersedia,Aktif'],
            'notes' => ['nullable', 'string'],
        ]);

        $newStatus = $validated['status'];
        if ($newStatus === 'Aktif') {
            $newStatus = 'Tersedia';
        }
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
                    'status' => 'Tersedia',
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

    /**
     * Export Excel (.csv / .xlsx) Data Aset
     */
    public function exportExcel(Request $request): StreamedResponse
    {
        @ini_set('memory_limit', '512M');
        @set_time_limit(300);

        $search = $request->query('search');
        $status = $request->query('status');
        $bidang = $request->query('bidang');
        $categoryId = $request->query('category_id');

        $query = Asset::with(['categoryRelation', 'currentEmployee'])
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
            ->orderBy('id', 'asc');

        $fileName = 'Data_Aset_Bakesbangpol_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($query) {
            $handle = fopen('php://output', 'w');
            // Write UTF-8 BOM for Excel auto-formatting
            fwrite($handle, "\xEF\xBB\xBF");

            // CSV Headers
            fputcsv($handle, [
                'No',
                'Kode Aset (SIMPEG)',
                'Kode Barang (BMD)',
                'No. Register',
                'Nama Barang / Aset',
                'Kategori',
                'Merk / Tipe',
                'Spesifikasi',
                'Unit Kerja / Bidang',
                'Lokasi',
                'Tahun Perolehan',
                'Nilai Perolehan (Rp)',
                'Jumlah Unit',
                'Kondisi',
                'Status',
            ]);

            $no = 1;
            $query->chunk(200, function ($assets) use ($handle, &$no) {
                foreach ($assets as $asset) {
                    fputcsv($handle, [
                        $no++,
                        $asset->asset_code,
                        $asset->kode_barang ?: '-',
                        $asset->no_register ?: '1',
                        $asset->nama_barang,
                        $asset->categoryRelation->name ?? $asset->category ?? '-',
                        $asset->merk_tipe ?: ($asset->brand . ' ' . $asset->model) ?: '-',
                        $asset->spesifikasi ?: '-',
                        $asset->bidang ?: '-',
                        $asset->location ?: '-',
                        $asset->tahun_perolehan ?: ($asset->purchase_date ? $asset->purchase_date->format('Y') : '-'),
                        (float)($asset->nilai_perolehan ?: $asset->purchase_price),
                        $asset->jumlah_unit ?: 1,
                        $asset->condition ?: ($asset->keadaan ?: 'Baik'),
                        $asset->status,
                    ]);
                }
            });

            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Preview Cetak Tampilan HTML (seperti Form PPPK)
     */
    public function printPreview(Request $request): View
    {
        $mode = $request->query('mode', 'rekap');
        $search = $request->query('search');
        $status = $request->query('status');
        $bidang = $request->query('bidang');
        $categoryId = $request->query('category_id');

        if ($mode === 'rekap' || $mode === 'summary') {
            $totalCount = Asset::count();
            $totalUnits = (int) Asset::sum('jumlah_unit');
            $totalValue = (float) Asset::sum('nilai_perolehan');

            $cutoffDate = Carbon::now()->subYears(10)->format('Y-m-d');
            $cutoffYear = (int) Carbon::now()->subYears(10)->year;
            $agedAssetsCount = Asset::where(function ($q) use ($cutoffDate, $cutoffYear) {
                $q->where('purchase_date', '<=', $cutoffDate)
                  ->orWhere('tahun_perolehan', '<=', $cutoffYear)
                  ->orWhere('status', 'Dapat Dihapus');
            })->count();

            $categories = AssetCategory::orderBy('name')->get();
            $byCategory = [];
            foreach ($categories as $cat) {
                $cQuery = Asset::where('asset_category_id', $cat->id);
                $byCategory[] = [
                    'name' => $cat->name,
                    'count' => $cQuery->count(),
                    'units' => (int) $cQuery->sum('jumlah_unit'),
                    'value' => (float) $cQuery->sum('nilai_perolehan'),
                ];
            }

            $byBidang = [];
            foreach (self::BIDANG_LIST as $b) {
                $bQuery = Asset::where('bidang', $b);
                $byBidang[] = [
                    'name' => $b,
                    'count' => $bQuery->count(),
                    'value' => (float) $bQuery->sum('nilai_perolehan'),
                ];
            }

            $statuses = ['Tersedia', 'Dipinjam', 'Dalam Perbaikan', 'Rusak Ringan', 'Rusak Berat', 'Dapat Dihapus'];
            $byStatus = [];
            foreach ($statuses as $st) {
                $sQuery = Asset::where('status', $st);
                $byStatus[] = [
                    'name' => $st,
                    'count' => $sQuery->count(),
                    'value' => (float) $sQuery->sum('nilai_perolehan'),
                ];
            }

            return view('assets.print_preview_summary', compact(
                'totalCount',
                'totalUnits',
                'totalValue',
                'agedAssetsCount',
                'byCategory',
                'byBidang',
                'byStatus'
            ));
        }

        $query = Asset::with(['categoryRelation', 'currentEmployee'])
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
            ->take(500);

        $assets = $query->get();
        $totalValue = (float) $assets->sum('nilai_perolehan');

        $filterCategory = $categoryId ? AssetCategory::find($categoryId)?->name : null;
        $filterBidang = $bidang;
        $filterStatus = $status;

        return view('assets.print_preview_detail', compact(
            'assets',
            'totalValue',
            'filterCategory',
            'filterBidang',
            'filterStatus',
            'search'
        ));
    }

    /**
     * Cetak PDF Data Aset (Mode Rekapitulasi Eksekutif vs Mode Detail Terfilter)
     */
    public function exportPdf(Request $request)
    {
        @ini_set('memory_limit', '512M');
        @set_time_limit(300);

        $mode = $request->query('mode', 'rekap');
        $isDownload = $request->query('download') === '1';
        $search = $request->query('search');
        $status = $request->query('status');
        $bidang = $request->query('bidang');
        $categoryId = $request->query('category_id');

        if ($mode === 'rekap' || $mode === 'summary') {
            // Mode 1: Laporan Rekapitulasi Eksekutif untuk Pimpinan
            $totalCount = Asset::count();
            $totalUnits = (int) Asset::sum('jumlah_unit');
            $totalValue = (float) Asset::sum('nilai_perolehan');

            $cutoffDate = Carbon::now()->subYears(10)->format('Y-m-d');
            $cutoffYear = (int) Carbon::now()->subYears(10)->year;
            $agedAssetsCount = Asset::where(function ($q) use ($cutoffDate, $cutoffYear) {
                $q->where('purchase_date', '<=', $cutoffDate)
                  ->orWhere('tahun_perolehan', '<=', $cutoffYear)
                  ->orWhere('status', 'Dapat Dihapus');
            })->count();

            // Rekap per Kategori
            $categories = AssetCategory::orderBy('name')->get();
            $byCategory = [];
            foreach ($categories as $cat) {
                $cQuery = Asset::where('asset_category_id', $cat->id);
                $byCategory[] = [
                    'name' => $cat->name,
                    'count' => $cQuery->count(),
                    'units' => (int) $cQuery->sum('jumlah_unit'),
                    'value' => (float) $cQuery->sum('nilai_perolehan'),
                ];
            }

            // Rekap per Bidang
            $byBidang = [];
            foreach (self::BIDANG_LIST as $b) {
                $bQuery = Asset::where('bidang', $b);
                $byBidang[] = [
                    'name' => $b,
                    'count' => $bQuery->count(),
                    'value' => (float) $bQuery->sum('nilai_perolehan'),
                ];
            }

            // Rekap per Status
            $statuses = ['Tersedia', 'Dipinjam', 'Dalam Perbaikan', 'Rusak Ringan', 'Rusak Berat', 'Dapat Dihapus'];
            $byStatus = [];
            foreach ($statuses as $st) {
                $sQuery = Asset::where('status', $st);
                $byStatus[] = [
                    'name' => $st,
                    'count' => $sQuery->count(),
                    'value' => (float) $sQuery->sum('nilai_perolehan'),
                ];
            }

            $pdf = Pdf::loadView('assets.pdf_summary', compact(
                'totalCount',
                'totalUnits',
                'totalValue',
                'agedAssetsCount',
                'byCategory',
                'byBidang',
                'byStatus'
            ))->setPaper('a4', 'portrait');

            $filename = 'Laporan_Rekapitulasi_Aset_Bakesbangpol_' . date('Ymd_His') . '.pdf';
            return $isDownload ? $pdf->download($filename) : $pdf->stream($filename);
        }

        // Mode 2: Laporan Rincian Terfilter (max limit 500)
        $query = Asset::with(['categoryRelation', 'currentEmployee'])
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
            ->take(500);

        $assets = $query->get();
        $totalValue = (float) $assets->sum('nilai_perolehan');

        $filterCategory = $categoryId ? AssetCategory::find($categoryId)?->name : null;
        $filterBidang = $bidang;
        $filterStatus = $status;

        $pdf = Pdf::loadView('assets.pdf_detail', compact(
            'assets',
            'totalValue',
            'filterCategory',
            'filterBidang',
            'filterStatus',
            'search'
        ))->setPaper('a4', 'landscape');

        $filename = 'Laporan_Rincian_Aset_Bakesbangpol_' . date('Ymd_His') . '.pdf';
        return $isDownload ? $pdf->download($filename) : $pdf->stream($filename);
    }
}
