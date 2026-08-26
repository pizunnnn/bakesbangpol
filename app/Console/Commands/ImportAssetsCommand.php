<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\AssetCatalog;
use Carbon\Carbon;
use Illuminate\Console\Command;
use ZipArchive;

class ImportAssetsCommand extends Command
{
    protected $signature = 'import:assets {--clear : Clear existing assets before import}';
    protected $description = 'Import selected asset sheets (TANAH, PM, GB, JIJ) from DataAsset.xlsx';

    public function handle(): int
    {
        $filePath = base_path('DataAsset.xlsx');

        if (!file_exists($filePath)) {
            $this->error("File DataAsset.xlsx tidak ditemukan di: {$filePath}");
            return Command::FAILURE;
        }

        if ($this->option('clear')) {
            $this->info("Membersihkan data aset lama...");
            Asset::truncate();
            $this->info("Data aset lama berhasil dibersihkan.");
        }

        $this->info("Membaca file DataAsset.xlsx...");

        $zip = new ZipArchive();
        if ($zip->open($filePath) !== true) {
            $this->error("Gagal membuka file DataAsset.xlsx");
            return Command::FAILURE;
        }

        // Shared strings
        $sharedStrings = [];
        if (($index = $zip->locateName('xl/sharedStrings.xml')) !== false) {
            $xml = simplexml_load_string($zip->getFromIndex($index));
            foreach ($xml->si as $val) {
                if (isset($val->t)) {
                    $sharedStrings[] = (string)$val->t;
                } elseif (isset($val->r)) {
                    $text = '';
                    foreach ($val->r as $r) {
                        $text .= (string)$r->t;
                    }
                    $sharedStrings[] = $text;
                } else {
                    $sharedStrings[] = '';
                }
            }
        }

        // Selected Target Sheets: 1=TANAH, 2=PM, 3=GB, 4=JIJ
        $targetSheets = [
            1 => 'TANAH',
            2 => 'PM',
            3 => 'GB',
            4 => 'JIJ',
        ];

        $totalImported = 0;
        $registerCounters = [];

        foreach ($targetSheets as $sheetNum => $sheetName) {
            $content = $zip->getFromName("xl/worksheets/sheet{$sheetNum}.xml");
            if (!$content) {
                continue;
            }

            $sheetXml = simplexml_load_string($content);
            $rows = $sheetXml->sheetData->row;
            $sheetCount = 0;

            foreach ($rows as $row) {
                $r = [];
                foreach ($row->c as $cell) {
                    $attr = $cell->attributes();
                    $type = (string)$attr['t'];
                    $val = (string)$cell->v;
                    if ($type === 's' && isset($sharedStrings[(int)$val])) {
                        $val = $sharedStrings[(int)$val];
                    }
                    $r[] = trim($val);
                }

                $kodeBarang = $r[1] ?? '';
                $namaBarang = $r[3] ?? '';

                if (preg_match('/^1\.[0-9]\.[0-9]/', $kodeBarang)) {
                    $noRegisterRaw = $r[2] ?? '1';
                    $merkTipe = $r[5] ?? $r[6] ?? '';
                    $spesifikasi = $r[6] ?? $r[11] ?? '';
                    $caraPerolehan = $r[7] ?? $r[13] ?? '';
                    $tahunPerolehan = (int)($r[9] ?? $r[15] ?? $r[4] ?? 0);

                    $nilaiPerolehan = 0.0;
                    foreach ([$r[13] ?? 0, $r[19] ?? 0, $r[21] ?? 0, $r[7] ?? 0] as $candidate) {
                        if (is_numeric($candidate) && (float)$candidate > 1000) {
                            $nilaiPerolehan = (float)$candidate;
                            break;
                        }
                    }

                    $jumlahUnit = (int)($r[12] ?? $r[18] ?? 1);
                    if ($jumlahUnit <= 0) {
                        $jumlahUnit = 1;
                    }

                    $umurEkonomis = (int)($r[14] ?? 0);
                    $validTahun = ($tahunPerolehan > 1900 && $tahunPerolehan < 2030) ? $tahunPerolehan : null;

                    // Determine Category ID
                    $categoryId = $this->determineCategoryId($sheetName, $namaBarang, $merkTipe, $kodeBarang);
                    $categoryName = AssetCategory::find($categoryId)?->name ?? 'Peralatan Mesin & Perkantoran';

                    // Generate guaranteed unique asset code
                    $counterKey = $kodeBarang;
                    if (!isset($registerCounters[$counterKey])) {
                        $registerCounters[$counterKey] = max(1, (int)$noRegisterRaw);
                    } else {
                        $registerCounters[$counterKey]++;
                    }
                    $seqNo = $registerCounters[$counterKey];
                    $uniqueCode = $kodeBarang . '.' . str_pad((string)$seqNo, 4, '0', STR_PAD_LEFT);

                    Asset::create([
                        'asset_category_id' => $categoryId,
                        'asset_code' => $uniqueCode,
                        'category' => $categoryName,
                        'brand' => substr($merkTipe, 0, 100),
                        'model' => substr($spesifikasi, 0, 150),
                        'serial_number' => null,
                        'purchase_date' => $validTahun ? "{$validTahun}-01-01" : null,
                        'purchase_price' => $nilaiPerolehan,
                        'condition' => 'Baik',
                        'status' => 'Aktif',
                        'location' => 'Bakesbangpol',
                        'kode_barang' => $kodeBarang,
                        'no_register' => (string)$seqNo,
                        'nama_barang' => $namaBarang,
                        'merk_tipe' => $merkTipe,
                        'spesifikasi' => $spesifikasi,
                        'cara_perolehan' => $caraPerolehan,
                        'tahun_perolehan' => $validTahun,
                        'nilai_perolehan' => $nilaiPerolehan,
                        'keadaan' => 'B',
                        'umur_ekonomis' => $umurEkonomis,
                        'jumlah_unit' => $jumlahUnit,
                    ]);

                    AssetCatalog::updateOrCreate(
                        ['kode_barang' => $kodeBarang],
                        ['nama_barang' => $namaBarang]
                    );

                    $sheetCount++;
                    $totalImported++;
                }
            }

            $this->info("Sheet [{$sheetName}]: Berhasil mengimpor {$sheetCount} aset.");
        }

        $zip->close();

        $this->info("SELESAI! Total {$totalImported} data aset berhasil diimpor ke database.");
        return Command::SUCCESS;
    }

