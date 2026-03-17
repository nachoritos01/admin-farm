<?php

namespace App\Models;

use App\Enums\HenMovementType;
use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HenMovement extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'hen_batch_id',
        'type',
        'quantity',
        'date',
        'reason',
        'notes',
    ];

    protected $casts = [
        'type' => HenMovementType::class,
        'quantity' => 'integer',
        'date' => 'date',
    ];

    protected static function booted(): void
    {
        static::saved(function (self $movement): void {
            /** @var HenBatch $batch */
            $batch = $movement->henBatch;
            $batch->recalculateCurrentCount();
        });

        static::deleted(function (self $movement): void {
            /** @var HenBatch $batch */
            $batch = $movement->henBatch;
            $batch->recalculateCurrentCount();
        });
    }

    // Relationships

    public function henBatch(): BelongsTo
    {
        return $this->belongsTo(HenBatch::class);
    }
}
