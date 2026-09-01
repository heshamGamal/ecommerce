<?php
namespace App\Application\Shipping\Services;
use App\Domain\Models\ShippingProvider;use App\Domain\Models\ShippingRate;use Illuminate\Database\Eloquent\ModelNotFoundException;use InvalidArgumentException;
class ShippingCostService
{
 public function calculate(string $providerId,int $zoneId,float $weight=0):array{$provider=ShippingProvider::whereKey($providerId)->where('is_active',true)->firstOrFail();$rate=ShippingRate::where('shipping_provider_id',$provider->id)->where('shipping_zone_id',$zoneId)->where('is_active',true)->first();if(!$rate)throw new ModelNotFoundException('لا توجد خدمة شحن لهذه المنطقة.');$cost=round((float)$rate->base_cost+((float)$rate->per_kg_cost*max(0,$weight)),2);return ['provider'=>$provider,'zone_id'=>$zoneId,'weight'=>$weight,'shipping_cost'=>$cost];}
}
