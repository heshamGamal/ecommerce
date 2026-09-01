<?php

namespace Tests\Feature;

use App\Domain\Models\Cart;
use App\Domain\Models\Product;
use App\Domain\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_decrements_stock_and_clears_cart(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['price' => 100, 'stock' => 5]);
        $this->actingAs($user)->postJson('/api/v1/cart/items', ['product_id' => $product->id, 'quantity' => 2])->assertCreated();
        $response = $this->actingAs($user)->postJson('/api/v1/orders', ['shipping_full_name' => 'Test User', 'shipping_phone' => '01000000000', 'shipping_city' => 'Cairo', 'shipping_address' => 'Test address']);
        $response->assertCreated()->assertJsonPath('status', 'success');
        $this->assertSame(3, $product->fresh()->stock);
        $this->assertDatabaseCount('cart_items', 0);
        $this->assertDatabaseHas('orders', ['user_id' => $user->id, 'grand_total' => 200]);
    }

    public function test_pending_order_can_be_cancelled_and_stock_is_restored(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['price' => 100, 'stock' => 5]);
        $this->actingAs($user)->postJson('/api/v1/cart/items', ['product_id' => $product->id, 'quantity' => 2]);
        $order = $this->actingAs($user)->postJson('/api/v1/orders', ['shipping_full_name' => 'Test', 'shipping_phone' => '0100', 'shipping_city' => 'Cairo', 'shipping_address' => 'Address'])->json('data.id');
        $this->actingAs($user)->postJson("/api/v1/orders/{$order}/cancel")->assertOk()->assertJsonPath('data.status', 'cancelled');
        $this->assertSame(5, $product->fresh()->stock);
    }
}
