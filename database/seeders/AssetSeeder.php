<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Asset;
use Illuminate\Database\Seeder;

class AssetSeeder extends Seeder
{
  public function run(): void
  {
    $assets = [
      [
        'kode_barang' => '1.3.2.01.03.04.002',
        'no_register' => '1',
        'nama_barang' => 'Portable Generating Set',
        'merk_tipe' => 'Honda EU30i - 3000 Watt',
        'spesifikasi' => 'Genset portable 3000 Watt, 4 tak, bensin 4.5L',
        'cara_perolehan' => 'Belanja Modal TW II 2024',
        'tahun_perolehan' => 2024,
        'nilai_perolehan' => 18500000,
'keadaan' => 'B',
        'umur_ekonomis' => 5,
        'jumlah_unit' => 2,
        'status' => 'Disetujui',
        'asset_code' => 'AST-' . strtoupper(substr(md5(uniqid()), 0, 8)),
      ],
      [
        'kode_barang' => '1.3.2.02.01.02.003',
        'no_register' => '2',
        'nama_barang' => 'Mini Bus',
        'merk_tipe' => 'Toyota HiAce Commuter',
        'spesifikasi' => 'Bakal Roda 4, 15 seat, diesel 2.5L',
        'cara_perolehan' => 'Belanja Modal TW I 2023',
        'tahun_perolehan' => 2023,
        'nilai_perolehan' => 780000000,
'keadaan' => 'B',
        'umur_ekonomis' => 10,
        'jumlah_unit' => 1,
        'status' => 'Disetujui',
        'asset_code' => 'AST-' . strtoupper(substr(md5(uniqid()), 0, 8)),
      ],
    ];

    foreach ($assets as $asset) {
      Asset::firstOrCreate(
        ['kode_barang' => $asset['kode_barang'], 'no_register' => $asset['no_register']],
        $asset
      );
    }
  }
}