    private function determineCategoryId(string $sheetName, string $namaBarang, string $merkTipe, string $kodeBarang): int
    {
        $str = strtolower($namaBarang . ' ' . $merkTipe . ' ' . $sheetName);

        if ($sheetName === 'TANAH' || $sheetName === 'GB' || str_contains($str, 'tanah') || str_contains($str, 'bangunan') || str_contains($str, 'gedung')) {
            return 10; // Tanah & Bangunan
        }
        if (str_contains($str, 'mini bus') || str_contains($str, 'mobil') || str_contains($str, 'motor') || str_contains($str, 'kendaraan') || str_contains($str, 'bus') || str_contains($str, 'hiace') || str_contains($str, 'innova') || str_contains($str, 'rush')) {
            return 7; // Kendaraan Dinas / Operasional
        }
        if (str_contains($str, 'laptop') || str_contains($str, 'notebook')) {
            return 3; // Laptop / Notebook
        }
        if (str_contains($str, 'komputer') || str_contains($str, 'pc ') || str_contains($str, 'desktop')) {
            return 2; // Komputer / Desktop
        }
        if (str_contains($str, 'printer') || str_contains($str, 'scanner') || str_contains($str, 'epson') || str_contains($str, 'canon')) {
            return 4; // Printer & Scanner
        }
        if (str_contains($str, 'router') || str_contains($str, 'switch') || str_contains($str, 'access point') || str_contains($str, 'wifi') || str_contains($str, 'jaringan') || $sheetName === 'JIJ') {
            return 5; // Server & Perangkat Jaringan
        }
        if (str_contains($str, 'ups') || str_contains($str, 'generator') || str_contains($str, 'genset') || str_contains($str, 'listrik')) {
            return 6; // UPS & Daya Listrik IT
        }
        if (str_contains($str, 'kursi') || str_contains($str, 'meja') || str_contains($str, 'lemari') || str_contains($str, 'rak') || str_contains($str, 'sofa') || str_contains($str, 'bifet') || str_contains($str, 'filing')) {
            return 9; // Mebel & Furniture Perkantoran
        }

        return 8; // Peralatan Mesin & Perkantoran
    }
}
