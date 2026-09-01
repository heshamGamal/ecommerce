<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasUuids;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['code', 'type', 'value', 'min_order_amount', 'usage_limit', 'user_limit', 'used_count', 'is_active', 'starts_at', 'expires_at'];
    protected $casts = ['value' => 'decimal:2', 'min_order_amount' => 'decimal:2', 'starts_at' => 'datetime', 'expires_at' => 'datetime', 'is_active' => 'boolean'];
}
