<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('employees', function (Blueprint $table): void {
      $table->id();
      $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
      $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
      $table->foreignId('position_id')->nullable()->constrained()->nullOnDelete();
      $table->string('employee_number')->unique();
      $table->string('full_name');
      $table->enum('gender', ['male', 'female'])->nullable();
      $table->string('birth_place')->nullable();
      $table->date('birth_date')->nullable();
      $table->string('phone')->nullable();
      $table->string('email')->nullable()->unique();
      $table->text('address')->nullable();
      $table->date('join_date')->nullable();
      $table->string('employment_status')->default('active');
      $table->string('photo')->nullable();
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('employees');
  }
};
