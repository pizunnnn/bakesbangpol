<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetCatalog extends Model
{
  use HasFactory;

  protected $table = 'asset_catalog';

  protected $fillable = [
    'kode_barang',
    'nama_barang',
  ];
}
