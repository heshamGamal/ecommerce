<?php
namespace App\Http\Controllers\Api\V1;
use App\Application\Assistants\Services\AssistantService;
use App\Http\Controllers\Controller;
use App\Domain\Models\AssistantProfile;
use App\Domain\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
class AssistantController extends Controller
{
    public function __construct(private readonly AssistantService $service) {}
    public function index(Request $request): JsonResponse { return response()->json(['status'=>'success','data'=>$this->service->list($request->user())]); }
    public function store(Request $request): JsonResponse { $data=$request->validate(['user_id'=>['required','uuid','exists:users,id'],'title'=>['nullable','string','max:150']]); if (AssistantProfile::where('user_id',$data['user_id'])->exists()) return response()->json(['status'=>'error','message'=>'المستخدم مسجل كمساعد مسبقًا.'],422); return response()->json(['status'=>'success','data'=>$this->service->create(User::findOrFail($data['user_id']),$request->user(),$data)],201); }
    public function log(Request $request, string $assistant): JsonResponse { $profile=AssistantProfile::findOrFail($assistant); if (!$this->owns($profile,$request)) return $this->forbidden(); $data=$request->validate(['action'=>['required','string','max:150'],'specialty'=>['required','in:problem_solving,shipping,order_confirmation'],'outcome'=>['required','in:success,failure'],'duration_seconds'=>['nullable','integer','min:0'],'completed_at'=>['nullable','date'],'entity_type'=>['nullable','string','max:100'],'entity_id'=>['nullable','uuid'],'metadata'=>['nullable','array']]); return response()->json(['status'=>'success','data'=>$this->service->log($profile,$request->user(),$data)],201); }
    public function review(Request $request, string $assistant): JsonResponse { $profile=AssistantProfile::findOrFail($assistant); if (!$this->owns($profile,$request)) return $this->forbidden(); $data=$request->validate(['rating'=>['required','integer','between:1,5'],'comment'=>['nullable','string','max:2000'],'reviewed_for'=>['nullable','date']]); return response()->json(['status'=>'success','data'=>$this->service->review($profile,$request->user(),$data)],201); }
    public function history(Request $request, string $assistant): JsonResponse { $profile=AssistantProfile::findOrFail($assistant); if (!$this->owns($profile,$request)) return $this->forbidden(); return response()->json(['status'=>'success','data'=>$profile->load(['activities','reviews'])]); }
    public function performance(Request $request, string $assistant): JsonResponse { $profile=AssistantProfile::findOrFail($assistant); if (!$this->owns($profile,$request)) return $this->forbidden(); return response()->json(['status'=>'success','data'=>$this->service->performance($profile,$request->user())]); }
    public function specialty(Request $request, string $assistant): JsonResponse { $profile=AssistantProfile::findOrFail($assistant); if (!$this->owns($profile,$request)) return $this->forbidden(); $data=$request->validate(['specialty'=>['required','in:problem_solving,shipping,order_confirmation'],'is_primary'=>['nullable','boolean']]); return response()->json(['status'=>'success','data'=>$this->service->assignSpecialty($profile,$request->user(),$data)]); }
    private function owns(AssistantProfile $profile, Request $request): bool { return (string) $profile->manager_id === (string) $request->user()->id; }
    private function forbidden(): JsonResponse { return response()->json(['status'=>'error','message'=>'ليس لديك صلاحية لهذا المساعد.'],403); }
}
