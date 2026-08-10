<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('asset_catalog', function (Blueprint $table): void {
      $table->id();
      $table->string('kode_barang')->unique();
      $table->string('nama_barang');
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('asset_catalog');
  }
};
