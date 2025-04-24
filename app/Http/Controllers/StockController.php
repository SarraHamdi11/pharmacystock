<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Product; // Probablement nécessaire pour lier le stock aux produits
use App\Models\Store;   // Probablement nécessaire pour lier le stock aux magasins
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class StockController extends Controller
{
   
    public function index(): View
    {
        return view('stocks.index', [
            'stocks' => Stock::paginate(10)
        ]);
    }

    
    public function create(): View
    {
        $products = Product::all();
        $stores = Store::all();
        return view('stocks.create', compact('products', 'stores'));
    }

    
    public function store(Request $request): RedirectResponse
    {
        Stock::create($request->all()); // Vous devrez peut-être valider que le produit et le magasin existent

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

    
    public function update(Request $request, Stock $stock): RedirectResponse
    {
        $stock->update($request->all()); // Vous devrez peut-être valider que le produit et le magasin existent

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