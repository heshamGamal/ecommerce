<?php
namespace App\Application\Ports;
interface AuthorizationInterface{public function allows(object $user,string $permission):bool;}
