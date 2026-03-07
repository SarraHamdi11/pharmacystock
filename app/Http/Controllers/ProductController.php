<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category; // Probablement nécessaire pour lier les produits aux catégories
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Http\Requests\ProductRequest;
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
        $suppliers = Supplier::all();
        return view('products.create', compact('categories', 'suppliers'));
    }

    
    public function store(Request $request): RedirectResponse
    {
        // Temporarily remove validation to test 419 error
        $validated = $request->all();
        
        // Handle checkbox
        $validated['track_expiry'] = $request->has('track_expiry') ? 1 : 0;

        // Create the product
        Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', 'Medication created successfully!');
    }

    
    public function show(Product $product): View
    {
        return view('products.show', compact('product'));
    }

    
    public function edit(Product $product): View
    {
        $categories = Category::all();
        $suppliers = Supplier::all();
        return view('products.edit', compact('product', 'categories', 'suppliers'));
    }

    
   
    public function update(Request $request, Product $product): RedirectResponse
    {
        // Simple validation without ProductRequest to avoid 419 errors
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'generic_name' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'code_bar' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'min_stock' => 'required|integer|min:0',
            'track_expiry' => 'boolean',
            'description' => 'nullable|string',
            'picture' => 'nullable|image|max:2048'
        ]);
        
        // Handle checkbox
        $validated['track_expiry'] = $request->has('track_expiry') ? 1 : 0;
        
        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', 'Medication updated successfully!');
    }

    
    public function delete(Product $product): View
    {
        return view('products.delete', compact('product'));
    }

    
    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Medication deleted successfully!');
    }
    
    public function search(Request $request)
    {
        $term = $request->input('term');
        
        // If search term is empty, redirect to index
        if (empty($term) || trim($term) === '') {
            return redirect()->route('products.index');
        }
        
        // Enhanced search by name, category, and supplier
        $products = Product::with(['category', 'supplier', 'stocks'])
            ->where(function($query) use ($term) {
                $query->where('name', 'like', "%{$term}%")
                      ->orWhere('description', 'like', "%{$term}%")
                      ->orWhereHas('category', function($q) use ($term) {
                          $q->where('name', 'like', "%{$term}%");
                      })
                      ->orWhereHas('supplier', function($q) use ($term) {
                          $q->where('first_name', 'like', "%{$term}%")
                            ->orWhere('last_name', 'like', "%{$term}%");
                      });
            })
            ->limit(10)
            ->get();

        // Return search results view
        return view('products.search-results', compact('products', 'term'));
    }
    
    public function export()
    {
        return Excel::download(new ProductExport, 'products.xlsx');
    }
}