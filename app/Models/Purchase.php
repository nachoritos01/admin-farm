<?php

namespace App\Models;

use App\Enums\PaymentMethod;
use App\Enums\PurchaseUnit;
use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Purchase extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'supplier_id',
        'product',
        'quantity',
        'unit',
        'unit_price',
        'total',
        'date',
        'notes',
        'payment_method',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'total' => 'decimal:2',
        'date' => 'date',
        'unit' => PurchaseUnit::class,
        'payment_method' => PaymentMethod::class,
    ];

    protected static function booted(): void
    {
        static::saving(function (self $purchase): void {
            $purchase->total = (float) $purchase->quantity * (float) $purchase->unit_price;
        });
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
}
