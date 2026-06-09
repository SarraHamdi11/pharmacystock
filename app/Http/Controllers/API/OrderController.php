<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(): JsonResponse
    {
        return response()->json(['success' => true, 'data' => Order::with('customer')->paginate()]);
    }

    public function store(Request $request): JsonResponse
    {
        // We can reuse the logic from the web controller or move it to OrderService
        try {
            $order = $this->orderService->createOrder($request->all());
            return response()->json(['success' => true, 'data' => $order], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function show(Order $order): JsonResponse
    {
        return response()->json(['success' => true, 'data' => $order->load(['customer', 'products'])]);
    }

    public function products(Order $order): JsonResponse
    {
        return response()->json(['success' => true, 'data' => $order->products]);
    }
}
