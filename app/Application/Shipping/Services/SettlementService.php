<?php
namespace App\Application\Shipping\Services;
use App\Domain\Models\Shipment;use App\Domain\Models\ShippingSettlement;use Illuminate\Support\Facades\DB;use InvalidArgumentException;
class SettlementService
{
 public function create(Shipment $shipment,float $commissionRate):ShippingSettlement{return DB::transaction(function()use($shipment,$commissionRate){$shipment=Shipment::with('order')->lockForUpdate()->findOrFail($shipment->id);if($shipment->settlement()->exists())throw new InvalidArgumentException('تم إنشاء تسوية لهذه الشحنة مسبقًا.');if($shipment->status!=='delivered')throw new InvalidArgumentException('لا يمكن تسوية شحنة قبل تسليمها.');if($commissionRate<0||$commissionRate>100)throw new InvalidArgumentException('نسبة العمولة غير صحيحة.');$amount=(float)$shipment->shipping_cost;$commission=round($amount*$commissionRate/100,2);return ShippingSettlement::create(['shipment_id'=>$shipment->id,'shipping_provider_id'=>$shipment->shipping_provider_id,'order_id'=>$shipment->order_id,'shipping_amount'=>$amount,'provider_amount'=>$amount-$commission,'commission_amount'=>$commission,'status'=>'pending']);});}
 public function settle(ShippingSettlement $settlement):ShippingSettlement{return DB::transaction(function()use($settlement){$s=ShippingSettlement::lockForUpdate()->findOrFail($settlement->id);if($s->status!=='pending')throw new InvalidArgumentException('التسوية تمت معالجتها مسبقًا.');$s->update(['status'=>'settled','settled_at'=>now()]);return $s->fresh(['provider','order','shipment']);});}
 public function pending(?string $providerId=null){return ShippingSettlement::with(['provider','order','shipment'])->where('status','pending')->when($providerId,fn($q)=>$q->where('shipping_provider_id',$providerId))->latest()->get();}
}
