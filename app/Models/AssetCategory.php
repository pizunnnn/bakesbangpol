<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssetCategory extends Model
{
  use HasFactory;

  protected $fillable = ['name', 'description'];

  public function assets(): HasMany
  {
    return $this->hasMany(Asset::class, 'asset_category_id');
  }
}
