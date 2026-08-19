<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            if (!Schema::hasColumn('employees', 'pangkat_golongan')) {
                $table->string('pangkat_golongan')->nullable()->after('position_id');
            }
        });

        // Migrate existing status values
        DB::table('employees')->where('status_pegawai', 'Pegawai Tetap')->update(['status_pegawai' => 'PNS']);
        DB::table('employees')->where('status_pegawai', 'P3K Paruh Waktu')->update(['status_pegawai' => 'PPPK Paruh Waktu']);
        DB::table('employees')->where('status_pegawai', 'Outsourcing')->update(['status_pegawai' => 'PPPK Paruh Waktu']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            if (Schema::hasColumn('employees', 'pangkat_golongan')) {
                $table->dropColumn('pangkat_golongan');
            }
        });
    }
};
