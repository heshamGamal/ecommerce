<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class AddressRequest extends FormRequest{public function authorize():bool{return true;}public function rules():array{$p=$this->isMethod('patch')||$this->isMethod('put');return ['address_title'=>[$p?'sometimes':'required','string','max:100'],'full_name'=>[$p?'sometimes':'required','string','max:255'],'phone'=>[$p?'sometimes':'required','string','max:50'],'country'=>['sometimes','string','max:100'],'city'=>[$p?'sometimes':'required','string','max:100'],'state'=>['nullable','string','max:100'],'street_address'=>[$p?'sometimes':'required','string','max:500'],'postal_code'=>['nullable','string','max:30'],'is_default'=>['sometimes','boolean']];}}
