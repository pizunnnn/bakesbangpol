<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('pppk_reviews', function (Blueprint $table): void {
      $table->id();
      $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
      $table->string('evaluation_period');
      $table->unsignedSmallInteger('year');
      $table->decimal('work_target_achievement', 5, 2)->nullable();
      $table->decimal('work_quality', 5, 2)->nullable();
      $table->decimal('discipline', 5, 2)->nullable();
      $table->decimal('attendance', 5, 2)->nullable();
      $table->decimal('communication', 5, 2)->nullable();
      $table->decimal('teamwork', 5, 2)->nullable();
      $table->decimal('responsibility', 5, 2)->nullable();
      $table->decimal('innovation', 5, 2)->nullable();
      $table->decimal('leadership', 5, 2)->nullable();
      $table->text('comments')->nullable();
      $table->json('supporting_documents')->nullable();
      $table->decimal('final_score', 5, 2)->nullable();
      $table->string('status')->default('draft');
      $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
      $table->dateTime('reviewed_at')->nullable();
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('pppk_reviews');
  }
};
