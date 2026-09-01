<?php
namespace App\Http\Controllers\Api\V1;
use App\Application\Shipping\Services\ShippingCostService;use App\Http\Controllers\Controller;use Illuminate\Http\JsonResponse;use Illuminate\Http\Request;
class ShippingController extends Controller{public function __construct(private readonly ShippingCostService $service){}public function calculate(Request $r):JsonResponse{$d=$r->validate(['provider_id'=>['required','uuid','exists:shipping_providers,id'],'zone_id'=>['required','integer','exists:shipping_zones,id'],'weight'=>['nullable','numeric','min:0']]);return response()->json(['status'=>'success','data'=>$this->service->calculate($d['provider_id'],$d['zone_id'],(float)($d['weight']??0))]);}}
