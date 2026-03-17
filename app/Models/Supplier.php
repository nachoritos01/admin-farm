<?php

namespace App\Models;

use App\Enums\SupplierCategory;
use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'name',
        'contact_name',
        'phone',
        'email',
        'category',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'category' => SupplierCategory::class,
        'is_active' => 'boolean',
    ];

    // Relationships

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    // Scopes

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory(Builder $query, SupplierCategory $category): Builder
    {
        return $query->where('category', $category);
    }

    public function scopeSearch(Builder $query, string $term): Builder
    {
        return $query->where('name', 'ilike', "%{$term}%")
            ->orWhere('contact_name', 'ilike', "%{$term}%");
    }
}
