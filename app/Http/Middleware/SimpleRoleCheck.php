<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SimpleRoleCheck
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Vérifier le rôle dans la colonne role de la table users
        if (auth()->user()->role !== $role) {
            // Rediriger selon le rôle actuel
            return match(auth()->user()->role) {
                'admin' => redirect()->route('admin.dashboard'),
                'promoter' => redirect()->route('promoter.dashboard'),
                'client' => redirect()->route('client.dashboard'),
                default => redirect()->route('dashboard'),
            };
        }

        return $next($request);
    }
}
