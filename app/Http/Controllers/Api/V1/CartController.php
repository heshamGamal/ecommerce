<?php

namespace App\Http\Controllers\Api\V1;

use App\Application\Carts\Services\CartService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(private readonly CartService $service) {}
    public function show(Request $request): JsonResponse { return response()->json(['status' => 'success', 'data' => $this->service->getForUser($request->user()->id)]); }
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate(['product_id' => ['required', 'uuid', 'exists:products,id'], 'product_variant_id' => ['nullable', 'uuid', 'exists:product_variants,id'], 'quantity' => ['required', 'integer', 'min:1']]);
        return response()->json(['status' => 'success', 'data' => $this->service->add($request->user()->id, $data['product_id'], $data['product_variant_id'] ?? null, $data['quantity'])], 201);
    }
    public function update(Request $request, string $item): JsonResponse
    {
        $data = $request->validate(['quantity' => ['required', 'integer', 'min:1']]);
        return response()->json(['status' => 'success', 'data' => $this->service->update($this->service->getForUser($request->user()->id), $item, $data['quantity'])]);
    }
    public function destroy(Request $request, string $item): JsonResponse { $this->service->remove($this->service->getForUser($request->user()->id), $item); return response()->json(['status' => 'success']); }
    public function clear(Request $request): JsonResponse { $this->service->clear($this->service->getForUser($request->user()->id)); return response()->json(['status' => 'success']); }
}
