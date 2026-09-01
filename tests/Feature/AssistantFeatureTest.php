<?php

namespace Tests\Feature;

use App\Domain\Models\AssistantActivityLog;
use App\Domain\Models\AssistantProfile;
use App\Domain\Models\AssistantReview;
use App\Domain\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssistantFeatureTest extends TestCase
{
    use RefreshDatabase;

    private function manager(): User
    {
        $this->seed();
        return User::factory()->create()->assignRole('admin');
    }

    public function test_unauthenticated_requests_are_rejected(): void
    {
        $this->getJson('/api/v1/admin/assistants')->assertUnauthorized();
    }

    public function test_customer_cannot_access_assistant_management(): void
    {
        $this->seed();
        $customer = User::factory()->create()->assignRole('customer');
        $this->actingAs($customer)->getJson('/api/v1/admin/assistants')->assertForbidden()->assertJson(['status' => 'error']);
    }

    public function test_manager_can_create_and_list_only_own_assistants(): void
    {
        $manager = $this->manager();
        $assistant = User::factory()->create();
        $otherManager = User::factory()->create()->assignRole('admin');
        AssistantProfile::create(['user_id' => User::factory()->create()->id, 'manager_id' => $otherManager->id]);
        $response = $this->actingAs($manager)->postJson('/api/v1/admin/assistants', ['user_id' => $assistant->id, 'title' => 'Catalog Assistant'])->assertCreated();
        $profileId = $response->json('data.id');
        $response = $this->actingAs($manager)->getJson('/api/v1/admin/assistants')->assertOk();
        $this->assertCount(1, $response->json('data'));
        $this->assertSame($profileId, $response->json('data.0.id'));
        $this->assertSame('Catalog Assistant', $response->json('data.0.title'));
    }

    public function test_duplicate_assistant_and_invalid_payloads_are_rejected(): void
    {
        $manager = $this->manager();
        $assistant = User::factory()->create();
        $this->actingAs($manager)->postJson('/api/v1/admin/assistants', ['user_id' => $assistant->id])->assertCreated();
        $this->actingAs($manager)->postJson('/api/v1/admin/assistants', ['user_id' => $assistant->id])->assertStatus(422);
        $this->actingAs($manager)->postJson('/api/v1/admin/assistants', ['title' => 'Missing user'])->assertUnprocessable()->assertJsonValidationErrors('user_id');
    }

    public function test_activity_is_validated_persisted_and_returned_in_history(): void
    {
        $manager = $this->manager();
        $assistant = User::factory()->create();
        $profile = $this->actingAs($manager)->postJson('/api/v1/admin/assistants', ['user_id' => $assistant->id])->json('data.id');
        $this->actingAs($manager)->postJson("/api/v1/admin/assistants/{$profile}/activity", ['action' => 'updated_product', 'specialty' => 'shipping', 'outcome' => 'success', 'duration_seconds' => 120, 'entity_type' => 'product', 'metadata' => ['product_id' => 'abc']])->assertCreated()->assertJsonPath('data.action', 'updated_product');
        $this->assertDatabaseHas('assistant_activity_logs', ['assistant_id' => $profile, 'action' => 'updated_product']);
        $history = $this->actingAs($manager)->getJson("/api/v1/admin/assistants/{$profile}/history")->assertOk();
        $this->assertCount(1, $history->json('data.activities'));
        $this->assertCount(0, $history->json('data.reviews'));
        $this->actingAs($manager)->postJson("/api/v1/admin/assistants/{$profile}/specialties", ['specialty' => 'shipping', 'is_primary' => true])->assertOk()->assertJsonPath('data.specialties.0.specialty', 'shipping');
        $this->actingAs($manager)->getJson("/api/v1/admin/assistants/{$profile}/performance")->assertOk()->assertJsonPath('data.0.success_rate', 100)->assertJsonPath('data.0.average_duration_seconds', 120);
        $this->actingAs($manager)->postJson("/api/v1/admin/assistants/{$profile}/activity", ['action' => ''])->assertUnprocessable()->assertJsonValidationErrors('action');
    }

    public function test_reviews_update_average_and_validate_rating(): void
    {
        $manager = $this->manager();
        $assistant = User::factory()->create();
        $profile = $this->actingAs($manager)->postJson('/api/v1/admin/assistants', ['user_id' => $assistant->id])->json('data.id');
        $this->actingAs($manager)->postJson("/api/v1/admin/assistants/{$profile}/reviews", ['rating' => 5, 'comment' => 'Excellent'])->assertCreated();
        $this->actingAs($manager)->postJson("/api/v1/admin/assistants/{$profile}/reviews", ['rating' => 3])->assertCreated();
        $this->assertSame('4.00', AssistantProfile::findOrFail($profile)->average_rating);
        $this->assertDatabaseCount('assistant_reviews', 2);
        $this->actingAs($manager)->postJson("/api/v1/admin/assistants/{$profile}/reviews", ['rating' => 6])->assertUnprocessable()->assertJsonValidationErrors('rating');
    }

    public function test_manager_cannot_read_track_or_review_another_managers_assistant(): void
    {
        $manager = $this->manager();
        $other = User::factory()->create()->assignRole('admin');
        $assistant = User::factory()->create();
        $profile = AssistantProfile::create(['user_id' => $assistant->id, 'manager_id' => $other->id]);
        $this->actingAs($manager)->getJson('/api/v1/admin/assistants')->assertOk()->assertJsonCount(0, 'data');
        foreach (['activity' => ['action' => 'test'], 'reviews' => ['rating' => 5]] as $endpoint => $payload) {
            $this->actingAs($manager)->postJson("/api/v1/admin/assistants/{$profile->id}/{$endpoint}", $payload)->assertForbidden()->assertJson(['status' => 'error']);
        }
        $this->actingAs($manager)->getJson("/api/v1/admin/assistants/{$profile->id}/history")->assertForbidden();
    }
}
