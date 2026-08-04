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
        Schema::table('pppk_reviews', function (Blueprint $table) {
            $table->string('nipkkk')->nullable()->after('employee_id');
            $table->string('jabatan')->nullable()->after('nipkkk');
            $table->string('pptk_nama')->nullable()->after('year');
            $table->string('pptk_nip')->nullable()->after('pptk_nama');
            $table->string('periode_bulan')->nullable()->after('pptk_nip');
            $table->unsignedSmallInteger('periode_tahun')->nullable()->after('periode_bulan');
        });

        Schema::table('review_details', function (Blueprint $table) {
            $table->date('kegiatan_date')->nullable()->after('pppk_review_id');
            $table->string('kegiatan_time')->nullable()->after('kegiatan_date');
            $table->text('uraian')->nullable()->after('kegiatan_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pppk_reviews', function (Blueprint $table) {
            $table->dropColumn(['nipkkk', 'jabatan', 'pptk_nama', 'pptk_nip', 'periode_bulan', 'periode_tahun']);
        });

        Schema::table('review_details', function (Blueprint $table) {
            $table->dropColumn(['kegiatan_date', 'kegiatan_time', 'uraian']);
        });
    }
};
