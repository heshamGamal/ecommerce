<?php
namespace App\Domain\Models;
use Illuminate\Database\Eloquent\Concerns\HasUuids;use Illuminate\Database\Eloquent\Model;
class Shipment extends Model{use HasUuids;public $incrementing=false;protected $keyType='string';protected $fillable=['order_id','shipping_provider_id','shipping_zone_id','tracking_number','status','shipping_cost','shipped_at','delivered_at'];protected $casts=['shipping_cost'=>'decimal:2','shipped_at'=>'datetime','delivered_at'=>'datetime'];public function order(){return $this->belongsTo(Order::class);}public function provider(){return $this->belongsTo(ShippingProvider::class,'shipping_provider_id');}public function settlement(){return $this->hasOne(ShippingSettlement::class);}}
