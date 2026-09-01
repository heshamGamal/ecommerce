<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class CouponUsage extends Model
{
    use HasUuids;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['coupon_id', 'user_id', 'order_id', 'discount_amount', 'used_at'];
    protected $casts = ['discount_amount' => 'decimal:2', 'used_at' => 'datetime'];
    public $timestamps = false;
}
