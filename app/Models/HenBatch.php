<?php

namespace App\Models;

use App\Enums\HenBatchStatus;
use App\Models\Concerns\BelongsToTenant;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HenBatch extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'name',
        'breed',
        'initial_count',
        'current_count',
        'age_weeks',
        'acquisition_date',
        'status',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'initial_count' => 'integer',
        'current_count' => 'integer',
        'age_weeks' => 'integer',
        'acquisition_date' => 'date',
        'status' => HenBatchStatus::class,
        'is_active' => 'boolean',
    ];

    // Relationships

    public function movements(): HasMany
    {
        return $this->hasMany(HenMovement::class);
    }

    public function productionRecords(): HasMany
    {
        return $this->hasMany(ProductionRecord::class);
    }

    public function healthRecords(): HasMany
    {
        return $this->hasMany(HenHealthRecord::class);
    }

    // Scopes

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeByStatus(Builder $query, HenBatchStatus $status): Builder
    {
        return $query->where('status', $status);
    }

    // Helpers

    public function recalculateCurrentCount(): void
    {
        $additions = $this->movements()
            ->where('type', 'addition')
            ->sum('quantity');

        $removals = $this->movements()
            ->whereIn('type', ['removal', 'death', 'transfer', 'sale'])
            ->sum('quantity');

        $this->update([
            'current_count' => $this->initial_count + $additions - $removals,
        ]);
    }

    public function getAgeWeeksAttribute(): int
    {
        if ($this->acquisition_date) {
            return (int) Carbon::parse($this->acquisition_date)->diffInWeeks(now());
        }

        return (int) ($this->attributes['age_weeks'] ?? 0);
    }

    public function getMortalityRateAttribute(): float
    {
        $deaths = $this->movements()
            ->where('type', 'death')
            ->sum('quantity');

        $total = $this->initial_count + $this->movements()
            ->where('type', 'addition')
            ->sum('quantity');

        return $total > 0 ? round(($deaths / $total) * 100, 2) : 0;
    }
}
