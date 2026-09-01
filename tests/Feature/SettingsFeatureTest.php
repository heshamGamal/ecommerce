<?php
namespace Tests\Feature;
use App\Domain\Models\Setting;use App\Domain\Models\User;use Illuminate\Foundation\Testing\RefreshDatabase;use Tests\TestCase;
class SettingsFeatureTest extends TestCase
{
 use RefreshDatabase;
 public function test_public_settings_are_readable_and_locked_values_hidden():void{$this->seed();$r=$this->getJson('/api/v1/settings')->assertOk()->assertJsonFragment(['site.name'=>'My Store']);$this->assertArrayNotHasKey('site.currency',$r->json('data'));}
 public function test_admin_can_update_typed_setting_but_cannot_change_locked_one():void{$this->seed();$admin=User::factory()->create()->assignRole('admin');$this->actingAs($admin)->postJson('/api/v1/admin/settings',['key'=>'theme.primary_color','display_name'=>'Primary','value'=>'#ff0000','type'=>'color','group'=>'theme'])->assertOk();$this->assertSame('#ff0000',Setting::where('key','theme.primary_color')->value('value'));$this->actingAs($admin)->postJson('/api/v1/admin/settings',['key'=>'site.currency','display_name'=>'Currency','value'=>'USD','type'=>'text','group'=>'site'])->assertStatus(422);}
}
