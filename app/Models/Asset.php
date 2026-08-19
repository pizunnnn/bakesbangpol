<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Asset extends Model
{
  use HasFactory;

  protected $fillable = [
    'asset_category_id',
    'asset_code',
    'category',
    'brand',
    'model',
    'serial_number',
    'purchase_date',
    'purchase_price',
    'condition',
    'status',
    'current_employee_id',
    'location',
    'bidang',
    // BMD fields
    'kode_barang',
    'no_register',
    'nama_barang',
    'merk_tipe',
    'spesifikasi',
    'cara_perolehan',
    'tahun_perolehan',
    'nilai_perolehan',
    'keadaan',
    'umur_ekonomis',
    'jumlah_unit',
    'photo',
  ];

  protected function casts(): array
  {
    return [
      'purchase_date' => 'date',
      'purchase_price' => 'decimal:2',
      'tahun_perolehan' => 'integer',
      'nilai_perolehan' => 'decimal:2',
      'umur_ekonomis' => 'integer',
      'jumlah_unit' => 'integer',
    ];
  }

  public function categoryRelation(): BelongsTo
  {
    return $this->belongsTo(AssetCategory::class, 'asset_category_id');
  }

  public function currentEmployee(): BelongsTo
  {
    return $this->belongsTo(Employee::class, 'current_employee_id');
  }

  public function assignments(): HasMany
  {
    return $this->hasMany(AssetAssignment::class);
  }

  public function maintenances(): HasMany
  {
    return $this->hasMany(AssetMaintenance::class)->orderBy('maintenance_date', 'desc');
  }

  public function vehicleRepairs(): HasMany
  {
    return $this->hasMany(VehicleRepair::class)->orderBy('repair_date', 'desc');
  }

  public function histories(): HasMany
  {
    return $this->hasMany(AssetHistory::class)->orderBy('created_at', 'desc');
  }

  /**
   * Tanggal Perolehan berbasis purchase_date atau tahun_perolehan
   */
  public function getTanggalPerolehanAttribute(): ?Carbon
  {
    if ($this->purchase_date) {
      return $this->purchase_date;
    }

    if ($this->tahun_perolehan) {
      return Carbon::createFromDate((int)$this->tahun_perolehan, 1, 1);
    }

    return null;
  }

  /**
   * Perhitungan presisi Umur Aset (Tahun) menggunakan Carbon
   */
  public function getAgeInYearsAttribute(): float
  {
    $tglPerolehan = $this->tanggal_perolehan;
    if (!$tglPerolehan) {
      return 0.0;
    }

    return round($tglPerolehan->diffInDays(Carbon::now()) / 365.25, 1);
  }

  /**
   * Umur Aset format ringkas (Tahun & Bulan)
   */
  public function getAgeFormattedAttribute(): string
  {
    $tglPerolehan = $this->tanggal_perolehan;
    if (!$tglPerolehan) {
      return '-';
    }

    $diff = $tglPerolehan->diff(Carbon::now());
    return "{$diff->y} Tahun {$diff->m} Bulan";
  }

  /**
   * Apakah aset sudah berumur >= 10 tahun (Eligible untuk Penghapusan)
   */
  public function getIsEligibleDisposalAttribute(): bool
  {
    $tglPerolehan = $this->tanggal_perolehan;
    if (!$tglPerolehan) {
      return false;
    }

    // Tepat 10 tahun dari tanggal perolehan
    return Carbon::now()->startOfDay()->gte($tglPerolehan->copy()->addYears(10)->startOfDay());
  }

  /**
   * Memeriksa apakah aset merupakan Kendaraan
   */
  public function getIsVehicleAttribute(): bool
  {
    $catName = strtolower($this->categoryRelation->name ?? $this->category ?? '');
    $namaBarang = strtolower($this->nama_barang ?? '');

    return str_contains($catName, 'kendaraan') || str_contains($namaBarang, 'bus') || str_contains($namaBarang, 'mobil') || str_contains($namaBarang, 'motor') || str_contains($namaBarang, 'hiace');
  }

  /**
   * Mencatat aktivitas Lifecycle ke AssetHistory
   */
  public function logHistory(string $eventType, string $description, ?array $payload = null): AssetHistory
  {
    return $this->histories()->create([
      'user_id' => Auth::id(),
      'event_type' => $eventType,
      'description' => $description,
      'payload' => $payload,
    ]);
  }
}
