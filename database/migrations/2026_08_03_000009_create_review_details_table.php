<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('review_details', function (Blueprint $table): void {
      $table->id();
      $table->foreignId('pppk_review_id')->constrained()->cascadeOnDelete();
      $table->string('indicator_name');
      $table->decimal('score', 5, 2)->nullable();
      $table->decimal('weight', 5, 2)->nullable();
      $table->text('notes')->nullable();
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('review_details');
  }
};
