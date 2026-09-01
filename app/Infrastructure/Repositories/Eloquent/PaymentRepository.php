<?php
namespace App\Infrastructure\Repositories\Eloquent;
use App\Domain\Contracts\Repositories\PaymentRepositoryInterface;use App\Domain\Models\PaymentTransaction;use App\Domain\Models\Order;
class PaymentRepository implements PaymentRepositoryInterface{public function createForOrder(Order $order,array $data):PaymentTransaction{return $order->payments()->create($data);}public function findForUser(string $paymentId,string $userId):PaymentTransaction{return PaymentTransaction::whereKey($paymentId)->whereHas('order',fn($q)=>$q->where('user_id',$userId))->findOrFail($paymentId);}public function confirm(PaymentTransaction $payment,array $data):PaymentTransaction{$payment->update($data);return $payment->fresh('order');}}
