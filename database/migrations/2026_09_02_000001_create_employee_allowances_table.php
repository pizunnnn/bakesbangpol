<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_allowances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            
            // 1. DATA PRIBADI PEMILIK TUNJANGAN & KELUARGA
            $table->string('periode_bulan')->default('Desember 2024');
            $table->string('status_kawin')->default('K'); // K = Kawin, TK = Tidak Kawin, HB = Hidup Berpisah
            $table->string('kd_jiwa')->nullable()->default('1100'); // e.g. 1102, 1101, 1100, 1000
            $table->integer('jml_jiwa')->default(1); // e.g. 4, 3, 2, 1
            $table->string('npwp')->nullable();
            $table->string('nomor_rekening')->nullable();
            $table->string('nama_bank')->default('Bank bjb');
            $table->string('masker')->nullable(); // Masa Kerja Golongan / Gaji, e.g. '32', '24'
            $table->date('tmt_sk')->nullable();

            // 2. KOMPONEN PENGHASILAN (INCOME)
            $table->decimal('gaji_pokok', 15, 2)->default(0);
            $table->decimal('tunjangan_suami_istri', 15, 2)->default(0);
            $table->decimal('tunjangan_anak', 15, 2)->default(0);
            $table->decimal('jumlah_bruto_1', 15, 2)->default(0);
            $table->decimal('tunjangan_umum', 15, 2)->default(0);
            $table->decimal('tambahan_tunjangan_umum', 15, 2)->default(0);
            $table->decimal('tunjangan_struktural', 15, 2)->default(0);
            $table->decimal('tunjangan_fungsional', 15, 2)->default(0);
            $table->decimal('tunjangan_beras', 15, 2)->default(0);
            $table->decimal('tunjangan_pph', 15, 2)->default(0);
            $table->decimal('pembulatan', 15, 2)->default(0);
            $table->decimal('jumlah_kotor', 15, 2)->default(0); // JML KOTOR

            // 3. KOMPONEN POTONGAN (DEDUCTIONS)
            $table->decimal('potongan_beras', 15, 2)->default(0);
            $table->decimal('potongan_iwp_8', 15, 2)->default(0); // IWP 8%
            $table->decimal('potongan_iwp_1', 15, 2)->default(0); // IWP 1%
            $table->decimal('potongan_pph', 15, 2)->default(0);   // PPH 21
            $table->decimal('potongan_sewa_rumah', 15, 2)->default(0); // S. RUMAH
            $table->decimal('potongan_hutang', 15, 2)->default(0);     // HUTANG
            $table->decimal('potongan_tabungan_rumah', 15, 2)->default(0); // T. RUMAH
            $table->decimal('potongan_lain', 15, 2)->default(0);       // LAIN-2
            $table->decimal('jumlah_potongan', 15, 2)->default(0); // JML POTONGAN

            // 4. JUMLAH YANG DIBAYARKAN (TAKE HOME PAY)
            $table->decimal('jumlah_dibayarkan', 15, 2)->default(0);

            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_allowances');
    }
};
