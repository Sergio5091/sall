<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Salle;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Validation\Rule;

class SalleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Salle::with('promoter');

        // Filtrage par recherche
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%")
                  ->orWhereHas('promoter', function($subQuery) use ($search) {
                      $subQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filtrage par statut
        if ($request->filled('status')) {
            $query->where('statut', $request->input('status'));
        }

        $salles = $query->orderBy('created_at', 'desc')
                       ->paginate(10)
                       ->withQueryString();

        // Statistiques pour le tableau de bord
        $stats = [
            'total' => Salle::count(),
            'active' => Salle::where('statut', 'actif')->where('valide', true)->count(),
            'pending' => Salle::where('valide', false)->count(),
            'disabled' => Salle::where('statut', 'maintenance')->count(),
        ];

        return Inertia::render('Admin/Salles', [
            'salles' => $salles,
            'stats' => $stats,
            'filters' => $request->only(['search', 'status'])
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Salle $salle)
    {
        $salle->load(['promoter', 'evenements' => function($query) {
            $query->withCount('inscriptions')->orderBy('created_at', 'desc')->take(5);
        }]);

        // Statistiques pour cette salle
        $stats = [
            'total_events' => $salle->evenements()->count(),
            'active_events' => $salle->evenements()->where('statut', 'publie')->count(),
            'total_reservations' => $salle->evenements()->withCount('inscriptions')->get()->sum('inscriptions_count'),
        ];

        return Inertia::render('Admin/SalleDetails', [
            'salle' => $salle,
            'stats' => $stats
        ]);
    }

    /**
     * Approve a pending salle.
     */
    public function approve(Salle $salle)
    {
        \Log::info('Tentative d\'approbation de la salle ID: ' . $salle->id);
        \Log::info('Statut avant validation: valide = ' . $salle->valide . ', statut = ' . $salle->statut);
        
        if ($salle->valide) {
            \Log::warning('La salle est déjà validée');
            return back()->with('error', 'Cette salle est déjà validée.');
        }

        $result = $salle->update([
            'valide' => true,
            'statut' => 'actif'
        ]);
        
        \Log::info('Résultat de la mise à jour: ' . ($result ? 'succès' : 'échec'));
        \Log::info('Statut après validation: valide = ' . $salle->fresh()->valide . ', statut = ' . $salle->fresh()->statut);

        // Notifier le promoteur que la salle est validée
        \App\Models\Notification::createForUser(
            $salle->promoter_id,
            'Salle validée et publiée',
            "Votre salle '{$salle->nom}' a été validée par notre équipe d'administration et est maintenant visible par les clients.",
            'success',
            'salle',
            $salle->id
        );

        return back()->with('success', 'La salle a été validée et est maintenant visible par les utilisateurs.');
    }

    /**
     * Toggle salle status (active/disabled).
     */
    public function toggleStatus(Request $request, Salle $salle)
    {
        $request->validate([
            'status' => ['required', 'string', Rule::in(['actif', 'maintenance'])]
        ]);

        $oldStatus = $salle->statut;
        $newStatus = $request->input('status');

        if ($oldStatus === $newStatus) {
            return back()->with('info', 'Le statut de la salle n\'a pas changé.');
        }

        $salle->update(['statut' => $newStatus]);

        // Notifier le promoteur du changement de statut
        $notificationTitle = $newStatus === 'actif' 
            ? 'Salle réactivée'
            : 'Salle mise en maintenance';
            
        $notificationMessage = $newStatus === 'actif'
            ? "Votre salle '{$salle->nom}' a été réactivée et est de nouveau visible par les clients."
            : "Votre salle '{$salle->nom}' a été temporairement mise en maintenance et n'est plus visible par les clients.";
            
        $notificationType = $newStatus === 'actif' ? 'success' : 'warning';

        \App\Models\Notification::createForUser(
            $salle->promoter_id,
            $notificationTitle,
            $notificationMessage,
            $notificationType,
            'salle',
            $salle->id
        );

        $message = $newStatus === 'actif' 
            ? 'La salle a été réactivée avec succès.' 
            : 'La salle a été mise en maintenance avec succès.';

        return back()->with('success', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Salle $salle)
    {
        // Vérifier s'il y a des réservations actives
        $activeReservations = $salle->evenements()
            ->where('statut', 'publie')
            ->whereHas('inscriptions', function($query) {
                $query->where('statut', 'confirme')
                      ->where('date_debut', '>', now());
            })
            ->count();

        if ($activeReservations > 0) {
            return back()->with('error', 'Impossible de supprimer cette salle car elle a des réservations actives.');
        }

        DB::beginTransaction();
        try {
            // Supprimer les événements associés
            $salle->evenements()->delete();
            
            // Supprimer la salle
            $salle->delete();

            DB::commit();
            return redirect()->route('admin.salles.index')
                ->with('success', 'La salle a été supprimée définitivement.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Une erreur est survenue lors de la suppression de la salle.');
        }
    }

    /**
     * Get statistics for dashboard.
     */
    public function getStats()
    {
        $stats = [
            'total_salles' => Salle::count(),
            'active_salles' => Salle::where('statut', 'actif')->where('valide', true)->count(),
            'pending_salles' => Salle::where('valide', false)->count(),
            'disabled_salles' => Salle::where('statut', 'maintenance')->count(),
            'total_promoters' => User::where('role', 'promoter')->count(),
            'total_clients' => User::where('role', 'client')->count(),
            'recent_salles' => Salle::with('promoter')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get(),
        ];

        return response()->json($stats);
    }

    /**
     * Export salles data.
     */
    public function export(Request $request)
    {
        $query = Salle::with('promoter');

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $salles = $query->get();

        // TODO: Implémenter l'export CSV/Excel
        return response()->json([
            'message' => 'Fonctionnalité d\'export à implémenter',
            'data' => $salles
        ]);
    }

    /**
     * Bulk actions on salles.
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => ['required', 'string', Rule::in(['validate', 'disable', 'enable', 'delete'])],
            'salle_ids' => ['required', 'array', 'min:1'],
            'salle_ids.*' => ['integer', 'exists:salles,id']
        ]);

        $action = $request->input('action');
        $salleIds = $request->input('salle_ids');

        try {
            switch ($action) {
                case 'validate':
                    Salle::whereIn('id', $salleIds)
                        ->where('valide', false)
                        ->update([
                            'valide' => true,
                            'statut' => 'actif'
                        ]);
                    $message = 'Les salles sélectionnées ont été validées et sont maintenant visibles.';
                    break;

                case 'disable':
                    Salle::whereIn('id', $salleIds)
                        ->update(['statut' => 'maintenance']);
                    $message = 'Les salles sélectionnées ont été mises en maintenance.';
                    break;

                case 'enable':
                    Salle::whereIn('id', $salleIds)
                        ->update(['statut' => 'actif']);
                    $message = 'Les salles sélectionnées ont été activées.';
                    break;

                case 'delete':
                    // Vérifier les réservations actives avant suppression
                    $sallesWithReservations = Salle::whereIn('id', $salleIds)
                        ->whereHas('events', function($query) {
                            $query->where('status', 'published')
                                  ->whereHas('reservations', function($subQuery) {
                                      $subQuery->where('status', 'confirmed')
                                              ->where('date_debut', '>', now());
                                  });
                        })
                        ->count();

                    if ($sallesWithReservations > 0) {
                        return back()->with('error', 'Certaines salles ont des réservations actives et ne peuvent être supprimées.');
                    }

                    Salle::whereIn('id', $salleIds)->delete();
                    $message = 'Les salles sélectionnées ont été supprimées.';
                    break;
            }

            return back()->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'Une erreur est survenue lors de l\'action de masse.');
        }
    }
}
