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
    public function create(): Response
    {
        // Récupérer les rôles disponibles (client et promoter)
        $roles = [
            'client' => 'Client',
            'promoter' => 'Promoteur'
        ];
        
        return Inertia::render('Auth/Register', [
            'availableRoles' => $roles
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|in:client,promoter'
        ]);

        // Créer l'utilisateur
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'], // Sauvegarder le rôle dans la colonne
        ]);

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
