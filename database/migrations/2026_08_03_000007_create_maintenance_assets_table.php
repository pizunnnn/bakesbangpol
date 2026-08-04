<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('maintenance_assets', function (Blueprint $table): void {
      $table->id();
      $table->foreignId('asset_id')->constrained()->cascadeOnDelete();
      $table->date('maintenance_date')->nullable();
      $table->text('description')->nullable();
      $table->decimal('cost', 15, 2)->nullable();
      $table->string('status')->default('scheduled');
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('maintenance_assets');
  }
};
