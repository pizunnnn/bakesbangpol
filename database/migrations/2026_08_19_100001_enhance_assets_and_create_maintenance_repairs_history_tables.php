<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Table asset_maintenances (Pemeliharaan Aset)
        if (!Schema::hasTable('asset_maintenances')) {
            Schema::create('asset_maintenances', function (Blueprint $table) {
                $table->id();
                $table->foreignId('asset_id')->constrained('assets')->cascadeOnDelete();
                $table->date('maintenance_date');
                $table->string('maintenance_type'); // Perawatan Rutin, Perbaikan, Penggantian Komponen, Pemeriksaan, Pemeliharaan Lainnya
                $table->text('description')->nullable();
                $table->string('condition_before')->nullable();
                $table->string('condition_after')->nullable();
                $table->string('vendor_name')->nullable();
                $table->decimal('cost', 15, 2)->default(0);
                $table->string('status')->default('Selesai'); // Diajukan, Dalam Perbaikan, Selesai, Dibatalkan
                $table->string('receipt_number')->nullable();
                $table->string('receipt_file')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // 2. Table vehicle_repairs (Perbaikan Kendaraan)
        if (!Schema::hasTable('vehicle_repairs')) {
            Schema::create('vehicle_repairs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('asset_id')->constrained('assets')->cascadeOnDelete();
                $table->string('license_plate')->nullable();
                $table->date('repair_date');
                $table->string('damage_type')->nullable();
                $table->text('repair_description')->nullable();
                $table->string('workshop_name')->nullable();
                $table->decimal('cost', 15, 2)->default(0);
                $table->string('status')->default('Selesai'); // Diajukan, Dalam Perbaikan, Selesai, Dibatalkan
                $table->string('receipt_number')->nullable();
                $table->string('receipt_file')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // 3. Table asset_histories (Rekam Jejak & Audit Trail Aset)
        if (!Schema::hasTable('asset_histories')) {
            Schema::create('asset_histories', function (Blueprint $table) {
                $table->id();
                $table->foreignId('asset_id')->constrained('assets')->cascadeOnDelete();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('event_type'); // Aset Dibuat, Perubahan Data, Perpindahan Lokasi, Perbaikan Kendaraan, Pemeliharaan, Upload Nota, dll.
                $table->text('description')->nullable();
                $table->json('payload')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_histories');
        Schema::dropIfExists('vehicle_repairs');
        Schema::dropIfExists('asset_maintenances');
    }
};
