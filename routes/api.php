<?php

use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CartController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\AdminRoleController;
use App\Http\Controllers\Api\V1\AssistantController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\AddressController;
use App\Http\Controllers\Api\V1\PaymentController;
use App\Http\Controllers\Api\V1\ShippingController;
use App\Http\Controllers\Api\V1\SettlementController;
use App\Http\Controllers\Api\V1\SettingController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::post('auth/register', [AuthController::class, 'register']);
    Route::post('auth/login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function (): void {
        Route::get('auth/me', [AuthController::class, 'me']);
        Route::post('auth/logout', [AuthController::class, 'logout']);
        Route::get('cart', [CartController::class, 'show']);
        Route::post('cart/items', [CartController::class, 'store']);
        Route::patch('cart/items/{item}', [CartController::class, 'update']);
        Route::delete('cart/items/{item}', [CartController::class, 'destroy']);
        Route::delete('cart', [CartController::class, 'clear']);
        Route::apiResource('addresses', AddressController::class)->only(['index','store','update','destroy']);
        Route::post('orders', [OrderController::class, 'store']);
        Route::get('orders', [OrderController::class, 'index']);
        Route::get('orders/{order}', [OrderController::class, 'show']);
        Route::post('orders/{order}/cancel', [OrderController::class, 'cancel']);
        Route::post('orders/{order}/payment/cod', [PaymentController::class, 'cod']);
        Route::post('payments/{payment}/confirm', [PaymentController::class, 'confirm']);
        Route::middleware('permission:shipping.settle')->prefix('admin/shipping')->group(function (): void {
            Route::get('settlements/pending', [SettlementController::class, 'pending']);
            Route::post('shipments/{shipment}/settlement', [SettlementController::class, 'store']);
            Route::post('settlements/{settlement}/settle', [SettlementController::class, 'settle']);
        });
        Route::middleware('permission:orders.view_all')->get('admin/orders', [OrderController::class, 'adminIndex']);
        Route::middleware('permission:orders.update_status')->patch('admin/orders/{order}/status', [OrderController::class, 'updateStatus']);
        Route::middleware('permission:users.manage_roles')->prefix('admin')->group(function (): void {
            Route::get('roles', [AdminRoleController::class, 'roles']);
            Route::post('users/{user}/roles', [AdminRoleController::class, 'assign']);
            Route::delete('users/{user}/roles', [AdminRoleController::class, 'revoke']);
        });
        Route::middleware('permission:assistants.track')->prefix('admin/assistants')->group(function (): void {
            Route::get('/', [AssistantController::class, 'index']);
            Route::post('{assistant}/activity', [AssistantController::class, 'log']);
            Route::get('{assistant}/history', [AssistantController::class, 'history']);
            Route::get('{assistant}/performance', [AssistantController::class, 'performance']);
            Route::post('{assistant}/specialties', [AssistantController::class, 'specialty']);
        });
        Route::middleware('permission:assistants.manage')->post('admin/assistants', [AssistantController::class, 'store']);
        Route::middleware('permission:assistants.review')->post('admin/assistants/{assistant}/reviews', [AssistantController::class, 'review']);
    });
    Route::get('products', [ProductController::class, 'index']);
    Route::get('products/{id}', [ProductController::class, 'show']);
    Route::get('categories', [CategoryController::class, 'index']);
    Route::get('shipping/calculate', [ShippingController::class, 'calculate']);
    Route::get('settings', [SettingController::class, 'public']);
    Route::middleware(['auth:sanctum', 'permission:settings.manage'])->prefix('admin/settings')->group(function (): void {
        Route::get('/', [SettingController::class, 'admin']);
        Route::post('/', [SettingController::class, 'store']);
    });
    Route::middleware(['auth:sanctum', 'permission:categories.manage'])->group(function (): void {
        Route::post('categories', [CategoryController::class, 'store']);
        Route::match(['put', 'patch'], 'categories/{category}', [CategoryController::class, 'update']);
        Route::delete('categories/{category}', [CategoryController::class, 'destroy']);
    });
    Route::middleware(['auth:sanctum', 'permission:products.create'])->post('products', [ProductController::class, 'store']);
    Route::middleware(['auth:sanctum', 'permission:products.update'])->match(['put', 'patch'], 'products/{id}', [ProductController::class, 'update']);
    Route::middleware(['auth:sanctum', 'permission:products.delete'])->delete('products/{id}', [ProductController::class, 'destroy']);
    Route::middleware(['auth:sanctum', 'permission:products.manage_stock'])->group(function (): void {
        Route::post('products/{id}/adjust-stock', [ProductController::class, 'adjustStock']);
        Route::post('products/{id}/flash-sale', [ProductController::class, 'flashSale']);
    });
    Route::middleware(['auth:sanctum', 'permission:products.delete'])->post('products/bulk-action', [ProductController::class, 'bulkAction']);
});
