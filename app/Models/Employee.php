<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
  use HasFactory;

  protected $fillable = [
    'user_id',
    'department_id',
    'position_id',
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
}
