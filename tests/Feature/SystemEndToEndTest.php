<?php
namespace Tests\Feature;
use App\Domain\Models\Product;
use App\Domain\Models\User;
use App\Domain\Models\ShippingProvider;
use App\Domain\Models\ShippingRate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
class SystemEndToEndTest extends TestCase
{
    use RefreshDatabase;
    public function test_customer_checkout_cod_and_admin_delivery_flow(): void
    {
        $this->seed();
        $admin = User::factory()->create()->assignRole('admin');
        $customer = User::factory()->create()->assignRole('customer');
        $product = Product::factory()->create(['price' => 100, 'stock' => 5]);
        $address = $this->actingAs($customer)->postJson('/api/v1/addresses', ['address_title'=>'Home','full_name'=>'Customer','phone'=>'01000000000','city'=>'Cairo','street_address'=>'Main street','is_default'=>true])->assertCreated()->json('data.id');
        $this->actingAs($customer)->postJson('/api/v1/cart/items', ['product_id'=>$product->id,'quantity'=>2])->assertCreated();
        $this->actingAs($customer)->getJson('/api/v1/cart')->assertOk()->assertJsonCount(1, 'data.items');
        $order = $this->actingAs($customer)->postJson('/api/v1/orders', ['shipping_full_name'=>'Customer','shipping_phone'=>'01000000000','shipping_city'=>'Cairo','shipping_address'=>'Main street','shipping_cost'=>15,'payment_method'=>'cod'])->assertCreated()->json('data');
        $this->assertSame(215.0, (float)$order['grand_total']);
        $payment = $this->actingAs($customer)->postJson("/api/v1/orders/{$order['id']}/payment/cod")->assertCreated()->json('data.id');
        $this->actingAs($customer)->postJson("/api/v1/payments/{$payment}/confirm", ['status'=>'successful','transaction_id'=>'COD-E2E-1'])->assertOk();
        $this->actingAs($admin)->patchJson("/api/v1/admin/orders/{$order['id']}/status", ['status'=>'processing'])->assertOk();
        $this->actingAs($admin)->patchJson("/api/v1/admin/orders/{$order['id']}/status", ['status'=>'shipped'])->assertOk();
        $this->actingAs($admin)->patchJson("/api/v1/admin/orders/{$order['id']}/status", ['status'=>'delivered'])->assertOk();
        $this->assertDatabaseHas('orders', ['id'=>$order['id'], 'status'=>'delivered', 'payment_status'=>'paid']);
        $this->assertDatabaseHas('products', ['id'=>$product->id, 'stock'=>3]);
        $this->assertNotNull($address);
    }
    public function test_shipping_calculation_and_public_settings_work(): void
    {
        $this->seed();
        $provider = ShippingProvider::create(['name'=>'Fast Delivery','code'=>'FAST-E2E','is_active'=>true]);
        $zone = DB::table('shipping_zones')->insertGetId(['name'=>'Cairo','code'=>'CAI-E2E','created_at'=>now(),'updated_at'=>now()]);
        ShippingRate::create(['shipping_provider_id'=>$provider->id,'shipping_zone_id'=>$zone,'base_cost'=>30,'per_kg_cost'=>10,'is_active'=>true]);
        $this->getJson("/api/v1/shipping/calculate?provider_id={$provider->id}&zone_id={$zone}&weight=2")->assertOk()->assertJsonPath('data.shipping_cost',50);
        $this->getJson('/api/v1/settings')->assertOk()->assertJsonFragment(['site.name'=>'My Store']);
    }
}
