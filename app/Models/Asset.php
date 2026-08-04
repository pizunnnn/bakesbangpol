<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
    'location',
    'photo',
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

  public function assignments(): HasMany
  {
    return $this->hasMany(AssetAssignment::class);
  }

  public function maintenances(): HasMany
  {
    return $this->hasMany(MaintenanceAsset::class);
  }
}
