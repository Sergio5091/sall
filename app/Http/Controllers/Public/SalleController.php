<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Salle;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SalleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $salles = Salle::with(['promoter', 'images'])
            ->where('statut', 'approuvee')
            ->when($request->search, function($query, $search) {
                $query->where('nom', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhere('adresse', 'like', "%{$search}%");
            })
            ->when($request->type, function($query, $type) {
                $query->where('type', $type);
            })
            ->when($request->min_price, function($query, $price) {
                $query->where('prix_heure', '>=', $price);
            })
            ->when($request->max_price, function($query, $price) {
                $query->where('prix_heure', '<=', $price);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return Inertia::render('Public/Salles/Index', [
            'salles' => $salles,
            'filters' => $request->only(['search', 'type', 'min_price', 'max_price'])
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Salle $salle)
    {
        return Inertia::render('Public/Salles/Show', [
            'salle' => $salle
        ]);
    }
}
