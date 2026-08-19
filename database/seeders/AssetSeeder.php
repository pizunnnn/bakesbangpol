<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\AssetCategory;
use Illuminate\Database\Seeder;

class AssetSeeder extends Seeder
{
  public function run(): void
  {
    $landCategory = AssetCategory::firstOrCreate(
      ['name' => 'Tanah & Bangunan'],
      ['description' => 'Tanah dan Bangunan Kantor Pemerintah Bakesbangpol']
    );

    $assets = [
      [
        'asset_category_id' => $landCategory->id,
        'kode_barang' => '1.3.1.01.01.04.001',
        'no_register' => '1',
        'nama_barang' => 'Tanah Bangunan Kantor Pemerintah',
        'merk_tipe' => 'Hak Pakai Tanggal 5-12-2014 / 10.15.14.04.4.00006',
        'spesifikasi' => 'Luas: 2.200 m2. Alamat: Jl. Supratman No. 44 Kel. Sukamaju, Kec. Cibeunying Kidul, Kota Bandung',
        'cara_perolehan' => 'Sertipikat Hak Pakai',
        'tahun_perolehan' => 2000,
        'purchase_date' => '2000-01-01',
        'nilai_perolehan' => 2523400000,
        'purchase_price' => 2523400000,
        'keadaan' => 'B',
        'condition' => 'Baik',
        'umur_ekonomis' => 50,
        'jumlah_unit' => 1,
        'status' => 'Aktif',
        'bidang' => 'Sekretariat',
        'location' => 'Jl. Supratman No. 44 Kota Bandung',
        'asset_code' => '1.3.1.01.01.04.001/001',
      ],
      [
        'kode_barang' => '1.3.2.01.03.04.002',
        'no_register' => '1',
        'nama_barang' => 'Portable Generating Set',
        'merk_tipe' => 'Honda EU30i - 3000 Watt',
        'spesifikasi' => 'Genset portable 3000 Watt, 4 tak, bensin 4.5L',
        'cara_perolehan' => 'Belanja Modal TW II 2024',
        'tahun_perolehan' => 2024,
        'purchase_date' => '2024-06-01',
        'nilai_perolehan' => 18500000,
        'purchase_price' => 18500000,
        'keadaan' => 'B',
        'condition' => 'Baik',
        'umur_ekonomis' => 5,
        'jumlah_unit' => 2,
        'status' => 'Aktif',
        'bidang' => 'Sekretariat',
        'location' => 'Gedung Utama',
        'asset_code' => '1.3.2.01.03.04.002/001',
      ],
      [
        'kode_barang' => '1.3.2.02.01.02.003',
        'no_register' => '2',
        'nama_barang' => 'Mini Bus',
        'merk_tipe' => 'Toyota HiAce Commuter',
        'spesifikasi' => 'Bakal Roda 4, 15 seat, diesel 2.5L. No. Polisi: D 7001 BP',
        'cara_perolehan' => 'Belanja Modal TW I 2023',
        'tahun_perolehan' => 2023,
        'purchase_date' => '2023-03-01',
        'nilai_perolehan' => 780000000,
        'purchase_price' => 780000000,
        'keadaan' => 'B',
        'condition' => 'Baik',
        'umur_ekonomis' => 10,
        'jumlah_unit' => 1,
        'status' => 'Aktif',
        'bidang' => 'Sekretariat',
        'location' => 'Garasi Dinas',
        'asset_code' => '1.3.2.02.01.02.003/002',
      ],
    ];

    foreach ($assets as $asset) {
      $existing = Asset::where('kode_barang', $asset['kode_barang'])->where('no_register', $asset['no_register'])->first();
      if ($existing) {
        unset($asset['asset_code']);
        $existing->update($asset);
      } else {
        Asset::create($asset);
      }
    }
  }
}
