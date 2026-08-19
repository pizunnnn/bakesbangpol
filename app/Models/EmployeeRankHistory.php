<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeRankHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'pangkat_lama',
        'pangkat_baru',
        'tanggal_kenaikan',
        'nomor_sk',
        'keterangan',
        'dokumen_sk',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_kenaikan' => 'date',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
