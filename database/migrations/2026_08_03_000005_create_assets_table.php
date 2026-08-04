<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('assets', function (Blueprint $table): void {
      $table->id();
      $table->foreignId('asset_category_id')->nullable()->constrained('asset_categories')->nullOnDelete();
      $table->string('asset_code')->unique();
      $table->string('category')->nullable();
      $table->string('brand')->nullable();
      $table->string('model')->nullable();
      $table->string('serial_number')->nullable()->unique();
      $table->date('purchase_date')->nullable();
      $table->decimal('purchase_price', 15, 2)->nullable();
      $table->string('condition')->default('good');
      $table->string('status')->default('available');
      $table->string('location')->nullable();
      $table->string('photo')->nullable();
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('assets');
  }
};
