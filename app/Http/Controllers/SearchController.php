<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function global(Request $request): JsonResponse
    {
        $query = trim($request->input('q', ''));

        if (strlen($query) < 2) {
            return response()->json(['results' => []]);
        }

        $products = Product::where('name', 'like', "%{$query}%")
            ->orWhere('generic_name', 'like', "%{$query}%")
            ->limit(5)
            ->get(['id', 'name'])
            ->map(fn ($p) => [
                'type' => 'medication',
                'label' => $p->name,
                'url' => route('products.edit', $p->id),
                'icon' => 'fa-pills',
            ]);

        $customers = Customer::where('first_name', 'like', "%{$query}%")
            ->orWhere('last_name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->limit(5)
            ->get(['id', 'first_name', 'last_name'])
            ->map(fn ($c) => [
                'type' => 'patient',
                'label' => $c->first_name.' '.$c->last_name,
                'url' => route('customers.edit', $c->id),
                'icon' => 'fa-user',
            ]);

        $orders = Order::with('customer')
            ->where('id', 'like', "%{$query}%")
            ->limit(3)
            ->get()
            ->map(fn ($o) => [
                'type' => 'order',
                'label' => 'Order #'.$o->id.($o->customer ? ' — '.$o->customer->first_name : ''),
                'url' => route('orders.show', $o->id),
                'icon' => 'fa-shopping-cart',
            ]);

        return response()->json([
            'results' => $products->concat($customers)->concat($orders)->values(),
        ]);
    }
}
