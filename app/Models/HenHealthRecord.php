<?php

namespace App\Models;

use App\Enums\HealthRecordType;
use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HenHealthRecord extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'hen_batch_id',
        'type',
        'date',
        'medication',
        'dosage',
        'next_due_date',
        'notes',
    ];

    protected $casts = [
        'type' => HealthRecordType::class,
        'date' => 'date',
        'next_due_date' => 'date',
    ];

    // Relationships

    public function henBatch(): BelongsTo
    {
        return $this->belongsTo(HenBatch::class);
    }

    // Scopes

    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->whereNotNull('next_due_date')
            ->where('next_due_date', '>=', now()->toDateString())
            ->orderBy('next_due_date');
    }

    public function scopeOverdue(Builder $query): Builder
    {
        return $query->whereNotNull('next_due_date')
            ->where('next_due_date', '<', now()->toDateString())
            ->orderBy('next_due_date');
    }

    public function scopeByType(Builder $query, HealthRecordType $type): Builder
    {
        return $query->where('type', $type);
    }
}
