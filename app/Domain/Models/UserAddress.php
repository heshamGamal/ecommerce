<?php
namespace App\Domain\Models;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
class UserAddress extends Model
{
 use HasUuids; public $incrementing=false; protected $keyType='string'; protected $fillable=['user_id','address_title','full_name','phone','country','city','state','street_address','postal_code','is_default']; protected $casts=['is_default'=>'boolean'];
 public function user(){return $this->belongsTo(User::class);}
}
