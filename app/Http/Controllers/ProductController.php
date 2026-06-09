<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Http\Requests\ProductRequest;
use App\Exports\ProductExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\RedirectResponse;
use App\Services\ProductService;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }
    
    public function index(Request $request): View
    {
        $products = $this->productService->getPaginatedProducts($request->all());

        return view('products.index', [
            'products' => $products,
            'categories' => Category::all(),
            'suppliers' => Supplier::all()
        ]);
    }

    
    public function create(): View
    {
        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('last_name')->get();
        return view('products.create', compact('categories', 'suppliers'));
    }

    
    public function store(ProductRequest $request): RedirectResponse
    {
        $this->productService->createProduct($request->validated());

        return redirect()->route('products.index')
            ->with('success', 'Medication created successfully!');
    }

    
    public function show(Product $product): View
    {
        return view('products.show', compact('product'));
    }

    
    public function edit(Product $product): View
    {
        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('last_name')->get();
        return view('products.edit', compact('product', 'categories', 'suppliers'));
    }

    
   
    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        $this->productService->updateProduct($product, $request->validated());

        return redirect()->route('products.index')
            ->with('success', 'Medication updated successfully!');
    }

    
    public function delete(Product $product): View
    {
        return view('products.delete', compact('product'));
    }

    
    public function destroy(Product $product): RedirectResponse
    {
        $this->productService->deleteProduct($product);

        return redirect()->route('products.index')
            ->with('success', 'Medication deleted successfully!');
    }
    
    public function search(Request $request)
    {
        $term = $request->input('term');
        
        if (empty($term)) {
            return redirect()->route('products.index');
        }
        
        $products = $this->productService->getPaginatedProducts(['term' => $term], 10);

        return view('products.search-results', compact('products', 'term'));
    }
    
    public function export()
    {
        return Excel::download(new ProductExport, 'products.xlsx');
    }
}