<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Salle;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * Display all promoters.
     */
    public function promoters(Request $request)
    {
        $query = User::where('role', 'promoter')->with('salles');

        // Filtrage par date d'inscription
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }

        // Filtrage par recherche
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $promoteurs = $query->orderBy('created_at', 'desc')->get();

        $stats = [
            'total_promoteurs' => User::where('role', 'promoter')->count(),
            'total_salles' => Salle::count(),
            'avg_salles_per_promoter' => User::where('role', 'promoter')->withCount('salles')->get()->avg('salles_count'),
        ];

        return Inertia::render('Admin/Promoteurs', [
            'promoteurs' => $promoteurs,
            'stats' => $stats,
            'filters' => $request->only(['search', 'date_from', 'date_to'])
        ]);
    }

    /**
     * Show a specific promoter.
     */
    public function showPromoter(User $user)
    {
        if ($user->role !== 'promoter') {
            abort(404);
        }

        $user->load(['salles' => function($query) {
            $query->orderBy('created_at', 'desc');
        }]);

        $stats = [
            'total_salles' => $user->salles()->count(),
            'active_salles' => $user->salles()->where('statut', 'actif')->count(),
            'inactive_salles' => $user->salles()->where('statut', 'inactif')->count(),
            'maintenance_salles' => $user->salles()->where('statut', 'maintenance')->count(),
        ];

        return Inertia::render('Admin/PromoteurDetails', [
            'promoteur' => $user,
            'stats' => $stats
        ]);
    }

    /**
     * Toggle promoter status.
     */
    public function togglePromoterStatus(Request $request, User $user)
    {
        if ($user->role !== 'promoter') {
            abort(404);
        }

        $request->validate([
            'status' => ['required', 'string', Rule::in(['active', 'inactive'])]
        ]);

        // TODO: Implement status toggle logic
        return back()->with('success', 'Statut du promoteur mis à jour.');
    }

    /**
     * Display all clients.
     */
    public function clients(Request $request)
    {
        $query = User::where('role', 'client')->withCount('reservations');

        // Filtrage par date d'inscription
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }

        // Filtrage par recherche
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $clients = $query->orderBy('created_at', 'desc')->get();

        $stats = [
            'total_clients' => User::where('role', 'client')->count(),
            'total_reservations' => \App\Models\Reservation::count(),
            'avg_reservations_per_client' => $clients->avg('reservations_count') ?? 0,
        ];

        return Inertia::render('Admin/Clients', [
            'clients' => $clients,
            'stats' => $stats,
            'filters' => $request->only(['search', 'date_from', 'date_to'])
        ]);
    }

    /**
     * Show a specific client.
     */
    public function showClient(User $user)
    {
        if ($user->role !== 'client') {
            abort(404);
        }

        $user->load(['reservations' => function($query) {
            $query->with('salle')->orderBy('created_at', 'desc');
        }]);

        return Inertia::render('Admin/ClientDetails', [
            'client' => $user
        ]);
    }

    /**
     * Toggle client status.
     */
    public function toggleClientStatus(Request $request, User $user)
    {
        if ($user->role !== 'client') {
            abort(404);
        }

        $request->validate([
            'status' => ['required', 'string', Rule::in(['active', 'inactive'])]
        ]);

        // TODO: Implement status toggle logic
        return back()->with('success', 'Statut du client mis à jour.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Prevent deletion of admin users
        if ($user->role === 'admin') {
            return back()->with('error', 'Impossible de supprimer un administrateur.');
        }

        // Check if promoter has active salles
        if ($user->role === 'promoter' && $user->salles()->where('statut', 'actif')->count() > 0) {
            return back()->with('error', 'Impossible de supprimer un promoteur avec des salles actives.');
        }

        $user->delete();

        $message = $user->role === 'promoter' 
            ? 'Le promoteur a été supprimé avec succès.' 
            : 'Le client a été supprimé avec succès.';

        return back()->with('success', $message);
    }
}
