<?php
namespace App\Application\Users\DTOs;
final class AddressData{public function __construct(public readonly array $attributes){}public static function fromArray(array $data):self{return new self($data);}}
