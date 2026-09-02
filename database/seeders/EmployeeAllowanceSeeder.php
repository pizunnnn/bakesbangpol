<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\EmployeeAllowance;

class EmployeeAllowanceSeeder extends Seeder
{
    public function run(): void
    {
        $exactEmployees = [
            '196412151992031005' => [
                'status_kawin' => 'K',
                'kd_jiwa' => '1102',
                'jml_jiwa' => 4,
                'npwp' => '08.765.432.1-423.000',
                'nomor_rekening' => '0009454731101',
                'nama_bank' => 'Bank bjb',
                'masker' => '32',
                'tmt_sk' => '2020-08-12',
                'gaji_pokok' => 6373200,
                'tunjangan_suami_istri' => 637320,
                'tunjangan_anak' => 254928,
                'jumlah_bruto_1' => 7265448,
                'tunjangan_umum' => 0,
                'tambahan_tunjangan_umum' => 0,
                'tunjangan_struktural' => 3250000,
                'tunjangan_fungsional' => 0,
                'tunjangan_beras' => 289680,
                'tunjangan_pph' => 208513,
                'pembulatan' => 8,
                'jumlah_kotor' => 11013649,
                'potongan_beras' => 0,
                'potongan_iwp_8' => 581236,
                'potongan_iwp_1' => 120000,
                'potongan_pph' => 208513,
                'potongan_sewa_rumah' => 0,
                'potongan_hutang' => 0,
                'potongan_tabungan_rumah' => 0,
                'potongan_lain' => 0,
                'jumlah_potongan' => 909749,
                'jumlah_dibayarkan' => 10103900,
            ],
            '197011231999031003' => [
                'status_kawin' => 'K',
                'kd_jiwa' => '1101',
                'jml_jiwa' => 3,
                'npwp' => '07.654.321.0-423.000',
                'nomor_rekening' => '0013210407498',
                'nama_bank' => 'Bank bjb',
                'masker' => '24',
                'tmt_sk' => '2023-09-01',
                'gaji_pokok' => 4971700,
                'tunjangan_suami_istri' => 497170,
                'tunjangan_anak' => 99434,
                'jumlah_bruto_1' => 5568304,
                'tunjangan_umum' => 0,
                'tambahan_tunjangan_umum' => 0,
                'tunjangan_struktural' => 1260000,
                'tunjangan_fungsional' => 0,
                'tunjangan_beras' => 217260,
                'tunjangan_pph' => 52279,
                'pembulatan' => 0,
                'jumlah_kotor' => 7097843,
                'potongan_beras' => 0,
                'potongan_iwp_8' => 445464,
                'potongan_iwp_1' => 120000,
                'potongan_pph' => 52279,
                'potongan_sewa_rumah' => 0,
                'potongan_hutang' => 0,
                'potongan_tabungan_rumah' => 0,
                'potongan_lain' => 0,
                'jumlah_potongan' => 617743,
                'jumlah_dibayarkan' => 6480100,
            ],
            '196707071988011003' => [
                'status_kawin' => 'K',
                'kd_jiwa' => '1100',
                'jml_jiwa' => 2,
                'npwp' => '06.543.210.9-423.000',
                'nomor_rekening' => '0013210414418',
                'nama_bank' => 'Bank bjb',
                'masker' => '32',
                'tmt_sk' => '2018-07-01',
                'gaji_pokok' => 5628300,
                'tunjangan_suami_istri' => 562830,
                'tunjangan_anak' => 0,
                'jumlah_bruto_1' => 6191130,
                'tunjangan_umum' => 0,
                'tambahan_tunjangan_umum' => 0,
                'tunjangan_struktural' => 1260000,
                'tunjangan_fungsional' => 0,
                'tunjangan_beras' => 144840,
                'tunjangan_pph' => 94546,
                'pembulatan' => 20,
                'jumlah_kotor' => 7690536,
                'potongan_beras' => 0,
                'potongan_iwp_8' => 495290,
                'potongan_iwp_1' => 120000,
                'potongan_pph' => 94546,
                'potongan_sewa_rumah' => 0,
                'potongan_hutang' => 0,
                'potongan_tabungan_rumah' => 0,
                'potongan_lain' => 0,
                'jumlah_potongan' => 709836,
                'jumlah_dibayarkan' => 6980700,
            ],
        ];

        $employees = Employee::all();
        $counter = 100;

        foreach ($employees as $emp) {
            $nipClean = preg_replace('/[^0-9]/', '', $emp->employee_number ?? '');

            if (isset($exactEmployees[$nipClean])) {
                $data = $exactEmployees[$nipClean];
                $data['employee_id'] = $emp->id;
                $data['periode_bulan'] = 'Desember 2024';
                EmployeeAllowance::updateOrCreate(['employee_id' => $emp->id], $data);
                continue;
            }

            // Generate realistic allowance data for others based on Golongan / Rank
            $pangkat = $emp->pangkat_golongan ?? 'III/a';
            $isGolIV = str_contains($pangkat, 'IV');
            $isGolIII = str_contains($pangkat, 'III');
            $isGolII = str_contains($pangkat, 'II');

            $gajiPokok = $isGolIV ? 4800000 : ($isGolIII ? 3800000 : ($isGolII ? 2900000 : 2200000));
            $isKawin = true;
            $jmlAnak = rand(1, 2);
            $jmlJiwa = 1 + ($isKawin ? 1 : 0) + $jmlAnak;
            $kdJiwa = '110' . $jmlAnak;

            $tjSuamiIstri = $isKawin ? ($gajiPokok * 0.10) : 0;
            $tjAnak = $gajiPokok * (0.02 * $jmlAnak);
            $bruto1 = $gajiPokok + $tjSuamiIstri + $tjAnak;

            $isStruktural = str_contains(strtolower($emp->position?->name ?? ''), 'kepala') || str_contains(strtolower($emp->position?->name ?? ''), 'sekretaris') || str_contains(strtolower($emp->position?->name ?? ''), 'kasubag');
            $tjStruktural = $isStruktural ? ($isGolIV ? 1260000 : 540000) : 0;
            $tjFungsional = !$isStruktural ? ($isGolIV ? 750000 : ($isGolIII ? 540000 : 360000)) : 0;
            $tjBeras = 72420 * $jmlJiwa;
            $tjPph = round($bruto1 * 0.015);
            $pembulatan = rand(0, 50);

            $jmlKotor = $bruto1 + $tjStruktural + $tjFungsional + $tjBeras + $tjPph + $pembulatan;

            $potIwp8 = round($bruto1 * 0.08);
            $potIwp1 = min(120000, round($bruto1 * 0.01));
            $potPph = $tjPph;
            $potLain = 0;
            $jmlPotongan = $potIwp8 + $potIwp1 + $potPph + $potLain;

            $jmlDibayarkan = $jmlKotor - $jmlPotongan;

            $counter++;
            $norek = '00132104' . str_pad((string)$counter, 5, '0', STR_PAD_LEFT);
            $npwp = sprintf('%02d.%03d.%03d.%01d-423.000', rand(10, 99), rand(100, 999), rand(100, 999), rand(1, 9));

            EmployeeAllowance::updateOrCreate(
                ['employee_id' => $emp->id],
                [
                    'periode_bulan' => 'Desember 2024',
                    'status_kawin' => 'K',
                    'kd_jiwa' => $kdJiwa,
                    'jml_jiwa' => $jmlJiwa,
                    'npwp' => $npwp,
                    'nomor_rekening' => $norek,
                    'nama_bank' => 'Bank bjb',
                    'masker' => (string)($emp->masa_kerja_tahun ?: rand(4, 28)),
                    'tmt_sk' => $emp->join_date ? $emp->join_date->format('Y-m-d') : '2020-01-01',
                    'gaji_pokok' => $gajiPokok,
                    'tunjangan_suami_istri' => $tjSuamiIstri,
                    'tunjangan_anak' => $tjAnak,
                    'jumlah_bruto_1' => $bruto1,
                    'tunjangan_umum' => 0,
                    'tambahan_tunjangan_umum' => 0,
                    'tunjangan_struktural' => $tjStruktural,
                    'tunjangan_fungsional' => $tjFungsional,
                    'tunjangan_beras' => $tjBeras,
                    'tunjangan_pph' => $tjPph,
                    'pembulatan' => $pembulatan,
                    'jumlah_kotor' => $jmlKotor,
                    'potongan_beras' => 0,
                    'potongan_iwp_8' => $potIwp8,
                    'potongan_iwp_1' => $potIwp1,
                    'potongan_pph' => $potPph,
                    'potongan_sewa_rumah' => 0,
                    'potongan_hutang' => 0,
                    'potongan_tabungan_rumah' => 0,
                    'potongan_lain' => $potLain,
                    'jumlah_potongan' => $jmlPotongan,
                    'jumlah_dibayarkan' => $jmlDibayarkan,
                ]
            );
        }
    }
}
