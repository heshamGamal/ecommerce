<?php
namespace App\Application\Ports;
interface PaymentGatewayInterface{public function confirm(string $reference,float $amount,array $payload=[]):array;}
