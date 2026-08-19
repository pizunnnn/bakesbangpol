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
        Schema::create('employee_rank_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->string('pangkat_lama')->nullable();
            $table->string('pangkat_baru');
            $table->date('tanggal_kenaikan');
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
        Schema::dropIfExists('employee_rank_histories');
    }
};
