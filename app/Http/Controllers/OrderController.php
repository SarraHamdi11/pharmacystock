<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Services\ActivityService;
use App\Http\Requests\OrderRequest;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }
    
    public function index(Request $request): View
    {
        $query = Order::with(['customer', 'products']);

        if ($request->filled('term')) {
            $term = $request->term;
            $query->where(function($q) use ($term) {
                $q->where('order_number', 'like', "%{$term}%")
                  ->orWhereHas('customer', function ($cq) use ($term) {
                      $cq->where('first_name', 'like', "%{$term}%")
                        ->orWhere('last_name', 'like', "%{$term}%");
                  });
            });
        }

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        return view('orders.index', [
            'orders' => $query->latest()->paginate(12)->withQueryString()
        ]);
    }

    
    public function create(): View
    {
        return view('orders.create');
    }

    
    public function store(OrderRequest $request): RedirectResponse
    {
        $this->orderService->createOrder($request->validated());

        return redirect()->route('orders.index')
            ->with('success', 'Order created successfully.');
    }

    
    public function show(Order $order): View
    {
        return view('orders.show', compact('order'));
    }

    
    public function edit(Order $order): View
    {
        return view('orders.edit', compact('order'));
    }

    
    public function update(OrderRequest $request, Order $order): RedirectResponse
    {
        $order->update($request->validated());
        ActivityService::log('updated', "Updated order: {$order->order_number}", $order);

        return redirect()->route('orders.index')
            ->with('success', 'Order updated successfully.');
    }

    public function delete(Order $order): View
    {
        return view('orders.delete', compact('order')); // Peut-être renommer en 'cancel'
    }

    
    public function destroy(Order $order): RedirectResponse
    {
        $orderNum = $order->order_number;
        $order->delete();
        ActivityService::log('deleted', "Cancelled order: {$orderNum}");

        return redirect()->route('orders.index')
            ->with('success', 'Order cancelled successfully.');
    }

   
    public function search(Request $request)
    {
        $term = $request->input('term');
        $orders = Order::where('order_number', 'like', "%{$term}%")
            ->orWhereHas('customer', function ($query) use ($term) {
                $query->where('first_name', 'like', "%{$term}%")
                      ->orWhere('last_name', 'like', "%{$term}%");
            })
            ->orWhere('order_date', 'like', "%{$term}%")
            ->paginate(10);

        return response()->json([
            'orders' => $orders->items(),
            'pagination' => [
                'total' => $orders->total(),
                'per_page' => $orders->perPage(),
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'from' => $orders->firstItem(),
                'to' => $orders->lastItem(),
                'links' => $orders->linkCollection()->toArray()
            ]
        ]);
    }
}