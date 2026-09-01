<?php
namespace App\Http\Controllers\Api\V1;
use App\Application\Catalog\Services\CategoryService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
class CategoryController extends Controller
{
    public function __construct(private readonly CategoryService $service) {}
    public function index(): JsonResponse { return response()->json(['status'=>'success','data'=>$this->service->list()]); }
    public function store(Request $request): JsonResponse { $data=$request->validate(['name'=>['required','string','max:255'],'slug'=>['nullable','string','max:255','unique:categories,slug'],'description'=>['nullable','string'],'image'=>['nullable','string'],'parent_id'=>['nullable','uuid','exists:categories,id'],'is_active'=>['nullable','boolean'],'sort_order'=>['nullable','integer','min:0']]); return response()->json(['status'=>'success','data'=>$this->service->create($data)],201); }
    public function update(Request $request,string $category): JsonResponse { $data=$request->validate(['name'=>['sometimes','string','max:255'],'slug'=>['sometimes','string','max:255','unique:categories,slug,'.$category],'description'=>['nullable','string'],'image'=>['nullable','string'],'parent_id'=>['nullable','uuid','exists:categories,id'],'is_active'=>['sometimes','boolean'],'sort_order'=>['sometimes','integer','min:0']]); return response()->json(['status'=>'success','data'=>$this->service->update($category,$data)]); }
    public function destroy(string $category): JsonResponse { $this->service->delete($category); return response()->json(['status'=>'success','message'=>'تم حذف التصنيف.']); }
}
