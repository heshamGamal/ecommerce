<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasUuids;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['order_number', 'user_id', 'subtotal', 'discount_amount', 'shipping_cost', 'grand_total', 'status', 'payment_status', 'payment_method', 'shipping_full_name', 'shipping_phone', 'shipping_city', 'shipping_address', 'notes'];
    protected $casts = ['subtotal' => 'decimal:2', 'discount_amount' => 'decimal:2', 'shipping_cost' => 'decimal:2', 'grand_total' => 'decimal:2'];
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function items(): HasMany { return $this->hasMany(OrderItem::class); }
    public function payments(): HasMany { return $this->hasMany(PaymentTransaction::class); }
}
