<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeAllowance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'periode_bulan',
        'status_kawin',
        'kd_jiwa',
        'jml_jiwa',
        'npwp',
        'nomor_rekening',
        'nama_bank',
        'masker',
        'tmt_sk',
        'gaji_pokok',
        'tunjangan_suami_istri',
        'tunjangan_anak',
        'jumlah_bruto_1',
        'tunjangan_umum',
        'tambahan_tunjangan_umum',
        'tunjangan_struktural',
        'tunjangan_fungsional',
        'tunjangan_beras',
        'tunjangan_pph',
        'pembulatan',
        'jumlah_kotor',
        'potongan_beras',
        'potongan_iwp_8',
        'potongan_iwp_1',
        'potongan_pph',
        'potongan_sewa_rumah',
        'potongan_hutang',
        'potongan_tabungan_rumah',
        'potongan_lain',
        'jumlah_potongan',
        'jumlah_dibayarkan',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'tmt_sk' => 'date',
            'gaji_pokok' => 'decimal:2',
            'tunjangan_suami_istri' => 'decimal:2',
            'tunjangan_anak' => 'decimal:2',
            'jumlah_bruto_1' => 'decimal:2',
            'tunjangan_umum' => 'decimal:2',
            'tambahan_tunjangan_umum' => 'decimal:2',
            'tunjangan_struktural' => 'decimal:2',
            'tunjangan_fungsional' => 'decimal:2',
            'tunjangan_beras' => 'decimal:2',
            'tunjangan_pph' => 'decimal:2',
            'pembulatan' => 'decimal:2',
            'jumlah_kotor' => 'decimal:2',
            'potongan_beras' => 'decimal:2',
            'potongan_iwp_8' => 'decimal:2',
            'potongan_iwp_1' => 'decimal:2',
            'potongan_pph' => 'decimal:2',
            'potongan_sewa_rumah' => 'decimal:2',
            'potongan_hutang' => 'decimal:2',
            'potongan_tabungan_rumah' => 'decimal:2',
            'potongan_lain' => 'decimal:2',
            'jumlah_potongan' => 'decimal:2',
            'jumlah_dibayarkan' => 'decimal:2',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function getHasTjSuamiIstriAttribute(): bool
    {
        return $this->status_kawin === 'K' || (float)$this->tunjangan_suami_istri > 0;
    }

    public function getJumlahAnakTanggunganAttribute(): int
    {
        if ($this->kd_jiwa && strlen((string)$this->kd_jiwa) >= 4) {
            return (int) substr((string)$this->kd_jiwa, -1);
        }
        return max(0, (int)$this->jml_jiwa - ($this->status_kawin === 'K' ? 2 : 1));
    }

    public function getHasTjAnakAttribute(): bool
    {
        return $this->jumlah_anak_tanggungan > 0 || (float)$this->tunjangan_anak > 0;
    }

    public function getHasTjStrukturalAttribute(): bool
    {
        return (float)$this->tunjangan_struktural > 0;
    }

    public function getHasTjFungsionalAttribute(): bool
    {
        return (float)$this->tunjangan_fungsional > 0;
    }

    public function getHasTjBerasAttribute(): bool
    {
        return (int)$this->jml_jiwa > 0 || (float)$this->tunjangan_beras > 0;
    }

    /**
     * Hitung ulang otomatis seluruh total penghasilan, potongan, dan take home pay
     */
    public function recalculate(): void
    {
        $this->jumlah_bruto_1 = (float)$this->gaji_pokok + (float)$this->tunjangan_suami_istri + (float)$this->tunjangan_anak;
        
        $this->jumlah_kotor = $this->jumlah_bruto_1 
            + (float)$this->tunjangan_umum 
            + (float)$this->tambahan_tunjangan_umum 
            + (float)$this->tunjangan_struktural 
            + (float)$this->tunjangan_fungsional 
            + (float)$this->tunjangan_beras 
            + (float)$this->tunjangan_pph 
            + (float)$this->pembulatan;

        $this->jumlah_potongan = (float)$this->potongan_beras 
            + (float)$this->potongan_iwp_8 
            + (float)$this->potongan_iwp_1 
            + (float)$this->potongan_pph 
            + (float)$this->potongan_sewa_rumah 
            + (float)$this->potongan_hutang 
            + (float)$this->potongan_tabungan_rumah 
            + (float)$this->potongan_lain;

        $this->jumlah_dibayarkan = max(0, $this->jumlah_kotor - $this->jumlah_potongan);
    }
}
