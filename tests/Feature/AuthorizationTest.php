<?php

namespace Tests\Feature;

use App\Domain\Models\Product;
use App\Domain\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_cannot_create_product(): void
    {
        $this->seed();
        $user = User::factory()->create()->assignRole('customer');
        $this->actingAs($user)->postJson('/api/v1/products', [])->assertForbidden();
    }

    public function test_admin_can_manage_product_and_roles(): void
    {
        $this->seed();
        $admin = User::factory()->create()->assignRole('admin');
        $this->actingAs($admin)->getJson('/api/v1/admin/roles')->assertOk();
        $this->actingAs($admin)->postJson('/api/v1/products', ['name' => 'Managed product', 'price' => 10, 'sku' => 'ADMIN-1', 'stock' => 2, 'category_id' => \App\Domain\Models\Category::factory()->create()->id])->assertCreated();
    }
}
