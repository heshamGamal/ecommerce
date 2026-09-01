<?php
namespace App\Application\Assistants\Services;
use App\Domain\Models\AssistantActivityLog;
use App\Domain\Models\AssistantProfile;
use App\Domain\Models\AssistantReview;
use App\Domain\Models\User;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
class AssistantService
{
    public function create(User $user, User $manager, array $data): AssistantProfile { return DB::transaction(fn () => AssistantProfile::create(['user_id'=>$user->id,'manager_id'=>$manager->id,'title'=>$data['title'] ?? 'Manager Assistant'])); }
    public function list(User $manager) { return AssistantProfile::with('user')->where('manager_id', $manager->id)->latest()->get(); }
    public function log(AssistantProfile $assistant, User $manager, array $data): AssistantActivityLog { if ($assistant->manager_id !== $manager->id) throw new InvalidArgumentException('المساعد غير تابع لهذا المدير.'); return $assistant->activities()->create(['manager_id'=>$manager->id,'action'=>$data['action'],'specialty'=>$data['specialty'],'outcome'=>$data['outcome'],'duration_seconds'=>$data['duration_seconds'] ?? null,'completed_at'=>$data['completed_at'] ?? now(),'entity_type'=>$data['entity_type'] ?? null,'entity_id'=>$data['entity_id'] ?? null,'metadata'=>$data['metadata'] ?? null]); }
    public function review(AssistantProfile $assistant, User $manager, array $data): AssistantReview { return DB::transaction(function () use ($assistant,$manager,$data): AssistantReview { if ($assistant->manager_id !== $manager->id) throw new InvalidArgumentException('المساعد غير تابع لهذا المدير.'); $review=$assistant->reviews()->create(['manager_id'=>$manager->id,'rating'=>$data['rating'],'comment'=>$data['comment'] ?? null,'reviewed_for'=>$data['reviewed_for'] ?? now()->toDateString()]); $assistant->update(['average_rating'=>round((float)$assistant->reviews()->avg('rating'),2)]); return $review; }); }
    public function performance(AssistantProfile $assistant, User $manager): array { if ($assistant->manager_id !== $manager->id) throw new InvalidArgumentException('المساعد غير تابع لهذا المدير.'); $events=$assistant->activities()->select('specialty','outcome','duration_seconds')->get(); return $events->groupBy('specialty')->map(function ($rows, $specialty): array { $total=$rows->count(); $success=$rows->where('outcome','success')->count(); return ['specialty'=>$specialty,'total_tasks'=>$total,'successful_tasks'=>$success,'failed_tasks'=>$total-$success,'success_rate'=>round($success/$total*100,2),'average_duration_seconds'=>round((float)$rows->whereNotNull('duration_seconds')->avg('duration_seconds'),2)]; })->values()->all(); }
    public function assignSpecialty(AssistantProfile $assistant, User $manager, array $data): AssistantProfile { if ($assistant->manager_id !== $manager->id) throw new InvalidArgumentException('المساعد غير تابع لهذا المدير.'); $assistant->specialties()->updateOrCreate(['specialty'=>$data['specialty']], ['is_primary'=>$data['is_primary'] ?? false]); return $assistant->load('specialties'); }
}
