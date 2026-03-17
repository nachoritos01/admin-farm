<?php

namespace App\Models;

use App\Enums\QualityGrade;
use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductionRecord extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'hen_batch_id',
        'date',
        'qty_morning',
        'qty_afternoon',
        'qty_total',
        'broken',
        'net_production',
        'quality_grade',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'qty_morning' => 'integer',
        'qty_afternoon' => 'integer',
        'qty_total' => 'integer',
        'broken' => 'integer',
        'net_production' => 'integer',
        'quality_grade' => QualityGrade::class,
    ];

    protected static function booted(): void
    {
        static::saving(function (self $record): void {
            $record->qty_total = $record->qty_morning + $record->qty_afternoon;
            $record->net_production = $record->qty_total - $record->broken;
        });
    }

    // Relationships

    public function henBatch(): BelongsTo
    {
        return $this->belongsTo(HenBatch::class);
    }

    // Scopes

    public function scopeForDate(Builder $query, string $date): Builder
    {
        return $query->where('date', $date);
    }

    public function scopeForDateRange(Builder $query, string $from, string $to): Builder
    {
        return $query->whereBetween('date', [$from, $to]);
    }
}
