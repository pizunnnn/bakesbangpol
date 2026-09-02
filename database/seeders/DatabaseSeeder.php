<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Position;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
  public function run(): void
  {
    $roles = ['Administrator', 'HR / Kepegawaian', 'PPPK Employee', 'Supervisor'];

    foreach ($roles as $roleName) {
      Role::findOrCreate($roleName);
    }

    $admin = User::firstOrCreate(
      ['email' => 'admin@simpeg-asset.local'],
      [
        'name' => 'Administrator',
        'password' => Hash::make('password'),
      ]
    );

    $admin->assignRole('Administrator');

    // Hapus unit kerja lama "Human Resources" jika masih ada
    Department::where('code', 'HUM')->delete();

    $unitKerja = [
      'Sekretariat' => 'Sekretariat',
      'POLDAGRI' => 'Politik Dalam Negeri (POLDAGRI)',
      'IDWASBANG' => 'Ideologi dan Wawasan Kebangsaan (IDWASBANG)',
      'KESBAK' => 'Ketahanan Ekonomi, Seni, Budaya, Agama, dan Kemasyarakatan (KESBAK)',
      'WASDA' => 'Kewaspadaan Daerah (WASDA)',
    ];

    foreach ($unitKerja as $kode => $nama) {
      Department::firstOrCreate([
        'code' => $kode,
      ], [
        'name' => $nama,
        'description' => 'Unit kerja / bidang Bakesbangpol',
      ]);
    }

    $jabatan = [
      'Kepala Badan Kesatuan Bangsa dan Politik Provinsi Jawa Barat',
      'Sekretaris Badan Kesatuan Bangsa dan Politik Jawa Barat',
      'Kepala Bidang Dalam Negri',
      'Kepala Bidang Ketahanan Ekonomi, Seni, Budaya, Agama, dan Kemasyarakatan',
      'Kepala Bidang Ideologi Dan Wawasan Kebangsaan',
      'Kepala Bidang Kewaspadaan Daerah',
      'Analis Kebijakan Ahli Muda',
      'Kepala Sub Bagian Keuangan dan Aset',
      'Perencana Ahli Pertama',
      'Penelaah Teknis Kebijakan',
      'Pengadministrasi Perkantoran',
      'Pengolah Data dan Informasi',
      'Ahli Pertama Analis Kebijakan',
      'Penata Layanan Operasional',
      'Operator Layanan Operasional',
      'Front Office',
      'Driver',
      'Petugas Kebersihan',
    ];

    foreach ($jabatan as $nama) {
      Position::firstOrCreate([
        'name' => $nama,
      ], [
        'level' => 'Staff',
        'description' => 'Jabatan Bakesbangpol',
      ]);
    }

    $this->call([
      AssetCategorySeeder::class,
      AssetSeeder::class,
      EmployeeSeeder::class,
    ]);
  }
}
