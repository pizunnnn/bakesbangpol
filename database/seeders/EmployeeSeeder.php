<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeePositionHistory;
use App\Models\EmployeeRankHistory;
use App\Models\EmployeeTraining;
use App\Models\Position;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
  public function run(): void
  {
    $jsonPath = 'C:/Users/byzad/.gemini/antigravity/brain/682c6b4d-a12c-48c1-a7a0-164c083f4e9f/scratch/extracted_employees.json';
    if (!file_exists($jsonPath)) {
      $jsonPath = base_path('data_pegawai.json');
    }

    if (!file_exists($jsonPath)) {
      $this->command->error("File data pegawai JSON tidak ditemukan.");
      return;
    }

    $rawList = json_decode(file_get_contents($jsonPath), true);
    if (!is_array($rawList)) {
      $this->command->error("Format JSON tidak valid.");
      return;
    }

    // Pastikan Unit Kerja standar ada
    $unitKerjaMap = [
      'Sekretariat' => ['code' => 'Sekretariat', 'name' => 'Sekretariat'],
      'POLDAGRI' => ['code' => 'POLDAGRI', 'name' => 'Politik Dalam Negeri (POLDAGRI)'],
      'IDWASBANG' => ['code' => 'IDWASBANG', 'name' => 'Ideologi dan Wawasan Kebangsaan (IDWASBANG)'],
      'KESBAK' => ['code' => 'KESBAK', 'name' => 'Ketahanan Ekonomi, Seni, Budaya, Agama, dan Kemasyarakatan (KESBAK)'],
      'WASDA' => ['code' => 'WASDA', 'name' => 'Kewaspadaan Daerah (WASDA)'],
    ];

    $departments = [];
    foreach ($unitKerjaMap as $key => $info) {
      $departments[$key] = Department::firstOrCreate(
        ['code' => $info['code']],
        ['name' => $info['name'], 'description' => 'Unit kerja / bidang Bakesbangpol']
      );
    }

    $insertedCount = 0;
    $updatedCount = 0;

    foreach ($rawList as $item) {
      $idInfo = $this->parseIdentity($item['raw_identity'] ?? '');
      $fullName = $idInfo['full_name'];
      if (empty($fullName)) {
        continue;
      }

      $nip = $idInfo['nip'];
      $pangkatGol = trim($item['pangkat_gol'] ?? '');
      $tmtPangkat = $this->parseDate($item['tmt_pangkat'] ?? null);
      $rawJabatan = $item['jabatan'] ?? '';
      $tmtJabatan = $this->parseDate($item['tmt_jabatan'] ?? null);
      $cleanedJabatan = $this->cleanJabatan($rawJabatan);

      // Tentukan Status Pegawai
      $statusPegawai = $this->detectStatusPegawai($nip, $pangkatGol, $rawJabatan);

      // Tentukan Unit Kerja
      $unitKey = $this->detectUnitKerja($rawJabatan, $item['catatan'] ?? '');
      $department = $departments[$unitKey] ?? $departments['Sekretariat'];
      $unitKerjaName = $department->name;

      // Cari atau buat Jabatan di tabel positions
      $position = Position::firstOrCreate(
        ['name' => $cleanedJabatan],
        [
          'level' => (stripos($cleanedJabatan, 'Kepala') !== false || stripos($cleanedJabatan, 'Sekretaris') !== false) ? 'Eselon' : 'Staff',
          'description' => 'Jabatan di lingkungan Bakesbangpol',
        ]
      );

      // Tentukan Join Date (TMT CPNS / Pengangkatan) dari NIP jika ada
      $joinDate = null;
      if ($nip && strlen($nip) === 18) {
        $tmtYear = substr($nip, 8, 4);
        $tmtMonth = substr($nip, 12, 2);
        if (is_numeric($tmtYear) && is_numeric($tmtMonth) && (int)$tmtMonth >= 1 && (int)$tmtMonth <= 12) {
          $joinDate = Carbon::createFromDate((int)$tmtYear, (int)$tmtMonth, 1)->format('Y-m-d');
        }
      }
      if (!$joinDate) {
        $joinDate = $tmtPangkat ?? $tmtJabatan ?? Carbon::now()->format('Y-m-d');
      }

      // Generate Email internal jika belum ada
      $email = null;
      if ($nip) {
        $email = $nip . '@bakesbangpol.jabarprov.go.id';
      }

      // Cari pegawai berdasarkan NIP atau Nama
      $employee = null;
      if ($nip) {
        $employee = Employee::where('employee_number', $nip)->first();
      }
      if (!$employee) {
        $employee = Employee::where('full_name', $fullName)->first();
      }

      $dataToSave = [
        'full_name' => $fullName,
        'employee_number' => $nip,
        'department_id' => $department->id,
        'position_id' => $position->id,
        'unit_kerja' => $unitKerjaName,
        'pangkat_golongan' => $pangkatGol,
        'gender' => $idInfo['gender'],
        'birth_place' => $idInfo['birth_place'],
        'birth_date' => $idInfo['birth_date'],
        'join_date' => $joinDate,
        'employment_status' => 'active',
        'status_pegawai' => $statusPegawai,
        'email' => $email,
      ];

      if ($employee) {
        $employee->update($dataToSave);
        $updatedCount++;
      } else {
        $employee = Employee::create($dataToSave);
        $insertedCount++;
      }

      // Simpan Riwayat Pangkat
      if ($pangkatGol && $tmtPangkat) {
        EmployeeRankHistory::firstOrCreate(
          [
            'employee_id' => $employee->id,
            'pangkat_baru' => $pangkatGol,
            'tanggal_kenaikan' => $tmtPangkat,
          ],
          [
            'keterangan' => 'Pangkat/Golongan terdata dari DUK Kepegawaian',
          ]
        );
      }

      // Simpan Riwayat Jabatan
      if ($cleanedJabatan && $tmtJabatan) {
        EmployeePositionHistory::firstOrCreate(
          [
            'employee_id' => $employee->id,
            'nama_jabatan' => $cleanedJabatan,
            'tanggal_mulai' => $tmtJabatan,
          ],
          [
            'unit_kerja' => $unitKerjaName,
            'keterangan' => 'Jabatan terdata dari DUK Kepegawaian',
          ]
        );
      }

      // Simpan Diklat jika ada
      $diklatNama = trim($item['diklat_nama'] ?? '');
      if (!empty($diklatNama)) {
        $diklatTahun = trim($item['diklat_tahun'] ?? '');
        $diklatBulan = trim($item['diklat_bulan'] ?? '01');
        $diklatDate = null;
        if (is_numeric($diklatTahun) && (int)$diklatTahun > 1970) {
          $m = (is_numeric($diklatBulan) && (int)$diklatBulan >= 1 && (int)$diklatBulan <= 12) ? (int)$diklatBulan : 1;
          $diklatDate = Carbon::createFromDate((int)$diklatTahun, $m, 1)->format('Y-m-d');
        }

        EmployeeTraining::firstOrCreate(
          [
            'employee_id' => $employee->id,
            'nama_pelatihan' => $diklatNama,
          ],
          [
            'jenis_pelatihan' => 'Diklat Struktural / Kepemimpinan',
            'tanggal_mulai' => $diklatDate,
            'keterangan' => 'Jam Pelatihan: ' . ($item['diklat_jam'] ?? '-') . ' Jam',
          ]
        );
      }

      // Otomatis cek dan sesuaikan status pensiun jika usia >= 58 tahun
      $employee->checkAndUpdateStatusPensiun();
    }

    $this->command->info("Berhasil memproses data pegawai! Baru: {$insertedCount}, Diperbarui: {$updatedCount}, Total: " . ($insertedCount + $updatedCount));
  }

  private function parseIdentity(string $raw): array
  {
    $lines = array_values(array_filter(array_map('trim', explode("\n", $raw))));
    $fullName = $lines[0] ?? '';
    $nip = null;
    $birthPlace = null;
    $birthDate = null;

    foreach (array_slice($lines, 1) as $line) {
      if (stripos($line, 'NIP:') !== false || preg_match('/^\d{8}/', str_replace(' ', '', $line))) {
        $cleaned = preg_replace('/[^0-9]/', '', $line);
        if (strlen($cleaned) >= 18) {
          $nip = $cleaned;
        }
      }
      if (strpos($line, '/') !== false && !preg_match('/NIP:/i', $line)) {
        $parts = explode('/', $line, 2);
        $birthPlace = trim($parts[0]);
        $dateStr = trim($parts[1]);
        if (preg_match('/(\d{1,2})-(\d{1,2})-(\d{4})/', $dateStr, $m)) {
          $birthDate = sprintf('%04d-%02d-%02d', $m[3], $m[2], $m[1]);
        }
      }
    }

    // Deteksi jenis kelamin dari digit ke-15 NIP (1 = Laki-laki, 2 = Perempuan)
    $gender = null;
    if ($nip && strlen($nip) === 18) {
      $gDigit = substr($nip, 14, 1);
      if ($gDigit === '1') {
        $gender = 'male';
      } elseif ($gDigit === '2') {
        $gender = 'female';
      }
    }

    // Fallback jika tidak ada NIP
    if (!$gender) {
      $fFemaleNames = ['Hj.', 'Dra.', 'Siti', 'Neneng', 'Yuli', 'Endah', 'Sri', 'Luh Nyoman', 'Eli', 'Eva', 'Rumondang', 'Widya', 'Teti', 'Herijani', 'Diani', 'Viena', 'Nia'];
      foreach ($fFemaleNames as $fn) {
        if (stripos($fullName, $fn) !== false) {
          $gender = 'female';
          break;
        }
      }
    }
    if (!$gender) {
      $gender = 'male';
    }

    return [
      'full_name' => $fullName,
      'nip' => $nip,
      'birth_place' => $birthPlace,
      'birth_date' => $birthDate,
      'gender' => $gender,
    ];
  }

  private function parseDate(?string $dateStr): ?string
  {
    if (!$dateStr) {
      return null;
    }
    $dateStr = trim($dateStr);
    if (preg_match('/^(\d{1,2})-(\d{1,2})-(\d{4})$/', $dateStr, $m)) {
      return sprintf('%04d-%02d-%02d', $m[3], $m[2], $m[1]);
    }
    if (preg_match('/^(\d{4})-(\d{1,2})-(\d{1,2})$/', $dateStr, $m)) {
      return sprintf('%04d-%02d-%02d', $m[1], $m[2], $m[3]);
    }
    return null;
  }

  private function detectStatusPegawai(?string $nip, string $pangkatGol, string $jabatan): string
  {
    if ($pangkatGol && in_array(strtoupper(trim($pangkatGol)), ['IX', 'VII', 'X', 'VIII', 'XI', 'XII', 'V'])) {
      return 'PPPK';
    }
    if ($nip && strlen($nip) === 18 && substr($nip, 8, 6) === '202321') {
      return 'PPPK';
    }
    if (stripos($jabatan, 'PPPK') !== false || stripos($pangkatGol, 'PPPK') !== false) {
      return 'PPPK';
    }
    if (stripos($jabatan, 'Honorer') !== false || stripos($jabatan, 'Driver') !== false || stripos($jabatan, 'Kebersihan') !== false) {
      return 'Non-ASN';
    }
    return 'PNS';
  }

  private function detectUnitKerja(string $jabatan, string $catatan): string
  {
    $text = strtoupper($jabatan . ' ' . $catatan);
    if (strpos($text, 'KEWASPADAAN') !== false || strpos($text, 'WASDA') !== false) {
      return 'WASDA';
    }
    if (strpos($text, 'IDW') !== false || strpos($text, 'IDEOLOGI') !== false || strpos($text, 'IDIOLOGI') !== false || strpos($text, 'WAWASAN') !== false) {
      return 'IDWASBANG';
    }
    if (strpos($text, 'KETAHANAN') !== false || strpos($text, 'KESBAK') !== false || strpos($text, 'EKONOMI') !== false) {
      return 'KESBAK';
    }
    if (strpos($text, 'POLITIK DALAM') !== false || strpos($text, 'POLDAGRI') !== false || strpos($text, 'DALAM NEGRI') !== false) {
      return 'POLDAGRI';
    }
    return 'Sekretariat';
  }

  private function cleanJabatan(string $jabatan): string
  {
    $lines = explode("\n", $jabatan);
    $main = trim($lines[0]);
    $main = preg_replace('/\s+/', ' ', $main);
    return ucwords(strtolower($main));
  }
}
