<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReviewDetail extends Model
{
  use HasFactory;

protected $fillable = [
    'pppk_review_id',
    'kegiatan_date',
    'kegiatan_time',
    'uraian',
    'indicator_name',
    'score',
    'weight',
    'notes',
  ];

protected function casts(): array
  {
    return [
      'kegiatan_date' => 'date',
      'score' => 'decimal:2',
      'weight' => 'decimal:2',
    ];
  }

  public function review(): BelongsTo
  {
    return $this->belongsTo(PppkReview::class, 'pppk_review_id');
  }
}
