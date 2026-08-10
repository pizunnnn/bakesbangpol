<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\AssetCatalog;
use Illuminate\Database\Seeder;

class AssetCatalogSeeder extends Seeder
{
  public function run(): void
  {
    $catalogs = [
      ['kode_barang' => '1.3.2.02.01.02.003', 'nama_barang' => 'Kendaraan Mini Bus'],
      ['kode_barang' => '1.3.2.01.03.04.002', 'nama_barang' => 'Portable Generating Set'],
      ['kode_barang' => '1.3.2.05.01.01.001', 'nama_barang' => 'Personal Computer / Laptop'],
      ['kode_barang' => '1.3.2.05.02.01.004', 'nama_barang' => 'Printer / Scanner'],
    ];

    foreach ($catalogs as $catalog) {
      AssetCatalog::firstOrCreate(
        ['kode_barang' => $catalog['kode_barang']],
        $catalog
      );
    }
  }
}
