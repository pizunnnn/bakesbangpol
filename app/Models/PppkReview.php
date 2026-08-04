<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PppkReview extends Model
{
  use HasFactory;

protected $fillable = [
    'employee_id',
    'nama',
    'nipkkk',
    'jabatan',
    'evaluation_period',
    'year',
    'pptk_nama',
    'pptk_nip',
    'periode_bulan',
    'periode_tahun',
    'work_target_achievement',
    'work_quality',
    'discipline',
    'attendance',
    'communication',
    'teamwork',
    'responsibility',
    'innovation',
    'leadership',
    'comments',
    'supporting_documents',
    'final_score',
    'status',
    'reviewed_by',
    'reviewed_at',
  ];

  protected function casts(): array
  {
    return [
      'reviewed_at' => 'datetime',
      'final_score' => 'decimal:2',
    ];
  }

  public function employee(): BelongsTo
  {
    return $this->belongsTo(Employee::class);
  }

  public function details(): HasMany
  {
    return $this->hasMany(ReviewDetail::class);
  }
}
