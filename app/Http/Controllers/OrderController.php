<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class OrderController extends Controller
{
    
    public function index(): View
    {
        return view('orders.index', [
            'orders' => Order::paginate(10)
        ]);
    }

    
    public function create(): View
    {
        return view('orders.create');
    }

    
    public function store(Request $request): RedirectResponse
    {
        Order::create($request->all()); // Vous devrez probablement gérer les relations avec les produits, etc.

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

    
    public function update(Request $request, Order $order): RedirectResponse
    {
        $order->update($request->all()); // Vous devrez probablement gérer les relations avec les produits, etc.

        return redirect()->route('orders.index')
            ->with('success', 'Order updated successfully.');
    }

    public function delete(Order $order): View
    {
        return view('orders.delete', compact('order')); // Peut-être renommer en 'cancel'
    }

    
    public function destroy(Order $order): RedirectResponse
    {
        $order->delete(); // Ou peut-être mettre à jour un statut 'cancelled'

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