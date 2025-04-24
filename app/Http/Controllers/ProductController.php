<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category; // Probablement nécessaire pour lier les produits aux catégories
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Exports\ProductExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\RedirectResponse;

class ProductController extends Controller
{
    
    public function index(): View
    {
        return view('products.index', [
            'products' => Product::paginate(10),
            'categories' => Category::all(),
            'suppliers' => \App\Models\Supplier::all()
        ]);
    }

    
    public function create(): View
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    
    public function store(Request $request): RedirectResponse
    {
        Product::create($request->all()); // Créez une ProductRequest pour la validation

        return redirect()->route('products.index')
            ->with('success', 'Produit créé avec succès.');
    }

    
    public function show(Product $product): View
    {
        return view('products.show', compact('product'));
    }

    
    public function edit(Product $product): View
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    
   
    public function update(Request $request, Product $product): RedirectResponse
    {
        $product->update($request->all()); // Créez une ProductRequest pour la validation

        return redirect()->route('products.index')
            ->with('success', 'Produit mis à jour avec succès.');
    }

    
    public function delete(Product $product): View
    {
        return view('products.delete', compact('product'));
    }

    
    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Produit supprimé avec succès.');
    }
    
    public function search(Request $request)
    {
        $term = $request->input('term');
        $products = Product::with(['category', 'supplier', 'stock'])
            ->where(function($query) use ($term) {
                $query->where('name', 'like', "%{$term}%")
                    ->orWhere('description', 'like', "%{$term}%");
            })
            ->paginate(10);

        return response()->json([
            'products' => $products->items(),
            'pagination' => [
                'total' => $products->total(),
                'per_page' => $products->perPage(),
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'from' => $products->firstItem(),
                'to' => $products->lastItem(),
                'links' => $products->linkCollection()->toArray()
            ]
        ]);
    }
    
    public function export()
    {
        return Excel::download(new ProductExport, 'products.xlsx');
    }
}