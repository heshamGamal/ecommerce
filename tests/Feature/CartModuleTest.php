<?php

namespace Tests\Feature;

use App\Domain\Models\Product;
use App\Domain\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_add_product_to_cart(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 3]);
        $response = $this->actingAs($user)->postJson('/api/v1/cart/items', ['product_id' => $product->id, 'quantity' => 2]);
        $response->assertCreated()->assertJsonPath('status', 'success');
        $this->assertDatabaseHas('cart_items', ['product_id' => $product->id, 'quantity' => 2]);
    }

    public function test_cart_rejects_quantity_above_stock(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 1]);
        $this->actingAs($user)->postJson('/api/v1/cart/items', ['product_id' => $product->id, 'quantity' => 2])->assertStatus(422);
        $this->assertDatabaseCount('cart_items', 0);
    }
}
