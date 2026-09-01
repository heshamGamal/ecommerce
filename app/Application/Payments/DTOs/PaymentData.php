<?php
namespace App\Application\Payments\DTOs;
final class PaymentData{public function __construct(public readonly string $status,public readonly ?string $transactionId=null,public readonly array $payload=[]){ }public static function fromArray(array $data):self{return new self((string)($data['status']??'pending'),$data['transaction_id']??null,(array)($data['payload']??[]));}}
