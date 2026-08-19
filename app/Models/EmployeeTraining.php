<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeTraining extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'nama_pelatihan',
        'jenis_pelatihan',
        'penyelenggara',
        'tanggal_mulai',
        'tanggal_selesai',
        'lokasi',
        'nomor_sertifikat',
        'keterangan',
        'file_sertifikat',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
