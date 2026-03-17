<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PriceHistory extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'item_id',
        'price',
        'previous_price',
        'effective_date',
        'changed_by',
        'reason',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'previous_price' => 'decimal:2',
        'effective_date' => 'date',
    ];

    // Relationships

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function changedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    // Accessors

    public function getChangePercentageAttribute(): ?float
    {
        if (! $this->previous_price || $this->previous_price == 0) {
            return null;
        }

        return round((($this->price - $this->previous_price) / $this->previous_price) * 100, 2);
    }
}
