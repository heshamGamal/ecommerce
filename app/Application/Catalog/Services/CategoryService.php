<?php
namespace App\Application\Catalog\Services;
use App\Domain\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\ModelNotFoundException;
class CategoryService
{
    public function list() { return Category::with('children')->whereNull('parent_id')->orderBy('sort_order')->get(); }
    public function create(array $data): Category { return DB::transaction(fn () => Category::create(['name'=>$data['name'],'slug'=>$data['slug'] ?? Str::slug($data['name']),'description'=>$data['description'] ?? null,'image'=>$data['image'] ?? null,'parent_id'=>$data['parent_id'] ?? null,'is_active'=>$data['is_active'] ?? true,'sort_order'=>$data['sort_order'] ?? 0])); }
    public function update(string $id, array $data): Category { return DB::transaction(function () use ($id,$data): Category { $category=Category::find($id); if(!$category) throw new ModelNotFoundException('التصنيف غير موجود.'); $category->update(array_filter(['name'=>$data['name'] ?? null,'slug'=>$data['slug'] ?? null,'description'=>$data['description'] ?? null,'image'=>$data['image'] ?? null,'parent_id'=>$data['parent_id'] ?? null,'is_active'=>$data['is_active'] ?? null,'sort_order'=>$data['sort_order'] ?? null],fn($v)=>$v!==null)); return $category->fresh(); }); }
    public function delete(string $id): void { DB::transaction(function () use ($id): void { $category=Category::findOrFail($id); if($category->products()->exists() || $category->children()->exists()) throw new \InvalidArgumentException('لا يمكن حذف تصنيف يحتوي منتجات أو تصنيفات فرعية.'); $category->delete(); }); }
}
