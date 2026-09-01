<?php
namespace App\Http\Controllers\Api\V1;
use App\Application\Shipping\Services\SettlementService;use App\Http\Controllers\Controller;use App\Domain\Models\Shipment;use App\Domain\Models\ShippingSettlement;use Illuminate\Http\JsonResponse;use Illuminate\Http\Request;
class SettlementController extends Controller
{
 public function __construct(private readonly SettlementService $service){}
 public function store(Request $r,string $shipment):JsonResponse{$d=$r->validate(['commission_rate'=>['required','numeric','between:0,100']]);return response()->json(['status'=>'success','data'=>$this->service->create(Shipment::findOrFail($shipment),(float)$d['commission_rate'])],201);}
 public function settle(string $settlement):JsonResponse{return response()->json(['status'=>'success','data'=>$this->service->settle(ShippingSettlement::findOrFail($settlement))]);}
 public function pending(Request $r):JsonResponse{return response()->json(['status'=>'success','data'=>$this->service->pending($r->query('provider_id'))]);}
}
