<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, string ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();
                
                Log::info('User is authenticated, redirecting based on role', [
                    'user_id' => $user->id,
                    'role' => $user->role
                ]);
                
                // Rediriger selon le rôle de l'utilisateur
                if ($user->role === 'admin') {
                    return redirect()->route('admin.dashboard');
                } elseif ($user->role === 'promoter') {
                    return redirect()->route('promoter.dashboard');
                } else {
                    return redirect()->route('client.dashboard');
                }
            }
        }

        Log::info('User is not authenticated, proceeding to login/register page');
        return $next($request);
    }
}
