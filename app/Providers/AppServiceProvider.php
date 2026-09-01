<?php

namespace App\Providers;

use App\Domain\Contracts\Repositories\ProductRepositoryInterface;
use App\Infrastructure\Repositories\Eloquent\ProductRepository;
use App\Domain\Contracts\Repositories\RoleRepositoryInterface;
use App\Domain\Contracts\Repositories\PermissionRepositoryInterface;
use App\Infrastructure\Repositories\Eloquent\RoleRepository;
use App\Infrastructure\Repositories\Eloquent\PermissionRepository;
use App\Domain\Contracts\Repositories\SettingRepositoryInterface;
use App\Infrastructure\Repositories\Eloquent\SettingRepository;
use App\Domain\Contracts\TransactionManagerInterface;
use App\Infrastructure\Database\DatabaseTransactionManager;
use App\Application\Ports\PaymentGatewayInterface;
use App\Infrastructure\Payments\CodPaymentGateway;
use App\Application\Ports\ShippingProviderInterface;
use App\Infrastructure\Shipping\LocalShippingProvider;
use App\Application\Ports\AuthorizationInterface;
use App\Infrastructure\Authorization\SpatieAuthorization;
use App\Domain\Contracts\Repositories\AddressRepositoryInterface;
use App\Infrastructure\Repositories\Eloquent\AddressRepository;
use App\Domain\Contracts\Repositories\PaymentRepositoryInterface;
use App\Infrastructure\Repositories\Eloquent\PaymentRepository;
use App\Domain\Contracts\Repositories\CartRepositoryInterface;
use App\Infrastructure\Repositories\Eloquent\CartRepository;
use App\Domain\Contracts\Repositories\OrderRepositoryInterface;
use App\Infrastructure\Repositories\Eloquent\OrderRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(PermissionRepositoryInterface::class, PermissionRepository::class);
        $this->app->bind(SettingRepositoryInterface::class, SettingRepository::class);
        $this->app->bind(TransactionManagerInterface::class, DatabaseTransactionManager::class);
        $this->app->bind(PaymentGatewayInterface::class, CodPaymentGateway::class);
        $this->app->bind(ShippingProviderInterface::class, LocalShippingProvider::class);
        $this->app->bind(AuthorizationInterface::class, SpatieAuthorization::class);
        $this->app->bind(AddressRepositoryInterface::class, AddressRepository::class);
        $this->app->bind(PaymentRepositoryInterface::class, PaymentRepository::class);
        $this->app->bind(CartRepositoryInterface::class, CartRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
    }

    public function boot(): void {}
}
