<?php
namespace App\Domain\Contracts\Repositories;
use App\Domain\Models\Cart;
interface CartRepositoryInterface{public function getOrCreate(string $userId):Cart;public function add(Cart $cart,string $productId,?string $variantId,int $quantity):Cart;public function update(Cart $cart,string $itemId,int $quantity):Cart;public function remove(Cart $cart,string $itemId):Cart;public function clear(Cart $cart):void;}
