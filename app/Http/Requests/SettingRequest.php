<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class SettingRequest extends FormRequest{public function authorize():bool{return true;}public function rules():array{return ['key'=>['required','string','max:150'],'display_name'=>['required','string','max:255'],'value'=>['nullable'],'type'=>['required','in:text,textarea,boolean,number,json,array,color,select,file'],'group'=>['required','string','max:100'],'is_locked'=>['sometimes','boolean']];}}
