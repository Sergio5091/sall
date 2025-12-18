<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Salle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * Afficher les favoris de l'utilisateur
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'Utilisateur non authentifié'], 401);
        }

        $favorites = Favorite::where('user_id', $user->id)
            ->with('salle')
            ->get()
            ->map(function ($favorite) {
                return $favorite->salle;
            })
            ->filter();

        return response()->json([
            'favorites' => $favorites
        ]);
    }

    /**
     * Ajouter une salle aux favoris
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'Utilisateur non authentifié'], 401);
        }

        $request->validate([
            'salle_id' => 'required|exists:salles,id'
        ]);

        // Vérifier si déjà en favoris
        $existing = Favorite::where('user_id', $user->id)
            ->where('salle_id', $request->salle_id)
            ->first();

        if ($existing) {
            return response()->json(['error' => 'Salle déjà dans les favoris'], 422);
        }

        $favorite = Favorite::create([
            'user_id' => $user->id,
            'salle_id' => $request->salle_id
        ]);

        return response()->json([
            'message' => 'Salle ajoutée aux favoris',
            'favorite' => $favorite
        ]);
    }

    /**
     * Supprimer une salle des favoris
     */
    public function destroy($salleId)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'Utilisateur non authentifié'], 401);
        }

        $favorite = Favorite::where('user_id', $user->id)
            ->where('salle_id', $salleId)
            ->first();

        if (!$favorite) {
            return response()->json(['error' => 'Favori non trouvé'], 404);
        }

        $favorite->delete();

        return response()->json([
            'message' => 'Salle retirée des favoris'
        ]);
    }
}
