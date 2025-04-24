<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class StoreController extends Controller
{
    
    public function index(): View
    {
        return view('stores.index', [
            'stores' => Store::paginate(10)
        ]);
    }

    public function create(): View
    {
        return view('stores.create');
    }

    
    public function store(Request $request): RedirectResponse
    {
        Store::create($request->all()); // Vous devrez peut-être créer une StoreRequest pour la validation

        return redirect()->route('stores.index')
            ->with('success', 'Store created successfully.');
    }

    
    public function edit(Store $store): View
    {
        return view('stores.edit', compact('store'));
    }

    
    public function update(Request $request, Store $store): RedirectResponse
    {
        $store->update($request->all()); // Vous devrez peut-être créer une StoreRequest pour la validation

        return redirect()->route('stores.index')
            ->with('success', 'Store updated successfully.');
    }

    
    public function delete(Store $store): View
    {
        return view('stores.delete', compact('store'));
    }

   
    public function destroy(Store $store): RedirectResponse
    {
        $store->delete();

        return redirect()->route('stores.index')
            ->with('success', 'Store deleted successfully.');
    }

    
    public function search(Request $request)
    {
        $term = $request->input('term');
        $stores = Store::where('name', 'like', "%{$term}%")
            ->orWhere('location', 'like', "%{$term}%")
            ->paginate(10);

        return response()->json([
            'stores' => $stores->items(),
            'pagination' => [
                'total' => $stores->total(),
                'per_page' => $stores->perPage(),
                'current_page' => $stores->currentPage(),
                'last_page' => $stores->lastPage(),
                'from' => $stores->firstItem(),
                'to' => $stores->lastItem(),
                'links' => $stores->linkCollection()->toArray()
            ]
        ]);
    }
}