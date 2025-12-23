<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Salle;
use App\Models\User;
use App\Models\SubAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class SubAdminDashboardController extends Controller
{
    /**
     * Display the sub-admin dashboard with location-based filtering
     */
    public function index()
    {
        $user = Auth::user();
        
        // Debug: Check user role and sub-admin existence
        \Log::info('SubAdminDashboard index - User ID: ' . $user->id . ', Role: ' . $user->role);
        
        $subAdmin = SubAdmin::where('user_id', $user->id)->first();
        
        if (!$subAdmin) {
            \Log::error('SubAdmin not found for user ID: ' . $user->id);
            abort(403, 'Sous-admin non trouvé. Accès refusé.');
        }
        
        \Log::info('SubAdmin found: ' . $subAdmin->id . ', Country: ' . $subAdmin->country . ', City: ' . $subAdmin->city);
        
        // Get salles filtered by location
        $sallesQuery = Salle::query();
        
        if ($subAdmin->country) {
            $sallesQuery->where('pays', $subAdmin->country);
        }
        
        if ($subAdmin->city) {
            $sallesQuery->where('ville', $subAdmin->city);
        }
        
        $salles = $sallesQuery->with('promoter')->latest()->get();
        
        \Log::info('Found ' . $salles->count() . ' salles for sub-admin');
        
        // Get promoters filtered by location
        $promotersQuery = User::where('role', 'promoter');
        
        if ($subAdmin->country) {
            $promotersQuery->where('country', $subAdmin->country);
        }
        
        if ($subAdmin->city) {
            $promotersQuery->where('city', $subAdmin->city);
        }
        
        $promoters = $promotersQuery->latest()->get();
        
        \Log::info('Found ' . $promoters->count() . ' promoters for sub-admin');
        
        // Statistics
        $stats = [
            'total_salles' => $salles->count(),
            'active_salles' => $salles->where('status', 'active')->count(),
            'pending_salles' => $salles->where('status', 'pending')->count(),
            'total_promoters' => $promoters->count(),
            'active_promoters' => $promoters->where('status', 'active')->count(),
            'location' => [
                'country' => $subAdmin->country,
                'city' => $subAdmin->city,
            ]
        ];

        return Inertia::render('Admin/SubAdminDashboard', [
            'stats' => $stats,
            'salles' => $salles,
            'promoters' => $promoters,
            'subAdminInfo' => [
                'id' => $subAdmin->id,
                'location' => [
                    'country' => $subAdmin->country,
                    'city' => $subAdmin->city,
                ]
            ]
        ]);
    }

    
    
    
    
    /**
     * Toggle promoter status
     */
    public function togglePromoterStatus(User $promoter)
    {
        $subAdmin = SubAdmin::where('user_id', Auth::id())->firstOrFail();
        
        // Check if promoter role
        if ($promoter->role !== 'promoter') {
            abort(404, 'Promoter not found.');
        }
        
        // Check if sub-admin can access this promoter based on location
        // Only check if sub-admin has specific location restrictions
        if ($subAdmin->country && $promoter->country && $promoter->country !== $subAdmin->country) {
            abort(403, 'Unauthorized access to this promoter.');
        }
        
        if ($subAdmin->city && $promoter->city && $promoter->city !== $subAdmin->city) {
            abort(403, 'Unauthorized access to this promoter.');
        }
        
        $promoter->status = $promoter->status === 'active' ? 'inactive' : 'active';
        $promoter->save();

        return back()->with('success', 'Statut du promoteur mis à jour avec succès.');
    }

    /**
     * Display salles index for sub-admin
     */
    public function sallesIndex()
    {
        $user = Auth::user();
        $subAdmin = SubAdmin::where('user_id', $user->id)->firstOrFail();
        
        // Get salles filtered by location
        $sallesQuery = Salle::query();
        
        if ($subAdmin->country) {
            $sallesQuery->where('pays', $subAdmin->country);
        }
        
        if ($subAdmin->city) {
            $sallesQuery->where('ville', $subAdmin->city);
        }
        
        $salles = $sallesQuery->with('promoter')->latest()->get();
        
        // Log pour débogage - vérifier les statuts réels
        \Log::info('Salles loaded for sub-admin', [
            'sub_admin_id' => $subAdmin->id,
            'salles_count' => $salles->count(),
            'salles_data' => $salles->map(function($salle) {
                return [
                    'id' => $salle->id,
                    'nom' => $salle->nom,
                    'statut' => $salle->statut,
                    'valide' => $salle->valide
                ];
            })->toArray()
        ]);
        
        // Calculate stats
        $stats = [
            'total' => $salles->count(),
            'active' => $salles->where('statut', 'actif')->count(),
            'pending' => $salles->where('valide', false)->count(),
            'inactive' => $salles->where('statut', 'inactif')->count(),
        ];
        
        return Inertia::render('SubAdmin/Salles/Index', [
            'salles' => $salles,
            'stats' => $stats
        ]);
    }

    /**
     * Display promoters index for sub-admin
     */
    public function promotersIndex()
    {
        $user = Auth::user();
        $subAdmin = SubAdmin::where('user_id', $user->id)->firstOrFail();
        
        // Get promoters filtered by location
        $promotersQuery = User::where('role', 'promoter');
        
        if ($subAdmin->country) {
            $promotersQuery->where('country', $subAdmin->country);
        }

        if ($subAdmin->city) {
            $promotersQuery->where('city', $subAdmin->city);
        }

        $promoters = $promotersQuery->latest()->get();

        // Calculate stats
        $stats = [
            'total' => $promoters->count(),
            'active' => $promoters->where('status', 'active')->count(),
            'inactive' => $promoters->where('status', 'inactive')->count(),
            'total_salles' => Salle::whereIn('user_id', $promoters->pluck('id'))->count(),
        ];

        return Inertia::render('SubAdmin/Promoters/Index', [
            'promoters' => $promoters,
            'stats' => $stats
        ]);
    }

    /**
     * Show salle details for sub-admin
     */
    public function showSalle(Salle $salle)
    {
        $user = Auth::user();
        $subAdmin = SubAdmin::where('user_id', $user->id)->firstOrFail();
        
        // Check if sub-admin can access this salle based on location
        // Only check if sub-admin has specific location restrictions
        if ($subAdmin->country && $salle->pays && $salle->pays !== $subAdmin->country) {
            abort(403, 'Unauthorized access to this salle.');
        }
        
        if ($subAdmin->city && $salle->ville && $salle->ville !== $subAdmin->city) {
            abort(403, 'Unauthorized access to this salle.');
        }
        
        $salle->load('promoter');
        
        return Inertia::render('SubAdmin/Salles/Show', [
            'salle' => $salle
        ]);
    }

    /**
     * Show promoter details for sub-admin
     */
    public function showPromoter(User $promoter)
    {
        $user = Auth::user();
        $subAdmin = SubAdmin::where('user_id', $user->id)->firstOrFail();
        
        // Check if promoter role
        if ($promoter->role !== 'promoter') {
            abort(404, 'Promoter not found.');
        }
        
        // Check if sub-admin can access this promoter based on location
        // Only check if sub-admin has specific location restrictions
        if ($subAdmin->country && $promoter->country && $promoter->country !== $subAdmin->country) {
            abort(403, 'Unauthorized access to this promoter.');
        }
        
        if ($subAdmin->city && $promoter->city && $promoter->city !== $subAdmin->city) {
            abort(403, 'Unauthorized access to this promoter.');
        }
        
        // Load promoter's salles
        $promoter->salles = Salle::where('user_id', $promoter->id)->get();
        
        return Inertia::render('SubAdmin/Promoters/Show', [
            'promoter' => $promoter
        ]);
    }

    /**
     * Validate salle
     */
    public function validateSalle($salleId)
    {
        $user = Auth::user();
        $subAdmin = SubAdmin::where('user_id', $user->id)->firstOrFail();
        
        $salle = Salle::findOrFail($salleId);
        
        // Check if sub-admin can access this salle based on location
        // Only check if sub-admin has specific location restrictions
        if ($subAdmin->country && $salle->pays && $salle->pays !== $subAdmin->country) {
            abort(403, 'Unauthorized access to this salle.');
        }
        
        if ($subAdmin->city && $salle->ville && $salle->ville !== $subAdmin->city) {
            abort(403, 'Unauthorized access to this salle.');
        }
        
        $salle->statut = 'actif';
        $salle->valide = true;
        $salle->valide_par_admin = true;
        $salle->validated_at = now();
        $salle->validated_by = $user->id;
        $salle->save();

        return back()->with('success', 'Salle validée avec succès.');
    }

    /**
     * Deactivate salle
     */
    public function deactivateSalle($salleId)
    {
        $user = Auth::user();
        $subAdmin = SubAdmin::where('user_id', $user->id)->firstOrFail();
        
        $salle = Salle::findOrFail($salleId);
        
        // Check if sub-admin can access this salle based on location
        // Only check if sub-admin has specific location restrictions
        if ($subAdmin->country && $salle->pays && $salle->pays !== $subAdmin->country) {
            abort(403, 'Unauthorized access to this salle.');
        }
        
        if ($subAdmin->city && $salle->ville && $salle->ville !== $subAdmin->city) {
            abort(403, 'Unauthorized access to this salle.');
        }
        
        $salle->statut = 'inactif';
        $salle->deactivated_at = now();
        $salle->deactivated_by = $user->id;
        $salle->save();

        return back()->with('success', 'Salle désactivée avec succès.');
    }

    /**
     * Update salle
     */
    public function updateSalle(Request $request, $salleId)
    {
        $user = Auth::user();
        $subAdmin = SubAdmin::where('user_id', $user->id)->firstOrFail();
        
        $salle = Salle::findOrFail($salleId);
        
        // Check if sub-admin can access this salle based on location
        // Only check if sub-admin has specific location restrictions
        if ($subAdmin->country && $salle->pays && $salle->pays !== $subAdmin->country) {
            abort(403, 'Unauthorized access to this salle.');
        }
        
        if ($subAdmin->city && $salle->ville && $salle->ville !== $subAdmin->city) {
            abort(403, 'Unauthorized access to this salle.');
        }
        
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'capacite_max' => 'nullable|integer|min:1',
            'surface' => 'nullable|numeric|min:1',
            'type' => 'nullable|string|max:255',
            'statut' => 'required|in:actif,inactif,maintenance',
        ]);
        
        $salle->update($validated);

        return back()->with('success', 'Salle mise à jour avec succès.');
    }

    /**
     * Toggle salle status
     */
    public function toggleSalleStatus($salleId)
    {
        $user = Auth::user();
        $subAdmin = SubAdmin::where('user_id', $user->id)->firstOrFail();
        
        $salle = Salle::findOrFail($salleId);
        
        // Check if sub-admin can access this salle based on location
        // Only check if sub-admin has specific location restrictions
        if ($subAdmin->country && $salle->pays && $salle->pays !== $subAdmin->country) {
            abort(403, 'Unauthorized access to this salle.');
        }
        
        if ($subAdmin->city && $salle->ville && $salle->ville !== $subAdmin->city) {
            abort(403, 'Unauthorized access to this salle.');
        }
        
        $oldStatus = $salle->statut;
        $salle->statut = $salle->statut === 'actif' ? 'inactif' : 'actif';
        $salle->save();

        // Log pour débogage
        \Log::info('Salle status toggled', [
            'salle_id' => $salle->id,
            'old_status' => $oldStatus,
            'new_status' => $salle->statut,
            'sub_admin_id' => $subAdmin->id
        ]);

        return redirect()->route('admin.admin.sub-admin.salles.index')
            ->with('success', 'Statut de la salle mis à jour avec succès.');
    }

    /**
     * Reset promoter password
     */
    public function resetPromoterPassword($promoterId)
    {
        $user = Auth::user();
        $subAdmin = SubAdmin::where('user_id', $user->id)->firstOrFail();
        
        $promoter = User::where('role', 'promoter')->findOrFail($promoterId);
        
        // Check if sub-admin can access this promoter based on location
        // Only check if sub-admin has specific location restrictions
        if ($subAdmin->country && $promoter->country && $promoter->country !== $subAdmin->country) {
            abort(403, 'Unauthorized access to this promoter.');
        }
        
        if ($subAdmin->city && $promoter->city && $promoter->city !== $subAdmin->city) {
            abort(403, 'Unauthorized access to this promoter.');
        }
        
        $newPassword = Str::random(12);
        $promoter->password = Hash::make($newPassword);
        $promoter->save();

        // TODO: Send email with new password
        // Mail::to($promoter->email)->send(new PasswordResetMail($newPassword));

        return back()->with('success', 'Mot de passe réinitialisé avec succès. Nouveau mot de passe: ' . $newPassword);
    }
}
