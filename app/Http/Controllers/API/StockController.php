<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(['success' => true, 'data' => Stock::with('product')->paginate()]);
    }

    public function adjustStock(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'store_id' => 'required|exists:stores,id',
            'quantity' => 'required|integer',
        ]);

        $stock = Stock::updateOrCreate(
            ['product_id' => $validated['product_id'], 'store_id' => $validated['store_id']],
            ['quantity_stock' => DB::raw('quantity_stock + ' . $validated['quantity'])]
        );

        return response()->json(['success' => true, 'data' => $stock->fresh()]);
    }

    public function lowStock(): JsonResponse
    {
        $products = Product::lowStock()->get();
        return response()->json(['success' => true, 'data' => $products]);
    }

    public function expiryAlerts(): JsonResponse
    {
        $products = Product::expiringSoon()->get();
        return response()->json(['success' => true, 'data' => $products]);
    }
}
