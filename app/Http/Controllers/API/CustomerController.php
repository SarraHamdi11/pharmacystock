<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CustomerController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(['success' => true, 'data' => Customer::paginate()]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
        ]);
        $customer = Customer::create($validated);
        return response()->json(['success' => true, 'data' => $customer], 201);
    }

    public function show(Customer $customer): JsonResponse
    {
        return response()->json(['success' => true, 'data' => $customer->load('orders')]);
    }

    public function search(Request $request): JsonResponse
    {
        $term = $request->search;
        $customers = Customer::where('first_name', 'like', "%{$term}%")
            ->orWhere('last_name', 'like', "%{$term}%")
            ->get();
        return response()->json(['success' => true, 'data' => $customers]);
    }
}
