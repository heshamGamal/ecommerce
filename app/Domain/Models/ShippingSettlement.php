<?php
namespace App\Domain\Models;
use Illuminate\Database\Eloquent\Concerns\HasUuids;use Illuminate\Database\Eloquent\Model;
class ShippingSettlement extends Model{use HasUuids;public $incrementing=false;protected $keyType='string';protected $fillable=['shipment_id','shipping_provider_id','order_id','shipping_amount','provider_amount','commission_amount','status','settled_at'];protected $casts=['shipping_amount'=>'decimal:2','provider_amount'=>'decimal:2','commission_amount'=>'decimal:2','settled_at'=>'datetime'];public function shipment(){return $this->belongsTo(Shipment::class,'shipment_id');}public function provider(){return $this->belongsTo(ShippingProvider::class,'shipping_provider_id');}public function order(){return $this->belongsTo(Order::class);}}
