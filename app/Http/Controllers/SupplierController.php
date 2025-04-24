<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SupplierController extends Controller
{
    /**
     * Display a listing of the suppliers.
     */
    public function index(): View
    {
        return view('suppliers.index', [
            'suppliers' => Supplier::paginate(10)
        ]);
    }

    /**
     * Show the form for creating a new supplier.
     */
    public function create(): View
    {
        return view('suppliers.create');
    }

    
    public function store(Request $request): RedirectResponse
    {
        Supplier::create($request->all()); 

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier created successfully.');
    }

    
    public function edit(Supplier $supplier): View
    {
        return view('suppliers.edit', compact('supplier'));
    }

    
    public function update(Request $request, Supplier $supplier): RedirectResponse
    {
        $supplier->update($request->all()); // Vous devrez peut-être créer une SupplierRequest pour la validation

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier updated successfully.');
    }

    
    public function delete(Supplier $supplier): View
    {
        return view('suppliers.delete', compact('supplier'));
    }

    
    public function destroy(Supplier $supplier): RedirectResponse
    {
        $supplier->delete();

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier deleted successfully.');
    }

    
    public function search(Request $request)
    {
        $term = $request->input('term');
        $suppliers = Supplier::where('name', 'like', "%{$term}%")
            ->orWhere('contact_person', 'like', "%{$term}%") // Exemple d'un autre champ
            ->paginate(10);

        return response()->json([
            'suppliers' => $suppliers->items(),
            'pagination' => [
                'total' => $suppliers->total(),
                'per_page' => $suppliers->perPage(),
                'current_page' => $suppliers->currentPage(),
                'last_page' => $suppliers->lastPage(),
                'from' => $suppliers->firstItem(),
                'to' => $suppliers->lastItem(),
                'links' => $suppliers->linkCollection()->toArray()
            ]
        ]);
    }
}