<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetAssignment extends Model
{
  use HasFactory;

  protected $fillable = [
    'asset_id',
    'employee_id',
    'assigned_at',
    'returned_at',
    'status',
    'notes',
  ];

  protected function casts(): array
  {
    return [
      'assigned_at' => 'datetime',
      'returned_at' => 'datetime',
    ];
  }

  public function asset(): BelongsTo
  {
    return $this->belongsTo(Asset::class);
  }

  public function employee(): BelongsTo
  {
    return $this->belongsTo(Employee::class);
  }
}
