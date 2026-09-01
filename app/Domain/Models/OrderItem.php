<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasUuids;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['order_id', 'product_id', 'product_variant_id', 'product_name', 'variant_name', 'unit_price', 'quantity', 'total_price'];
    protected $casts = ['unit_price' => 'decimal:2', 'total_price' => 'decimal:2', 'quantity' => 'integer'];
    public function order(): BelongsTo { return $this->belongsTo(Order::class); }
}
