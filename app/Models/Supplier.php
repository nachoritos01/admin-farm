<?php

namespace App\Models;

use App\Enums\SupplierCategory;
use App\Enums\SupplierStatus;
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
        'address',
        'category',
        'products',
        'rating',
        'status',
        'notes',
    ];

    protected $casts = [
        'category' => SupplierCategory::class,
        'products' => 'array',
        'rating' => 'integer',
        'status' => SupplierStatus::class,
    ];

    // Relationships

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }

    // Scopes

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
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
