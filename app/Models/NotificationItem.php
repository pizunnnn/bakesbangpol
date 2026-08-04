<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationItem extends Model
{
  use HasFactory;

  protected $fillable = [
    'user_id',
    'title',
    'message',
    'type',
    'read_at',
    'payload',
  ];

  protected function casts(): array
  {
    return [
      'read_at' => 'datetime',
      'payload' => 'array',
    ];
  }
}
