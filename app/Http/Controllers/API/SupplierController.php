<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SupplierController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(['success' => true, 'data' => Supplier::all()]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
        ]);
        $supplier = Supplier::create($validated);
        return response()->json(['success' => true, 'data' => $supplier], 201);
    }

    public function show(Supplier $supplier): JsonResponse
    {
        return response()->json(['success' => true, 'data' => $supplier->load('products')]);
    }

    public function products(Supplier $supplier): JsonResponse
    {
        return response()->json(['success' => true, 'data' => $supplier->products]);
    }
}
