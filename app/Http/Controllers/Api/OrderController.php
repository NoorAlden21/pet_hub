<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(protected OrderService $orderService)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $orders = $this->orderService->indexFor($request->user());

        return response()->json([
            'data'    => OrderResource::collection($orders),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $order = $this->orderService->placeOrderFromCart($request->user());

        return response()->json([
            'message' => __('messages.order.created'),
            'data'    => new OrderResource($order),
        ], 201);
    }

    /**
     * GET /orders/{order}
     */
    public function show(Order $order): JsonResponse
    {
        $order = $this->orderService->show($order);

        return response()->json([
            'data'    => new OrderResource($order),
        ]);
    }

    public function updateStatus(Request $request, Order $order): JsonResponse
    {
        $data = $request->validate([
            'status' => ['required', 'string'],
            'payment_status' => ['nullable', 'string', 'in:paid,unpaid']
        ]);

        $order = $this->orderService->updateStatus($order, $data['status'], $data['payment_status'] ?? null);

        return response()->json([
            'message' => __('messages.order.status_updated'),
            'data'    => new OrderResource($order),
        ]);
    }

    public function destroy(Order $order): JsonResponse
    {
        $this->orderService->delete($order);

        return response()->json([
            'message' => __('messages.order.deleted'),
        ]);
    }

    public function cancel(Order $order): JsonResponse
    {
        $order = $this->orderService->cancel($order);

        return response()->json([
            'success' => true,
            'message' => __('messages.order.cancelled'),
            'data'    => new OrderResource($order),
        ]);
    }
}
