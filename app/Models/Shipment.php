<?php

namespace App\Models;

use App\Enums\ShipmentStatus;
use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shipment extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'driver_id',
        'status',
        'scheduled_date',
        'zone',
        'notes',
    ];

    protected $casts = [
        'status' => ShipmentStatus::class,
        'scheduled_date' => 'date',
    ];

    // Relationships

    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    // Scopes

    public function scopeByStatus(Builder $query, ShipmentStatus $status): Builder
    {
        return $query->where('status', $status);
    }

    public function scopeForDate(Builder $query, string $date): Builder
    {
        return $query->where('scheduled_date', $date);
    }

    public function scopeByZone(Builder $query, string $zone): Builder
    {
        return $query->where('zone', $zone);
    }

    // Accessors

    public function getOrderCountAttribute(): int
    {
        return $this->orders()->count();
    }

    public function getTotalAmountAttribute(): float
    {
        return (float) $this->orders()->sum('total');
    }
}
