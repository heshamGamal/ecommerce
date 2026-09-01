<?php
namespace App\Domain\Contracts\Repositories;
use App\Domain\Models\UserAddress;
interface AddressRepositoryInterface{public function listForUser(string $userId):iterable;public function findForUser(string $userId,string $id):UserAddress;public function create(array $data):UserAddress;public function update(UserAddress $address,array $data):UserAddress;public function delete(UserAddress $address):void;public function clearDefault(string $userId,?string $except=null):void;}
