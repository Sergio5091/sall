<?php

namespace App\Http;

// Importation des middlewares personnalisés pour la gestion des rôles
use App\Http\Middleware\IsAdmin;       // Middleware pour les administrateurs
use App\Http\Middleware\IsClient;      // Middleware pour les clients
use App\Http\Middleware\IsPromoter;    // Middleware pour les promoteurs
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * La pile de middleware HTTP globale de l'application.
     *
     * Ces middlewares sont exécutés à CHAQUE requête HTTP de l'application.
     * Ils sont appliqués dans l'ordre où ils sont listés.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // Désactive la vérification des hôtes de confiance (décommenter et configurer si nécessaire)
        // \App\Http\Middleware\TrustHosts::class,
        
        // Gère les proxies (utile derrière un load balancer ou un reverse proxy)
        \App\Http\Middleware\TrustProxies::class,
        
        // Gère les en-têtes CORS (Cross-Origin Resource Sharing)
        \Illuminate\Http\Middleware\HandleCors::class,
        
        // Affiche une page de maintenance quand l'application est en mode maintenance
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        
        // Vérifie la taille des requêtes POST
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        
        // Nettoie les chaînes de caractères en supprimant les espaces superflus
        \App\Http\Middleware\TrimStrings::class,
        
        // Convertit les chaînes vides en null
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * Les groupes de middleware de route de l'application.
     * Permet de regrouper plusieurs middlewares sous un nom commun.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        // Groupe 'web' : appliqué à toutes les routes web (accédées via un navigateur)
        'web' => [
            // Chiffre les cookies pour la sécurité
            \App\Http\Middleware\EncryptCookies::class,
            
            // Ajoute les cookies en file d'attente à la réponse
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            
            // Démarre la session pour maintenir l'état entre les requêtes
            \Illuminate\Session\Middleware\StartSession::class,
            
            // Partage les erreurs de validation avec les vues
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            
            // Protège contre les attaques CSRF (Cross-Site Request Forgery)
            \App\Http\Middleware\VerifyCsrfToken::class,
            
            // Permet l'injection de dépendances dans les contrôleurs
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            
            // Gère les requêtes Inertia.js pour le rendu côté client
            \App\Http\Middleware\HandleInertiaRequests::class,
            
            // Définit automatiquement le compte promoteur actif
            \App\Http\Middleware\SetActivePromoterAccount::class,
        ],

        // Groupe 'api' : appliqué aux routes d'API
        'api' => [
            // Middleware pour l'authentification d'API avec Sanctum (décommenter si nécessaire)
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            
            // Limite le nombre de requêtes pour prévenir les abus
            // Le ':api' fait référence à la configuration dans config/throttle.php
            \Illuminate\Routing\Middleware\ThrottleRequests::class.':api',
            
            // Permet l'injection de dépendances dans les contrôleurs d'API
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * Les alias de middleware de l'application.
     *
     * Permet d'utiliser des noms courts au lieu des noms de classe complets
     * lors de l'assignation des middlewares aux routes.
     *
     * @var array<string, class-string|string>
     */
    protected $middlewareAliases = [
        // Vérifie si l'utilisateur est authentifié
        'auth' => \App\Http\Middleware\Authenticate::class,
        
        // Authentification HTTP Basic (pour les APIs)
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        
        // Gère l'authentification par session
        'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
        
        // Définit les en-têtes de cache HTTP
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        
        // Vérifie les autorisations avec les gates/policies
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        
        // Redirige les utilisateurs déjà connectés (pour les pages comme login/register)
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        
        // Demande une confirmation de mot de passe avant d'accéder à certaines routes
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        
        // Gère les requêtes précognitives (validation côté serveur en temps réel)
        'precognitive' => \Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests::class,
        
        // Vérifie les signatures d'URL (pour les liens sécurisés)
        'signed' => \App\Http\Middleware\ValidateSignature::class,
        
        // Limite le taux de requêtes (protection contre les attaques par force brute)
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        
        // Vérifie que l'email de l'utilisateur est vérifié
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        
        // Middleware temporaire pour vérification de rôle simple
        'simple.role' => \App\Http\Middleware\SimpleRoleCheck::class,
        
        // Middleware Spatie Permission (quand installé)
        'role' => \Spatie\Permission\Middlewares\RoleMiddleware::class,
        'permission' => \Spatie\Permission\Middlewares\PermissionMiddleware::class,
        'role_or_permission' => \Spatie\Permission\Middlewares\RoleOrPermissionMiddleware::class,
        
        // Middleware personnalisé pour les administrateurs
        'role.admin' => IsAdmin::class,
        
        // Middleware personnalisé pour les promoteurs
        'role.promoter' => IsPromoter::class,
        
        // Middleware personnalisé pour les clients
        'role.client' => IsClient::class,
    ];
}
