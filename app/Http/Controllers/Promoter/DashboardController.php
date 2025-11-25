<?php

namespace App\Http\Controllers\Promoter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Affiche le tableau de bord du promoteur.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Ici, vous pouvez ajouter la logique pour récupérer les données du tableau de bord
        // Par exemple :
        // $salles = $user->salles()->count();
        // $evenements = $user->evenements()->count();
        // $reservations = $user->reservations()->count();
        
        return Inertia::render('Promoter/Dashboard', [
            'stats' => [
                'salles' => 0, // Remplacer par $salles
                'evenements' => 0, // Remplacer par $evenements
                'reservations' => 0, // Remplacer par $reservations
            ]
        ]);
    }
}
