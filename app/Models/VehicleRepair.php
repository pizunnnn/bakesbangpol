<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleRepair extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_id',
        'license_plate',
        'repair_date',
        'damage_type',
        'repair_description',
        'workshop_name',
        'cost',
        'status',
        'receipt_number',
        'receipt_file',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'repair_date' => 'date',
            'cost' => 'decimal:2',
        ];
    }

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }
}
