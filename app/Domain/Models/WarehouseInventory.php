<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WarehouseInventory extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $table = 'warehouse_inventory';

    protected $fillable = [
        'warehouse_id',
        'product_id',
        'product_variant_id',
        'quantity',
        'reserved_quantity',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'reserved_quantity' => 'integer',
    ];

    protected $appends = [
        'available_quantity',
    ];

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(
            ProductVariant::class,
            'product_variant_id'
        );
    }

    public function getAvailableQuantityAttribute(): int
    {
        return max(
            0,
            $this->quantity - $this->reserved_quantity
        );
    }
}
