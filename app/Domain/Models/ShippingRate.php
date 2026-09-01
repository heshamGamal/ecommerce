<?php
namespace App\Domain\Models;
use Illuminate\Database\Eloquent\Concerns\HasUuids;use Illuminate\Database\Eloquent\Model;
class ShippingRate extends Model{use HasUuids;public $incrementing=false;protected $keyType='string';protected $fillable=['shipping_provider_id','shipping_zone_id','base_cost','per_kg_cost','is_active'];protected $casts=['base_cost'=>'decimal:2','per_kg_cost'=>'decimal:2','is_active'=>'boolean'];public function provider(){return $this->belongsTo(ShippingProvider::class,'shipping_provider_id');}}
