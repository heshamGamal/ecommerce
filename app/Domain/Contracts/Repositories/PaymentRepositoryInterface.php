<?php
namespace App\Domain\Contracts\Repositories;
use App\Domain\Models\PaymentTransaction;use App\Domain\Models\Order;
interface PaymentRepositoryInterface{public function createForOrder(Order $order,array $data):PaymentTransaction;public function findForUser(string $paymentId,string $userId):PaymentTransaction;public function confirm(PaymentTransaction $payment,array $data):PaymentTransaction;}
