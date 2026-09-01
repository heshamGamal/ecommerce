<?php

namespace App\Application\Coupons\Services;

use App\Domain\Models\Coupon;
use App\Domain\Models\CouponUsage;
use Illuminate\Support\Carbon;
use InvalidArgumentException;

class CouponService
{
    public function findValid(string $code, int|string $userId, float $subtotal): Coupon
    {
        $coupon = Coupon::whereRaw('LOWER(code) = ?', [strtolower(trim($code))])->first();
        if (!$coupon || !$coupon->is_active) throw new InvalidArgumentException('الكوبون غير صالح أو غير فعال.');
        $now = Carbon::now();
        if (($coupon->starts_at && $coupon->starts_at->isFuture()) || ($coupon->expires_at && $coupon->expires_at->isPast())) throw new InvalidArgumentException('الكوبون خارج فترة الصلاحية.');
        if ($coupon->usage_limit !== null && $coupon->used_count >= $coupon->usage_limit) throw new InvalidArgumentException('تم استنفاد مرات استخدام الكوبون.');
        if ($coupon->min_order_amount !== null && $subtotal < (float) $coupon->min_order_amount) throw new InvalidArgumentException('قيمة الطلب لا تحقق الحد الأدنى للكوبون.');
        if ($coupon->user_limit !== null && CouponUsage::where('coupon_id', $coupon->id)->where('user_id', $userId)->count() >= $coupon->user_limit) throw new InvalidArgumentException('لقد تجاوزت حد استخدام هذا الكوبون.');
        return $coupon;
    }

    public function discount(Coupon $coupon, float $subtotal): float
    {
        $amount = $coupon->type === 'percentage' ? $subtotal * ((float) $coupon->value / 100) : (float) $coupon->value;
        return round(min($subtotal, max(0, $amount)), 2);
    }
}
