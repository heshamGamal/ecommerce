<?php

namespace Tests\Feature;

use App\Domain\Models\Product;
use App\Domain\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AddressesPaymentsFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_one_default_address_is_kept(): void
    {
        $this->seed();
        $user = User::factory()->create();
        $base = ['address_title' => 'Home', 'full_name' => 'Test', 'phone' => '0100', 'city' => 'Cairo', 'street_address' => 'Street', 'is_default' => true];
        $this->actingAs($user)->postJson('/api/v1/addresses', $base)->assertCreated();
        $this->actingAs($user)->postJson('/api/v1/addresses', array_merge($base, ['address_title' => 'Work']))->assertCreated();
        $this->assertDatabaseCount('user_addresses', 2);
        $this->assertSame(1, DB::table('user_addresses')->where('user_id', $user->id)->where('is_default', true)->count());
    }

    public function test_cod_payment_confirmation_updates_order(): void
    {
        $this->seed();
        $user = User::factory()->create();
        $product = Product::factory()->create(['price' => 20, 'stock' => 2]);
        $this->actingAs($user)->postJson('/api/v1/cart/items', ['product_id' => $product->id, 'quantity' => 1]);
        $order = $this->actingAs($user)->postJson('/api/v1/orders', ['shipping_full_name' => 'T', 'shipping_phone' => '0100', 'shipping_city' => 'Cairo', 'shipping_address' => 'Street'])->json('data.id');
        $payment = $this->actingAs($user)->postJson("/api/v1/orders/{$order}/payment/cod")->assertCreated()->json('data.id');
        $this->actingAs($user)->postJson("/api/v1/payments/{$payment}/confirm", ['status' => 'successful', 'transaction_id' => 'COD-1'])->assertOk();
        $this->assertDatabaseHas('orders', ['id' => $order, 'payment_status' => 'paid']);
        $this->assertDatabaseHas('payment_transactions', ['id' => $payment, 'status' => 'successful', 'transaction_id' => 'COD-1']);
    }
}
