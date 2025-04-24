<?php

namespace App\Http\Controllers;

use App\Models\Medicament; // Assurez-vous que le modèle Medicament existe
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class MedicamentController extends Controller
{
    /**
     * Display a listing of the medicaments.
     */
    public function index(): View
    {
        return view('medicaments.index', [
            'medicaments' => Medicament::paginate(10)
        ]);
    }

    /**
     * Show the form for creating a new medicament.
     */
    public function create(): View
    {
        return view('medicaments.create');
    }

    /**
     * Store a newly created medicament in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        Medicament::create($request->all()); // Créez une MedicamentRequest pour la validation

        return redirect()->route('medicaments.index')
            ->with('success', 'Médicament créé avec succès.');
    }

    /**
     * Display the specified medicament.
     */
    public function show(Medicament $medicament): View
    {
        return view('medicaments.show', compact('medicament'));
    }

    /**
     * Show the form for editing the specified medicament.
     */
    public function edit(Medicament $medicament): View
    {
        return view('medicaments.edit', compact('medicament'));
    }

    /**
     * Update the specified medicament in storage.
     */
    public function update(Request $request, Medicament $medicament): RedirectResponse
    {
        $medicament->update($request->all()); // Créez une MedicamentRequest pour la validation

        return redirect()->route('medicaments.index')
            ->with('success', 'Médicament mis à jour avec succès.');
    }

    
    public function delete(Medicament $medicament): View
    {
        return view('medicaments.delete', compact('medicament'));
    }

   
    public function destroy(Medicament $medicament): RedirectResponse
    {
        $medicament->delete();

        return redirect()->route('medicaments.index')
            ->with('success', 'Médicament supprimé avec succès.');
    }

    public function search(Request $request)
    {
        $term = $request->input('term');
        $medicaments = Medicament::where('nom_commercial', 'like', "%{$term}%") // Assurez-vous du nom du champ
            ->orWhere('nom_generique', 'like', "%{$term}%") // Assurez-vous du nom du champ
            ->orWhere('description', 'like', "%{$term}%") // Assurez-vous du nom du champ
            ->paginate(10);

        return response()->json([
            'medicaments' => $medicaments->items(),
            'pagination' => [
                'total' => $medicaments->total(),
                'per_page' => $medicaments->perPage(),
                'current_page' => $medicaments->currentPage(),
                'last_page' => $medicaments->lastPage(),
                'from' => $medicaments->firstItem(),
                'to' => $medicaments->lastItem(),
                'links' => $medicaments->linkCollection()->toArray()
            ]
        ]);
    }
}