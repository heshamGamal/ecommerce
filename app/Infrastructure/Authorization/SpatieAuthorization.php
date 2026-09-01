<?php
namespace App\Infrastructure\Authorization;
use App\Application\Ports\AuthorizationInterface;
class SpatieAuthorization implements AuthorizationInterface{public function allows(object $user,string $permission):bool{return method_exists($user,'can')&&$user->can($permission);}}
