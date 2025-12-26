<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Display the client's profile.
     */
    public function index()
    {
        $user = Auth::user();
        
        return Inertia::render('Client/Profile', [
            'user' => $user,
            'auth' => [
                'user' => $user
            ]
        ]);
    }

    /**
     * Update the client's profile.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'telephone' => 'nullable|string|max:20',
            'date_naissance' => 'nullable|date',
            'newsletter' => 'boolean',
            'notifications_email' => 'boolean',
            'partage_profil' => 'boolean',
        ]);

        $user->update($validated);

        return redirect()->back()->with('success', 'Profil mis à jour avec succès !');
    }

    /**
     * Update the client's password.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($validated['password'])
        ]);

        return redirect()->back()->with('success', 'Mot de passe changé avec succès !');
    }

    /**
     * Download client's data.
     */
    public function downloadData()
    {
        $user = Auth::user();
        
        $data = [
            'profile' => $user->toArray(),
            'reservations' => $user->reservations()->with(['salle'])->get()->toArray(),
            'created_at' => now()->toISOString(),
        ];

        return response()->json($data);
    }

    /**
     * Delete client's account.
     */
    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'password' => 'required|string',
        ]);

        $user = Auth::user();
        
        if (!Hash::check($validated['password'], $user->password)) {
            return redirect()->back()->withErrors(['password' => 'Mot de passe incorrect']);
        }

        // Supprimer les réservations associées
        $user->reservations()->delete();
        
        // Supprimer l'utilisateur
        $user->delete();

        Auth::logout();
        
        return redirect()->route('welcome')->with('success', 'Votre compte a été supprimé avec succès.');
    }
}
