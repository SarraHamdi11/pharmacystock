<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => Category::withCount('products')->get()
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate(['name' => 'required|string|unique:categories,name']);
        $category = Category::create($validated);
        return response()->json(['success' => true, 'data' => $category], 201);
    }

    public function show(Category $category): JsonResponse
    {
        return response()->json(['success' => true, 'data' => $category->load('products')]);
    }

    public function update(Request $request, Category $category): JsonResponse
    {
        $validated = $request->validate(['name' => 'required|string|unique:categories,name,' . $category->id]);
        $category->update($validated);
        return response()->json(['success' => true, 'data' => $category]);
    }

    public function destroy(Category $category): JsonResponse
    {
        $category->delete();
        return response()->json(['success' => true, 'message' => 'Category deleted']);
    }

    public function products(Category $category): JsonResponse
    {
        return response()->json(['success' => true, 'data' => $category->products]);
    }
}
