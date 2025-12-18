<?php

namespace App\Http\Controllers\Promoter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\User;

class AccountSwitchController extends Controller
{
    /**
     * Stocker l'ID du compte principal en session pour la liaison
     */
    public function storeLinkSession(Request $request)
    {
        $request->session()->put('linking_main_account_id', $request->mainAccountId);
        
        return response()->json(['success' => true]);
    }

    /**
     * Obtenir tous les comptes promoteurs (principal + liés)
     */
    public function index()
    {
        $user = Auth::user();
        
        // Vérifier que l'utilisateur est un promoteur
        if ($user->role !== 'promoter') {
            return redirect()->route('dashboard')
                ->with('error', 'Accès non autorisé.');
        }
        
        // Obtenir le compte principal
        $mainAccount = $user->isMainPromoter() ? $user : $user->getMainAccount();
        
        $allAccounts = $mainAccount->getAllPromoterAccounts();
        
        // Obtenir le compte actif
        $activeAccount = $mainAccount->getActivePromoterAccount();
        
        return Inertia::render('Promoter/AccountSwitch', [
            'mainAccount' => $mainAccount,
            'allAccounts' => $allAccounts,
            'activeAccount' => $activeAccount,
            'activeAccountId' => $activeAccount->id,
            'isMainAccount' => $user->isMainPromoter(),
        ]);
    }
    
    /**
     * Switcher vers un compte spécifique
     */
    public function switch(Request $request, $accountId)
    {
        $user = Auth::user();
        
        // Vérifier que l'utilisateur est un promoteur principal
        if (!$user->isMainPromoter()) {
            return back()->with('error', 'Seul le compte principal peut changer de compte.');
        }
        
        // Vérifier que le compte existe et appartient à l'utilisateur
        $targetAccount = $user->linkedPromoterAccounts()->where('users.id', $accountId)->first();
        
        if (!$targetAccount && $accountId != $user->id) {
            return back()->with('error', 'Ce compte n\'existe pas ou n\'est pas lié à votre compte.');
        }
        
        // Définir le compte comme actif
        $user->setActivePromoterAccount($accountId);
        
        $accountName = $accountId == $user->id ? $user->name : $targetAccount->name;
        
        return back()->with('success', 'Vous avez basculé vers le compte : ' . $accountName);
    }
    
    /**
     * Ajouter un nouveau compte promoteur lié
     */
    public function addAccount(Request $request)
    {
        $user = Auth::user();
        
        // Vérifier que l'utilisateur est un promoteur principal
        if (!$user->isMainPromoter()) {
            return back()->with('error', 'Seul le compte principal peut ajouter des comptes.');
        }
        
        $validated = $request->validate([
            'email' => 'required|email|exists:users,email',
            'nickname' => 'nullable|string|max:50',
        ]);
        
        // Trouver l'utilisateur à lier
        $targetUser = User::where('email', $validated['email'])->first();
        
        // Vérifier que c'est un promoteur
        if ($targetUser->role !== 'promoter') {
            return back()->with('error', 'Cet utilisateur n\'est pas un promoteur.');
        }
        
        // Vérifier que le compte n'est pas déjà lié
        if ($user->linkedPromoterAccounts()->where('users.id', $targetUser->id)->exists()) {
            return back()->with('error', 'Ce compte est déjà lié.');
        }
        
        // Vérifier que le compte n'est pas déjà un compte principal
        if ($targetUser->isMainPromoter()) {
            return back()->with('error', 'Ce compte est déjà un compte principal et ne peut être lié.');
        }
        
        // Vérifier que le compte n'est pas le même utilisateur
        if ($user->id === $targetUser->id) {
            return back()->with('error', 'Vous ne pouvez pas lier votre propre compte.');
        }
        
        // Ajouter le compte
        $user->addPromoterAccount($targetUser->id, $validated['nickname']);
        
        return back()->with('success', 'Le compte a été ajouté avec succès.');
    }
    
    /**
     * Retirer un compte promoteur lié
     */
    public function removeAccount(Request $request, $accountId)
    {
        $user = Auth::user();
        
        // Vérifier que l'utilisateur est un promoteur principal
        if (!$user->isMainPromoter()) {
            return back()->with('error', 'Seul le compte principal peut retirer des comptes.');
        }
        
        // Vérifier que le compte existe et est lié
        $linkedAccount = $user->linkedPromoterAccounts()->where('users.id', $accountId)->first();
        
        if (!$linkedAccount) {
            return back()->with('error', 'Ce compte n\'existe pas ou n\'est pas lié.');
        }
        
        // Retirer le compte
        $user->removePromoterAccount($accountId);
        
        return back()->with('success', 'Le compte a été retiré avec succès.');
    }
    
    /**
     * Mettre à jour le surnom d'un compte lié
     */
    public function updateNickname(Request $request, $accountId)
    {
        $user = Auth::user();
        
        // Vérifier que l'utilisateur est un promoteur principal
        if (!$user->isMainPromoter()) {
            return back()->with('error', 'Seul le compte principal peut modifier les comptes.');
        }
        
        $validated = $request->validate([
            'nickname' => 'nullable|string|max:50',
        ]);
        
        // Vérifier que le compte existe et est lié
        $linkedAccount = $user->linkedPromoterAccounts()->where('users.id', $accountId)->first();
        
        if (!$linkedAccount) {
            return back()->with('error', 'Ce compte n\'existe pas ou n\'est pas lié.');
        }
        
        // Mettre à jour le surnom
        $user->linkedPromoterAccounts()->updateExistingPivot($accountId, [
            'nickname' => $validated['nickname']
        ]);
        
        return back()->with('success', 'Le surnom a été mis à jour avec succès.');
    }
    
    /**
     * Obtenir le compte actuel via API
     */
    public function getActiveAccount()
    {
        $user = Auth::user();
        
        if ($user->role !== 'promoter') {
            return response()->json(['error' => 'Accès non autorisé'], 403);
        }
        
        $mainAccount = $user->isMainPromoter() ? $user : $user->getMainAccount();
        $activeAccount = $mainAccount->getActivePromoterAccount();
        
        return response()->json([
            'activeAccount' => $activeAccount,
            'activeAccountId' => $activeAccount->id,
            'isMainAccount' => $user->isMainPromoter(),
        ]);
    }
    
    /**
     * API pour obtenir tous les comptes du promoteur
     */
    public function getAccounts()
    {
        $user = Auth::user();
        
        if ($user->role !== 'promoter') {
            return response()->json(['error' => 'Accès non autorisé'], 403);
        }
        
        $mainAccount = $user->isMainPromoter() ? $user : $user->getMainAccount();
        $allAccounts = $mainAccount->getAllPromoterAccounts();
        $activeAccount = $mainAccount->getActivePromoterAccount();
        
        return response()->json([
            'mainAccount' => $mainAccount,
            'allAccounts' => $allAccounts,
            'activeAccountId' => $activeAccount->id,
            'isMainAccount' => $user->isMainPromoter(),
        ]);
    }
}
