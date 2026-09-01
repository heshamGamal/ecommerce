<?php
namespace Tests\Feature;
use App\Domain\Models\AssistantProfile;
use App\Domain\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class AssistantTest extends TestCase
{
    use RefreshDatabase;
    public function test_manager_can_track_and_review_assistant(): void
    {
        $this->seed();
        $manager = User::factory()->create()->assignRole('admin');
        $assistant = User::factory()->create()->assignRole('assistant_manager');
        $profile = $this->actingAs($manager)->postJson('/api/v1/admin/assistants', ['user_id' => $assistant->id])->assertCreated()->json('data.id');
        $this->actingAs($manager)->postJson("/api/v1/admin/assistants/{$profile}/activity", ['action' => 'updated_product', 'specialty' => 'problem_solving', 'outcome' => 'success'])->assertCreated();
        $this->actingAs($manager)->postJson("/api/v1/admin/assistants/{$profile}/reviews", ['rating' => 5, 'comment' => 'Excellent'])->assertCreated();
        $this->assertSame('5.00', AssistantProfile::find($profile)->average_rating);
    }
}
