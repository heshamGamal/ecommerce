<?php
namespace Tests\Feature;
use App\Domain\Models\Category;
use App\Domain\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class CategoryFeatureTest extends TestCase
{
    use RefreshDatabase;
    public function test_public_can_list_categories_but_customer_cannot_manage_them(): void
    {
        $this->seed(); Category::factory()->create(['name'=>'Shoes']);
        $this->getJson('/api/v1/categories')->assertOk()->assertJsonPath('data.0.name','Shoes');
        $customer=User::factory()->create()->assignRole('customer');
        $this->actingAs($customer)->postJson('/api/v1/categories',['name'=>'Blocked'])->assertForbidden();
    }
    public function test_admin_can_create_update_and_cannot_delete_non_empty_category(): void
    {
        $this->seed(); $admin=User::factory()->create()->assignRole('admin');
        $id=$this->actingAs($admin)->postJson('/api/v1/categories',['name'=>'Electronics'])->assertCreated()->json('data.id');
        $this->actingAs($admin)->patchJson("/api/v1/categories/{$id}",['name'=>'Devices'])->assertOk()->assertJsonPath('data.name','Devices');
        \App\Domain\Models\Product::factory()->create(['category_id'=>$id]);
        $this->actingAs($admin)->deleteJson("/api/v1/categories/{$id}")->assertStatus(422);
    }
}
