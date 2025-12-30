<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        
        $user = Auth::user();
        $request->session()->regenerate();
        
        // Vérifier si l'utilisateur est désactivé
        if ($user->status === 'inactive') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            // Envoyer une notification à l'admin
            $admin = \App\Models\User::where('role', 'admin')->first();
            if ($admin) {
                \App\Models\Notification::create([
                    'user_id' => $admin->id,
                    'title' => 'Tentative de connexion - Compte désactivé',
                    'message' => "L'utilisateur {$user->name} ({$user->email}) a tenté de se connecter mais son compte est désactivé.",
                    'type' => 'warning',
                    'is_read' => false
                ]);
            }
            
            return redirect()->route('login')
                ->with('error', 'Votre compte a été désactivé. Veuillez contacter l\'administrateur pour plus d\'informations.');
        }

        // Vérifier si c'est une demande de liaison de compte
        if ($request->has('link_account') && $request->get('link_account') === 'true') {
            // Récupérer l'ID du compte principal depuis la session
            $mainAccountId = $request->session()->get('linking_main_account_id');
            
            if ($mainAccountId) {
                $mainUserModel = \App\Models\User::find($mainAccountId);
                
                if ($mainUserModel && $mainUserModel->isMainPromoter()) {
                    // Lier le compte connecté au compte principal
                    $mainUserModel->linkedPromoterAccounts()->attach($user->id, [
                        'nickname' => $user->name,
                        'linked_at' => now(),
                    ]);
                    
                    // Se connecter automatiquement au compte principal
                    Auth::login($mainUserModel);
                    
                    // Nettoyer la session
                    $request->session()->forget('linking_main_account_id');
                    
                    return redirect()->intended(route('promoter.dashboard'))
                        ->with('success', 'Le compte a été lié avec succès !');
                }
            }
        }

        // Rediriger selon le rôle
        return match($user->role) {
            'admin' => redirect()->intended(route('admin.dashboard')),
            'sub_admin' => redirect()->intended(route('admin.sub-admins.dashboard')),
            'promoter' => redirect()->intended(route('promoter.dashboard')),
            'client' => redirect()->intended(route('client.dashboard')),
            default => redirect()->intended(route('dashboard')),
        };
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
