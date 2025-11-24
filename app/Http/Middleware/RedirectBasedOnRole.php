<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectBasedOnRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Vérifier si l'utilisateur a un des rôles autorisés
        if (!empty($roles) && !$user->hasAnyRole($roles)) {
            // Rediriger selon le rôle
            if ($user->hasRole('admin')) {
                return redirect()->route('admin.dashboard');
            } elseif ($user->hasRole('promoter')) {
                return redirect()->route('promoter.dashboard');
            }
            return redirect()->route('client.dashboard');
        }

        return $next($request);
    }
}