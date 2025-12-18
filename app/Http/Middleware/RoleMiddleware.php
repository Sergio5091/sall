<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (! $request->user() || ! $request->user()->hasRole($role)) {
            // Rediriger vers la page d'accueil si le rôle ne correspond pas
            return redirect('/')->with('error', 'Accès non autorisé.');
        }

        return $next($request);
    }
}
