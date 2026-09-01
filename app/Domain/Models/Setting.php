<?php
namespace App\Domain\Models;
use Illuminate\Database\Eloquent\Concerns\HasUuids;use Illuminate\Database\Eloquent\Model;
class Setting extends Model{use HasUuids;public $incrementing=false;protected $keyType='string';protected $fillable=['key','display_name','value','type','group','is_locked'];protected $casts=['is_locked'=>'boolean'];public function typedValue(){return match($this->type){'boolean'=>filter_var($this->value,FILTER_VALIDATE_BOOLEAN),'number'=>(float)$this->value,'json'=>json_decode($this->value,true),'array'=>json_decode($this->value,true)??[],'null'=>null,default=>$this->value};}}
