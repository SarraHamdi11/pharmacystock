<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Product;
use App\Models\Store;
use App\Http\Requests\StockRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class StockController extends Controller
{
   
    public function index(Request $request): View
    {
        $query = Stock::with(['product.category', 'store']);

        if ($request->filled('term')) {
            $term = $request->term;
            $query->whereHas('product', function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%");
            })->orWhereHas('store', function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%");
            });
        }

        return view('stocks.index', [
            'stocks' => $query->latest()->paginate(10)->withQueryString()
        ]);
    }

    
    public function create(): View
    {
        $products = Product::all();
        $stores = Store::all();
        return view('stocks.create', compact('products', 'stores'));
    }

    
    public function store(StockRequest $request): RedirectResponse
    {
        Stock::create($request->validated());

        return redirect()->route('stocks.index')
            ->with('success', 'Stock added successfully.');
    }

    public function show(Stock $stock): View
    {
        return view('stocks.show', compact('stock'));
    }

    
    public function edit(Stock $stock): View
    {
        $products = Product::all();
        $stores = Store::all();
        return view('stocks.edit', compact('stock', 'products', 'stores'));
    }

    
    public function update(StockRequest $request, Stock $stock): RedirectResponse
    {
        $stock->update($request->validated());

        return redirect()->route('stocks.index')
            ->with('success', 'Stock updated successfully.');
    }

    
    public function delete(Stock $stock): View
    {
        return view('stocks.delete', compact('stock')); // Peut-être renommer en 'remove'
    }

    
    public function destroy(Stock $stock): RedirectResponse
    {
        $stock->delete();

        return redirect()->route('stocks.index')
            ->with('success', 'Stock removed successfully.');
    }

    
    public function search(Request $request)
    {
        $term = $request->input('term');
        $stocks = Stock::whereHas('product', function ($query) use ($term) {
                $query->where('name', 'like', "%{$term}%");
            })
            ->orWhereHas('store', function ($query) use ($term) {
                $query->where('name', 'like', "%{$term}%");
            })
            ->paginate(10);

        return response()->json([
            'stocks' => $stocks->items(),
            'pagination' => [
                'total' => $stocks->total(),
                'per_page' => $stocks->perPage(),
                'current_page' => $stocks->currentPage(),
                'last_page' => $stocks->lastPage(),
                'from' => $stocks->firstItem(),
                'to' => $stocks->lastItem(),
                'links' => $stocks->linkCollection()->toArray()
            ]
        ]);
    }
}