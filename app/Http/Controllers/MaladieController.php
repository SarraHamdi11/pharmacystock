<?php

namespace App\Http\Controllers;

use App\Models\Maladie; // Assurez-vous que le modèle Maladie existe
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class MaladieController extends Controller
{
    /**
     * Display a listing of the maladies.
     */
    public function index(): View
    {
        return view('maladies.index', [
            'maladies' => Maladie::paginate(10)
        ]);
    }

    /**
     * Show the form for creating a new maladie.
     */
    public function create(): View
    {
        return view('maladies.create');
    }

    /**
     * Store a newly created maladie in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        Maladie::create($request->all()); // Créez une MaladieRequest pour la validation

        return redirect()->route('maladies.index')
            ->with('success', 'Maladie créée avec succès.');
    }

    /**
     * Display the specified maladie.
     */
    public function show(Maladie $maladie): View
    {
        return view('maladies.show', compact('maladie'));
    }

    /**
     * Show the form for editing the specified maladie.
     */
    public function edit(Maladie $maladie): View
    {
        return view('maladies.edit', compact('maladie'));
    }

    /**
     * Update the specified maladie in storage.
     */
    public function update(Request $request, Maladie $maladie): RedirectResponse
    {
        $maladie->update($request->all()); // Créez une MaladieRequest pour la validation

        return redirect()->route('maladies.index')
            ->with('success', 'Maladie mise à jour avec succès.');
    }

    /**
     * Show the form for confirming deletion of the specified maladie.
     */
    public function delete(Maladie $maladie): View
    {
        return view('maladies.delete', compact('maladie'));
    }

    /**
     * Remove the specified maladie from storage.
     */
    public function destroy(Maladie $maladie): RedirectResponse
    {
        $maladie->delete();

        return redirect()->route('maladies.index')
            ->with('success', 'Maladie supprimée avec succès.');
    }

    /**
     * Search for maladies by name or description.
     */
    public function search(Request $request)
    {
        $term = $request->input('term');
        $maladies = Maladie::where('nom', 'like', "%{$term}%") 
            ->orWhere('description', 'like', "%{$term}%") 
            ->paginate(10);

        return response()->json([
            'maladies' => $maladies->items(),
            'pagination' => [
                'total' => $maladies->total(),
                'per_page' => $maladies->perPage(),
                'current_page' => $maladies->currentPage(),
                'last_page' => $maladies->lastPage(),
                'from' => $maladies->firstItem(),
                'to' => $maladies->lastItem(),
                'links' => $maladies->linkCollection()->toArray()
            ]
        ]);
    }
}