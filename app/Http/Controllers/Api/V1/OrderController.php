<?php

namespace App\Http\Controllers\Api\V1;

use App\Application\Orders\Services\OrderService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Domain\Models\Order;

class OrderController extends Controller
{
    public function __construct(private readonly OrderService $service) {}
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'coupon_code' => ['nullable', 'string', 'max:100'], 'shipping_cost' => ['nullable', 'numeric', 'min:0'], 'payment_method' => ['nullable', 'in:cod,vodafone_cash,instapay,credit_card'],
            'shipping_full_name' => ['required', 'string', 'max:255'], 'shipping_phone' => ['required', 'string', 'max:50'], 'shipping_city' => ['required', 'string', 'max:100'], 'shipping_address' => ['required', 'string', 'max:1000'], 'notes' => ['nullable', 'string', 'max:2000'],
        ]);
        return response()->json(['status' => 'success', 'data' => $this->service->createForUser($request->user()->id, $data)], 201);
    }

    public function index(Request $request): JsonResponse
    {
        return response()->json(['status' => 'success', 'data' => $this->service->listForUser($request->user()->id)]);
    }

    public function adminIndex(): JsonResponse
    {
        return response()->json(['status' => 'success', 'data' => $this->service->listAll()]);
    }

    public function show(Request $request, string $order): JsonResponse
    {
        return response()->json(['status' => 'success', 'data' => $this->service->getForUser($request->user()->id, $order)]);
    }

    public function cancel(Request $request, string $order): JsonResponse
    {
        $model = $this->service->getForUser($request->user()->id, $order);
        return response()->json(['status' => 'success', 'data' => $this->service->cancel($model)]);
    }

    public function updateStatus(Request $request, string $order): JsonResponse
    {
        $data = $request->validate(['status' => ['required', 'in:processing,shipped,delivered,cancelled']]);
        return response()->json(['status' => 'success', 'data' => $this->service->updateStatus($order, $data['status'])]);
    }
}
