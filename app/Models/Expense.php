<?php

namespace App\Models;

use App\Enums\ExpenseCategory;
use App\Enums\PaymentMethod;
use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'supplier_id',
        'category',
        'amount',
        'description',
        'date',
        'payment_method',
        'notes',
    ];

    protected $casts = [
        'category' => ExpenseCategory::class,
        'amount' => 'decimal:2',
        'date' => 'date',
        'payment_method' => PaymentMethod::class,
    ];

    // Relationships

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    // Scopes

    public function scopeByCategory(Builder $query, ExpenseCategory $category): Builder
    {
        return $query->where('category', $category);
    }

    public function scopeForDateRange(Builder $query, string $from, string $to): Builder
    {
        return $query->whereBetween('date', [$from, $to]);
    }
}
