<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function create()
    {
        $categories = Category::all();
        return view('categories.create', compact('categories'));
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function index()
    {
        $categories = Category::paginate(10);
        return view('categories.index', compact('categories'));
    }

    public function productsByCategory()
    {
        $categories = Category::all();
        $products = collect();
        return view('products.by-category', compact('categories', 'products'));
    }

    public function getProductsByCategory(Category $category)
    {
        $categories = Category::all();
        $products = $category->products;
        return view('products.by-category', compact('categories', 'products'));
    }

    public function show(Category $category)
    {
        return view('categories.show', compact('category'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
    
        Category::create($validatedData);
    
        return redirect()->route('categories.index')
                         ->with('success', 'Category created successfully.');
    }

    public function update(Request $request, Category $category)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
    
        $category->update($validatedData);
    
        return redirect()->route('categories.index')
                         ->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        try {
            $category->delete();
            return redirect()->route('categories.index')
                             ->with('success', 'Catégorie supprimée avec succès.');
        } catch (\Exception $e) {
            return redirect()->route('categories.index')
                             ->with('error', 'Impossible de supprimer cette catégorie.');
        }
    }
}