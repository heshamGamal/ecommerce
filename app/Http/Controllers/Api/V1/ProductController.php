<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Application\Products\Services\ProductService;
use App\Application\Products\DTOs\ProductData;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductService $productService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $products = $this->productService->getCatalog($request->all(), (int) $request->get('per_page', 15));
        return response()->json(['status' => 'success', 'data' => $products]);
    }

    public function show(string $id): JsonResponse
    {
        return response()->json(['status' => 'success', 'data' => $this->productService->getDetails($id)]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'sale_price'  => 'nullable|numeric|min:0',
            'sku'         => 'required|max:255',
            'stock'       => 'required|integer|min:0',
            'category_id' => 'required|uuid|exists:categories,id',
            'variants'    => 'nullable|array',
        ]);

        $dto = ProductData::fromArray($validated);
        $product = $this->productService->createProduct($dto);

        return response()->json(['status' => 'success', 'data' => $product], 201);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $validated = $request->validate([
            'name'        => 'sometimes|string|max:255',
            'price'       => 'sometimes|numeric|min:0',
            'sale_price'  => 'nullable|numeric|min:0',
            'sku'         => 'sometimes|max:255',
            'stock'       => 'sometimes|integer|min:0',
            'category_id' => 'sometimes|uuid|exists:categories,id',
            'is_active'   => 'sometimes|boolean',
            'variants'    => 'nullable|array',
        ]);

        $existingProduct = $this->productService->getDetails($id);
        $existingDto = ProductData::fromArray($existingProduct->toArray());
        
        $dto = ProductData::fromArray($validated, $existingDto);
        $this->productService->updateProduct($id, $dto);

        return response()->json(['status' => 'success', 'message' => 'تم تعديل بيانات المنتج بنجاح']);
    }

    public function destroy(string $id): JsonResponse
    {
        $this->productService->deleteProduct($id);
        return response()->json(['status' => 'success', 'message' => 'تم حذف المنتج بنجاح']);
    }

    public function adjustStock(Request $request, string $id): JsonResponse
    {
        $request->validate(['change' => 'required|integer']);
        $this->productService->manualStockAdjustment($id, (int) $request->change);
        return response()->json(['status' => 'success', 'message' => 'تم تعديل المخزون بنجاح']);
    }

    public function flashSale(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'sale_price' => 'required|numeric|min:0',
            'end_date'   => 'required|date|after:now'
        ]);
        $this->productService->applyFlashSale($id, (float) $request->sale_price, $request->end_date);
        return response()->json(['status' => 'success', 'message' => 'تم تفعيل العرض المؤقت بنجاح']);
    }

    public function bulkAction(Request $request): JsonResponse
    {
        $request->validate([
            'ids'    => 'required|array',
            'ids.*'  => 'uuid',
            'action' => 'required|in:activate,deactivate,delete'
        ]);

        match ($request->action) {
            'activate'   => $this->productService->bulkChangeStatus($request->ids, true),
            'deactivate' => $this->productService->bulkChangeStatus($request->ids, false),
            'delete'     => $this->productService->bulkRemove($request->ids),
        };

        return response()->json(['status' => 'success', 'message' => 'تم تنفيذ الإجراء المجمع بنجاح']);
    }
}
?> 
