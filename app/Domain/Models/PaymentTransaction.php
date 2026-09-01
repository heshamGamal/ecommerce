<?php
namespace App\Domain\Models;
use Illuminate\Database\Eloquent\Concerns\HasUuids;use Illuminate\Database\Eloquent\Model;
class PaymentTransaction extends Model{use HasUuids;public $incrementing=false;protected $keyType='string';protected $fillable=['order_id','user_id','method','status','amount','transaction_id','payload'];protected $casts=['amount'=>'decimal:2','payload'=>'array'];public function order(){return $this->belongsTo(Order::class);}}
