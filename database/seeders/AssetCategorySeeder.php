<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\AssetCategory;
use Illuminate\Database\Seeder;

class AssetCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Sarana Prasarana Komputer',
                'description' => 'Perangkat komputer, server, printer, laptop, scanner, UPS, dan perangkat jaringan IT.',
            ],
            [
                'name' => 'Komputer / Desktop',
                'description' => 'Unit PC desktop dan workstation.',
            ],
            [
                'name' => 'Laptop / Notebook',
                'description' => 'Laptop, notebook, dan ultrabook dinas.',
            ],
            [
                'name' => 'Printer & Scanner',
                'description' => 'Printer laser, inkjet, multifunction printer, dan document scanner.',
            ],
            [
                'name' => 'Server & Perangkat Jaringan',
                'description' => 'Server rackmount/tower, switch, router, access point, dan rack server.',
            ],
            [
                'name' => 'UPS & Daya Listrik IT',
                'description' => 'Uninterruptible Power Supply (UPS) dan stabilizer daya komputer.',
            ],
            [
                'name' => 'Kendaraan Dinas / Operasional',
                'description' => 'Kendaraan roda 4 dan roda 2 dinas Bakesbangpol.',
            ],
            [
                'name' => 'Peralatan Mesin & Perkantoran',
                'description' => 'Genset, AC, proyektor, sound system, mesin penghancur kertas.',
            ],
            [
                'name' => 'Mebel & Furniture Perkantoran',
                'description' => 'Meja kerja, kursi, lemari arsip, sekat ruangan.',
            ],
        ];

        foreach ($categories as $cat) {
            AssetCategory::firstOrCreate(
                ['name' => $cat['name']],
                ['description' => $cat['description']]
            );
        }
    }
}
