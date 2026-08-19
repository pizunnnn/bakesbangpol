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
        Schema::create('employee_retirements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->date('tanggal_pensiun');
            $table->string('status_pensiun')->default('Dalam Proses'); // Dalam Proses, Pensiun, Ditolak, dll.
            $table->string('nomor_sk')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('dokumen_sk')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_retirements');
    }
};
