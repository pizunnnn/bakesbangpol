<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeSalaryHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'tanggal_mulai_berlaku',
        'gaji_pokok',
        'pangkat_golongan',
        'nomor_sk',
        'keterangan',
        'dokumen_sk',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai_berlaku' => 'date',
            'gaji_pokok' => 'decimal:2',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
