<?php
namespace App\Domain\Models;
use Illuminate\Database\Eloquent\Concerns\HasUuids;use Illuminate\Database\Eloquent\Model;
class ShippingProvider extends Model{use HasUuids;public $incrementing=false;protected $keyType='string';protected $fillable=['name','code','tracking_url_template','is_active','credentials'];protected $casts=['is_active'=>'boolean','credentials'=>'encrypted:array'];public function rates(){return $this->hasMany(ShippingRate::class);}}
