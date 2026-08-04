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
        Schema::table('assets', function (Blueprint $table) {
            $table->string('kode_barang')->nullable()->after('asset_code');
            $table->string('no_register')->nullable()->after('kode_barang');
            $table->string('nama_barang')->nullable()->after('no_register');
            $table->string('merk_tipe')->nullable()->after('nama_barang');
            $table->text('spesifikasi')->nullable()->after('merk_tipe');
            $table->string('cara_perolehan')->nullable()->after('spesifikasi');
            $table->year('tahun_perolehan')->nullable()->after('cara_perolehan');
            $table->decimal('nilai_perolehan', 15, 2)->nullable()->after('tahun_perolehan');
            $table->string('keadaan')->default('B')->after('nilai_perolehan');
            $table->unsignedInteger('umur_ekonomis')->nullable()->after('keadaan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn([
                'kode_barang',
                'no_register',
                'nama_barang',
                'merk_tipe',
                'spesifikasi',
                'cara_perolehan',
                'tahun_perolehan',
                'nilai_perolehan',
                'keadaan',
                'umur_ekonomis',
            ]);
        });
    }
};
