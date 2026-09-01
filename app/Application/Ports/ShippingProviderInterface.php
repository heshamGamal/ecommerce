<?php
namespace App\Application\Ports;
interface ShippingProviderInterface{public function calculate(string $zone,float $weight):float;public function createShipment(array $order):array;}
