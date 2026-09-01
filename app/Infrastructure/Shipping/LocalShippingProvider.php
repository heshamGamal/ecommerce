<?php
namespace App\Infrastructure\Shipping;
use App\Application\Ports\ShippingProviderInterface;
class LocalShippingProvider implements ShippingProviderInterface{public function calculate(string $zone,float $weight):float{return 0.0;}public function createShipment(array $order):array{return ['status'=>'pending','order'=>$order];}}
