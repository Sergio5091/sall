<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(Request $request): Response
    {
        $ref = $request->query('ref');
        $referrer = null;

        if ($ref) {
            $referrer = User::query()
                ->where('referral_code', $ref)
                ->first();

            if ($referrer) {
                $request->session()->put('ref', $ref);
            }
        }

        // Récupérer les rôles disponibles (client et promoter)
        $roles = [
            'client' => 'Client',
            'promoter' => 'Promoteur'
        ];
        
        return Inertia::render('Auth/Register', [
            'availableRoles' => $roles,
            'ref' => $ref,
            'referrer' => $referrer ? [
                'id' => $referrer->id,
                'name' => $referrer->name,
            ] : null,
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        if (!$request->filled('ref') && $request->session()->has('ref')) {
            $request->merge(['ref' => $request->session()->get('ref')]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|in:client,promoter',
            'ref' => 'nullable|string|exists:users,referral_code',
        ]);

        $parentId = null;
        if (!empty($validated['ref'])) {
            $parentId = User::query()
                ->where('referral_code', $validated['ref'])
                ->value('id');
        }

        // Créer l'utilisateur
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'], // Sauvegarder le rôle dans la colonne
            'referral_code' => User::generateUniqueReferralCode(),
            'parent_id' => $parentId,
        ]);

        $request->session()->forget('ref');

        // Si Spatie est installé et configuré, assigner le rôle
        if (class_exists('\Spatie\Permission\Models\Role') && \Spatie\Permission\Models\Role::count() > 0) {
            $user->assignRole($validated['role']);
        }

        event(new Registered($user));

        Auth::login($user);

        // Redirection selon le rôle
        return match($validated['role']) {
            'promoter' => redirect()->route('promoter.dashboard'),
            'client' => redirect()->route('client.dashboard'),
            default => redirect()->route('dashboard'),
        };
    }
}
