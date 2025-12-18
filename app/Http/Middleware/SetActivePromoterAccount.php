<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SetActivePromoterAccount
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Si l'utilisateur est un promoteur et est connecté
        if (Auth::check() && Auth::user()->role === 'promoter') {
            $user = Auth::user();
            
            // Si aucun compte actif n'est en session
            if (!session('active_promoter_account_id')) {
                $mainAccount = $user->isMainPromoter() ? $user : $user->getMainAccount();
                $activeAccount = $mainAccount->getActivePromoterAccount();
                
                // Si le promoteur a un compte actif en base
                if ($activeAccount) {
                    session(['active_promoter_account_id' => $activeAccount->id]);
                } else {
                    // Sinon, prendre le compte principal comme actif par défaut
                    session(['active_promoter_account_id' => $mainAccount->id]);
                }
            }
        }
        
        return $next($request);
    }
}
