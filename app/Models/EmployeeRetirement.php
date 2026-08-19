<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeRetirement extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'tanggal_pensiun',
        'status_pensiun',
        'nomor_sk',
        'keterangan',
        'dokumen_sk',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pensiun' => 'date',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
