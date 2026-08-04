<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
  use HasFactory;

  protected $fillable = [
    'user_id',
    'activity_type',
    'description',
    'payload',
  ];

  protected function casts(): array
  {
    return [
      'payload' => 'array',
    ];
  }
}
