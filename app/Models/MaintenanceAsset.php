<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceAsset extends Model
{
  use HasFactory;

  protected $fillable = [
    'asset_id',
    'maintenance_date',
    'description',
    'cost',
    'status',
  ];

  protected function casts(): array
  {
    return [
      'maintenance_date' => 'date',
      'cost' => 'decimal:2',
    ];
  }

  public function asset(): BelongsTo
  {
    return $this->belongsTo(Asset::class);
  }
}
