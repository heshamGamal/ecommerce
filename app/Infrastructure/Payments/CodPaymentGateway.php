<?php
namespace App\Infrastructure\Payments;
use App\Application\Ports\PaymentGatewayInterface;
class CodPaymentGateway implements PaymentGatewayInterface{public function confirm(string $reference,float $amount,array $payload=[]):array{return ['reference'=>$reference,'amount'=>$amount,'status'=>'successful','payload'=>$payload];}}
