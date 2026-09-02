<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Employee extends Model
{
  use HasFactory;

  protected $fillable = [
    'user_id',
    'department_id',
    'position_id',
    'pangkat_golongan',
    'employee_number',
    'full_name',
    'gender',
    'birth_place',
    'birth_date',
    'phone',
    'email',
    'address',
    'join_date',
    'employment_status',
    'status_pegawai',
    'unit_kerja',
    'photo',
  ];

  protected function casts(): array
  {
    return [
      'birth_date' => 'date',
      'join_date' => 'date',
    ];
  }

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  public function department(): BelongsTo
  {
    return $this->belongsTo(Department::class);
  }

  public function position(): BelongsTo
  {
    return $this->belongsTo(Position::class);
  }

  public function assetAssignments(): HasMany
  {
    return $this->hasMany(AssetAssignment::class);
  }

  public function pppkReviews(): HasMany
  {
    return $this->hasMany(PppkReview::class);
  }

  public function trainings(): HasMany
  {
    return $this->hasMany(EmployeeTraining::class)->orderBy('tanggal_mulai', 'desc');
  }

  public function salaryHistories(): HasMany
  {
    return $this->hasMany(EmployeeSalaryHistory::class)->orderBy('tanggal_mulai_berlaku', 'desc');
  }

  public function rankHistories(): HasMany
  {
    return $this->hasMany(EmployeeRankHistory::class)->orderBy('tanggal_kenaikan', 'desc');
  }

  public function positionHistories(): HasMany
  {
    return $this->hasMany(EmployeePositionHistory::class)->orderBy('tanggal_mulai', 'desc');
  }

  public function retirements(): HasMany
  {
    return $this->hasMany(EmployeeRetirement::class)->orderBy('tanggal_pensiun', 'desc');
  }

  public function allowance(): HasOne
  {
    return $this->hasOne(EmployeeAllowance::class)->latestOfMany();
  }

  public function allowances(): HasMany
  {
    return $this->hasMany(EmployeeAllowance::class)->orderBy('id', 'desc');
  }

  // ==================== 1. KALKULASI MASA KERJA ====================
  public function getMasaKerjaTahunAttribute(): int
  {
    if (!$this->join_date) {
      return 0;
    }
    return (int) $this->join_date->diffInYears(Carbon::now());
  }

  public function getMasaKerjaBulanAttribute(): int
  {
    if (!$this->join_date) {
      return 0;
    }
    return (int) ($this->join_date->diffInMonths(Carbon::now()) % 12);
  }

  public function getMasaKerjaAttribute(): string
  {
    if (!$this->join_date) {
      return '-';
    }
    return "{$this->masa_kerja_tahun} Tahun {$this->masa_kerja_bulan} Bulan";
  }

  // ==================== 2. OTOMATISASI KENAIKAN GAJI BERKALA (2 TAHUN) ====================
  public function getTanggalKgbBerikutnyaAttribute(): ?Carbon
  {
    // Cari TMT Gaji Berkala terakhir dari history
    $latestSalary = $this->salaryHistories()->first();
    $baseDate = $latestSalary ? $latestSalary->tanggal_mulai_berlaku : $this->join_date;

    if (!$baseDate) {
      return null;
    }

    return $baseDate->copy()->addYears(2);
  }

  public function getIsEligibleKgbAttribute(): bool
  {
    $nextKgb = $this->tanggal_kgb_berikutnya;
    if (!$nextKgb) {
      return false;
    }

    return Carbon::now()->startOfDay()->gte($nextKgb->startOfDay());
  }

  // ==================== 3. OTOMATISASI KENAIKAN PANGKAT (4 TAHUN) ====================
  public function getTanggalKenaikanPangkatBerikutnyaAttribute(): ?Carbon
  {
    // Cari TMT Kenaikan Pangkat terakhir dari history
    $latestRank = $this->rankHistories()->first();
    $baseDate = $latestRank ? $latestRank->tanggal_kenaikan : $this->join_date;

    if (!$baseDate) {
      return null;
    }

    return $baseDate->copy()->addYears(4);
  }

  public function getIsEligibleKenaikanPangkatAttribute(): bool
  {
    $nextRank = $this->tanggal_kenaikan_pangkat_berikutnya;
    if (!$nextRank) {
      return false;
    }

    return Carbon::now()->startOfDay()->gte($nextRank->startOfDay());
  }

  // ==================== 4. OTOMATISASI PENSIUN (58 TAHUN) ====================
  public function getUsiaAttribute(): int
  {
    if (!$this->birth_date) {
      return 0;
    }
    return (int) $this->birth_date->diffInYears(Carbon::now());
  }

  public function getTanggalPensiunOtomatisAttribute(): ?Carbon
  {
    if (!$this->birth_date) {
      return null;
    }
    return $this->birth_date->copy()->addYears(58);
  }

  public function getIsSudahPensiunAttribute(): bool
  {
    $tglPensiun = $this->tanggal_pensiun_otomatis;
    if (!$tglPensiun) {
      return false;
    }

    return Carbon::now()->startOfDay()->gte($tglPensiun->startOfDay());
  }

  /**
   * Pengecekan otomatis & pembaruan status pensiun jika pegawai telah mencapai usia 58 tahun
   */
  public function checkAndUpdateStatusPensiun(): void
  {
    if ($this->is_sudah_pensiun) {
      // Ubah status keaktifan menjadi tidak aktif (Pensiun)
      if ($this->employment_status !== 'inactive') {
        $this->update(['employment_status' => 'inactive']);
      }

      // Buat entri pensiun otomatis di history jika belum pernah ada
      if ($this->retirements()->count() === 0) {
        $this->retirements()->create([
          'tanggal_pensiun' => $this->tanggal_pensiun_otomatis ?? Carbon::now(),
          'status_pensiun' => 'Pensiun BUP (Batas Usia 58 Tahun)',
          'keterangan' => 'Status pensiun otomatis disesuaikan oleh sistem karena telah mencapai batas usia 58 tahun.',
        ]);
      }
    }
  }
}
