<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class PaymentConfirmRequest extends FormRequest{public function authorize():bool{return true;}public function rules():array{return ['status'=>['required','in:successful,failed'],'transaction_id'=>['nullable','string','max:255'],'payload'=>['nullable','array']];}}
