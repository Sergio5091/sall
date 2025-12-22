<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    $news = \App\Models\News::where('is_active', true)
        ->orderBy('created_at', 'desc')
        ->take(9)
        ->get();
    
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => app()->version(),
        'phpVersion' => PHP_VERSION,
        'news' => $news
    ]);
})->name('welcome');

// Route pour la connexion admin
Route::get('/admin/login', function () {
    return Inertia::render('Admin/Login');
})->name('admin.login');

Route::get('/search/rooms', function () {
    return Inertia::render('Search/Rooms');
})->name('search.rooms');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Routes protégées par rôle (temporairement sans middleware de rôle pour tester)
use App\Http\Controllers\Promoter\DashboardController;
use App\Http\Controllers\Promoter\PromoterProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SalleController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Promoter\AccountSwitchController;

Route::middleware(['auth'])->prefix('promoter')->name('promoter.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Routes pour la gestion du profil
    Route::get('/profile', [PromoterProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [PromoterProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/photo', [PromoterProfileController::class, 'updatePhoto'])->name('profile.photo');
    Route::delete('/profile/photo', [PromoterProfileController::class, 'deletePhoto'])->name('profile.photo.delete');
    Route::put('/password', [PromoterProfileController::class, 'updatePassword'])->name('password.update');
    Route::delete('/profile', [PromoterProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/stats', [PromoterProfileController::class, 'stats'])->name('profile.stats');
    
    // Routes pour la gestion des comptes promoteurs (API uniquement)
    Route::get('/api/accounts', [AccountSwitchController::class, 'getAccounts'])->name('api.accounts');
    Route::post('/accounts/add', [AccountSwitchController::class, 'addAccount'])->name('accounts.add');
    Route::post('/accounts/switch/{accountId}', [AccountSwitchController::class, 'switch'])->name('accounts.switch');
    Route::delete('/accounts/{accountId}', [AccountSwitchController::class, 'removeAccount'])->name('accounts.remove');
    Route::patch('/accounts/{accountId}/nickname', [AccountSwitchController::class, 'updateNickname'])->name('accounts.update-nickname');
    Route::get('/api/active-account', [AccountSwitchController::class, 'getActiveAccount'])->name('api.active-account');
    Route::post('/accounts/store-link-session', [AccountSwitchController::class, 'storeLinkSession'])->name('accounts.store-link-session');
    
    // Routes pour la gestion des salles
    Route::get('/venues', [App\Http\Controllers\Promoter\SalleController::class, 'index'])->name('venues');
    Route::get('/venues/create', [App\Http\Controllers\Promoter\SalleController::class, 'create'])->name('venues.create');
    Route::post('/venues', [App\Http\Controllers\Promoter\SalleController::class, 'store'])->name('venues.store');
    Route::get('/venues/{salle}', [App\Http\Controllers\Promoter\SalleController::class, 'show'])->name('venues.show');
    Route::get('/venues/{salle}/edit', [App\Http\Controllers\Promoter\SalleController::class, 'edit'])->name('venues.edit');
    Route::put('/venues/{salle}', [App\Http\Controllers\Promoter\SalleController::class, 'update'])->name('venues.update');
    Route::delete('/venues/{salle}', [App\Http\Controllers\Promoter\SalleController::class, 'destroy'])->name('venues.destroy');
    
    // Routes pour la gestion des événements
    Route::get('/events', [App\Http\Controllers\Promoter\EventController::class, 'index'])->name('events');
    Route::get('/events/create', [App\Http\Controllers\Promoter\EventController::class, 'create'])->name('events.create');
    Route::post('/events', [App\Http\Controllers\Promoter\EventController::class, 'store'])->name('events.store');
    Route::get('/events/{event}', [App\Http\Controllers\Promoter\EventController::class, 'show'])->name('events.show');
    Route::get('/events/{event}/edit', [App\Http\Controllers\Promoter\EventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{event}', [App\Http\Controllers\Promoter\EventController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}', [App\Http\Controllers\Promoter\EventController::class, 'destroy'])->name('events.destroy');
    Route::get('/events/{event}/participants', [App\Http\Controllers\Promoter\EventController::class, 'participants'])->name('events.participants');
    Route::post('/events/{event}/duplicate', [App\Http\Controllers\Promoter\EventController::class, 'duplicate'])->name('events.duplicate');
    Route::post('/events/{event}/publish', [App\Http\Controllers\Promoter\EventController::class, 'publish'])->name('events.publish');
    Route::post('/events/{event}/cancel', [App\Http\Controllers\Promoter\EventController::class, 'cancel'])->name('events.cancel');
    Route::get('/notifications', [App\Http\Controllers\Promoter\NotificationController::class, 'index'])->name('notifications');
    Route::post('/notifications/{notification}/read', [App\Http\Controllers\Promoter\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::delete('/notifications/{notification}', [App\Http\Controllers\Promoter\NotificationController::class, 'destroy'])->name('notifications.delete');
    Route::post('/notifications/read-all', [App\Http\Controllers\Promoter\NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::delete('/notifications/delete-all', [App\Http\Controllers\Promoter\NotificationController::class, 'clearAll'])->name('notifications.delete-all');
    
    // Routes pour la gestion des messages
    Route::get('/messages', [App\Http\Controllers\Promoter\MessageController::class, 'index'])->name('messages');
    Route::get('/messages/create', [App\Http\Controllers\Promoter\MessageController::class, 'create'])->name('messages.create');
    Route::post('/messages', [App\Http\Controllers\Promoter\MessageController::class, 'store'])->name('messages.store');
    Route::get('/messages/{message}', [App\Http\Controllers\Promoter\MessageController::class, 'show'])->name('messages.show');
    Route::get('/messages/{message}/edit', [App\Http\Controllers\Promoter\MessageController::class, 'edit'])->name('messages.edit');
    Route::put('/messages/{message}', [App\Http\Controllers\Promoter\MessageController::class, 'update'])->name('messages.update');
    Route::delete('/messages/{message}', [App\Http\Controllers\Promoter\MessageController::class, 'destroy'])->name('messages.destroy');
    Route::post('/messages/{message}/send', [App\Http\Controllers\Promoter\MessageController::class, 'send'])->name('messages.send');
    Route::get('/api/messages/subscribers-count', [App\Http\Controllers\Promoter\MessageController::class, 'getSubscribersCount'])->name('api.messages.subscribers-count');
    
    // API pour le compteur de notifications
    Route::get('/api/unread-count', function () {
        $user = Auth::user();
        $count = \App\Models\Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();
        return response()->json(['count' => $count]);
    })->withoutMiddleware(['inertia']);
});

Route::middleware(['auth', 'role:client'])->prefix('client')->name('client.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Client\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/salles', [App\Http\Controllers\Client\SalleController::class, 'index'])->name('salles');
    Route::get('/salles/{salle}', [App\Http\Controllers\Client\SalleController::class, 'show'])->name('salles.show');
    Route::post('/salles/{salle}/reserver', [App\Http\Controllers\Client\SalleController::class, 'reserver'])->name('salles.reserver');

    Route::get('/reseau', [App\Http\Controllers\Client\ReseauController::class, 'index'])->name('reseau');
    
    // Routes pour les événements
    Route::get('/evenements', [App\Http\Controllers\Client\EvenementController::class, 'index'])->name('evenements');
    Route::get('/evenements/{evenement}', [App\Http\Controllers\Client\EvenementController::class, 'show'])->name('evenements.show');
    
    // Routes pour les réservations
    Route::get('/reservations', [App\Http\Controllers\Client\ReservationController::class, 'index'])->name('reservations');
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Admin/Dashboard');
    })->name('dashboard');
    
    // Routes pour la gestion des salles (admin)
    Route::get('/salles', [App\Http\Controllers\Admin\SalleController::class, 'index'])->name('salles.index');
    Route::get('/salles/{salle}', [App\Http\Controllers\Admin\SalleController::class, 'show'])->name('salles.show');
    Route::patch('/salles/{salle}/approve', [App\Http\Controllers\Admin\SalleController::class, 'approve'])->name('salles.approve');
    Route::patch('/salles/{salle}/toggle-status', [App\Http\Controllers\Admin\SalleController::class, 'toggleStatus'])->name('salles.toggle-status');
    Route::delete('/salles/{salle}', [App\Http\Controllers\Admin\SalleController::class, 'destroy'])->name('salles.destroy');
    Route::post('/salles/bulk-action', [App\Http\Controllers\Admin\SalleController::class, 'bulkAction'])->name('salles.bulk-action');
    Route::get('/salles/export', [App\Http\Controllers\Admin\SalleController::class, 'export'])->name('salles.export');
    Route::get('/salles/stats', [App\Http\Controllers\Admin\SalleController::class, 'getStats'])->name('salles.stats');
    
    // Routes pour la gestion des promoteurs (admin)
    Route::get('/promoteurs', [UserController::class, 'promoters'])->name('promoteurs.index');
    Route::get('/promoteurs/{user}', [UserController::class, 'showPromoter'])->name('promoteurs.show');
    Route::patch('/promoteurs/{user}/toggle-status', [UserController::class, 'togglePromoterStatus'])->name('promoteurs.toggle-status');
    Route::post('/promoters/{user}/notify', [NotificationController::class, 'sendToPromoter'])->name('promoters.notify');
    Route::delete('/promoteurs/{user}', [UserController::class, 'destroy'])->name('promoteurs.destroy');
    
    // Routes pour la gestion des clients (admin)
    Route::get('/clients', [App\Http\Controllers\Admin\UserController::class, 'clients'])->name('clients.index');
    Route::get('/clients/{user}', [App\Http\Controllers\Admin\UserController::class, 'showClient'])->name('clients.show');
    Route::patch('/clients/{user}/toggle-status', [App\Http\Controllers\Admin\UserController::class, 'toggleClientStatus'])->name('clients.toggle-status');
    Route::post('/clients/{user}/notify', [NotificationController::class, 'sendToClient'])->name('clients.notify');
    
    // Routes pour la gestion des événements (admin)
    Route::get('/events', [App\Http\Controllers\Admin\EventController::class, 'index'])->name('events.index');
    Route::get('/events/{event}', [App\Http\Controllers\Admin\EventController::class, 'show'])->name('events.show');
    Route::delete('/events/{event}', [App\Http\Controllers\Admin\EventController::class, 'destroy'])->name('events.destroy');
    Route::patch('/events/{event}/toggle-status', [App\Http\Controllers\Admin\EventController::class, 'toggleStatus'])->name('events.toggle-status');
    
    // Routes pour la gestion des actualités (admin)
    Route::get('/news', [NewsController::class, 'index'])->name('news.index');
    Route::get('/news/create', [NewsController::class, 'create'])->name('news.create');
    Route::post('/news', [NewsController::class, 'store'])->name('news.store');
    Route::get('/news/{news}', [NewsController::class, 'show'])->name('news.show');
    Route::get('/news/{news}/edit', [NewsController::class, 'edit'])->name('news.edit');
    Route::put('/news/{news}', [NewsController::class, 'update'])->name('news.update');
    Route::delete('/news/{news}', [NewsController::class, 'destroy'])->name('news.destroy');
    
    // Routes pour la gestion des sous-admins (admin)
    Route::get('/sub-admins', [App\Http\Controllers\Admin\SubAdminController::class, 'index'])->name('sub-admins.index');
    Route::get('/sub-admins/create', [App\Http\Controllers\Admin\SubAdminController::class, 'create'])->name('sub-admins.create');
    Route::post('/sub-admins', [App\Http\Controllers\Admin\SubAdminController::class, 'store'])->name('sub-admins.store');
    Route::get('/sub-admins/{subAdmin}', [App\Http\Controllers\Admin\SubAdminController::class, 'show'])->name('sub-admins.show');
    Route::get('/sub-admins/{subAdmin}/edit', [App\Http\Controllers\Admin\SubAdminController::class, 'edit'])->name('sub-admins.edit');
    Route::put('/sub-admins/{subAdmin}', [App\Http\Controllers\Admin\SubAdminController::class, 'update'])->name('sub-admins.update');
    Route::delete('/sub-admins/{subAdmin}', [App\Http\Controllers\Admin\SubAdminController::class, 'destroy'])->name('sub-admins.destroy');
    Route::patch('/sub-admins/{subAdmin}/toggle-status', [App\Http\Controllers\Admin\SubAdminController::class, 'toggleStatus'])->name('sub-admins.toggle-status');
    
    // Routes pour la gestion des événements ponctuels (admin)
    Route::get('/standalone-events', [App\Http\Controllers\Admin\StandaloneEventController::class, 'index'])->name('standalone-events.index');
    Route::get('/standalone-events/create', [App\Http\Controllers\Admin\StandaloneEventController::class, 'create'])->name('standalone-events.create');
    Route::post('/standalone-events', [App\Http\Controllers\Admin\StandaloneEventController::class, 'store'])->name('standalone-events.store');
    Route::get('/standalone-events/{standaloneEvent}', [App\Http\Controllers\Admin\StandaloneEventController::class, 'show'])->name('standalone-events.show');
    Route::get('/standalone-events/{standaloneEvent}/edit', [App\Http\Controllers\Admin\StandaloneEventController::class, 'edit'])->name('standalone-events.edit');
    Route::put('/standalone-events/{standaloneEvent}', [App\Http\Controllers\Admin\StandaloneEventController::class, 'update'])->name('standalone-events.update');
    Route::delete('/standalone-events/{standaloneEvent}', [App\Http\Controllers\Admin\StandaloneEventController::class, 'destroy'])->name('standalone-events.destroy');
    Route::patch('/standalone-events/{standaloneEvent}/toggle-status', [App\Http\Controllers\Admin\StandaloneEventController::class, 'toggleStatus'])->name('standalone-events.toggle-status');
    
    // Routes pour le dashboard des sous-admins
    Route::get('/sub-admin-dashboard', [App\Http\Controllers\Admin\SubAdminDashboardController::class, 'index'])->name('sub-admins.dashboard');
    
    // Routes pour la gestion des salles par sous-admin
    Route::get('/sub-admin/salles', [App\Http\Controllers\Admin\SubAdminDashboardController::class, 'sallesIndex'])->name('admin.sub-admin.salles.index');
    Route::get('/sub-admin/salles/{salle}', [App\Http\Controllers\Admin\SubAdminDashboardController::class, 'showSalle'])->name('admin.sub-admin.salles.show');
    Route::post('/sub-admin/salles/{salle}/validate', [App\Http\Controllers\Admin\SubAdminDashboardController::class, 'validateSalle'])->name('admin.sub-admin.salles.validate');
    Route::post('/sub-admin/salles/{salle}/deactivate', [App\Http\Controllers\Admin\SubAdminDashboardController::class, 'deactivateSalle'])->name('admin.sub-admin.salles.deactivate');
    Route::put('/sub-admin/salles/{salle}/update', [App\Http\Controllers\Admin\SubAdminDashboardController::class, 'updateSalle'])->name('admin.sub-admin.salles.update');
    Route::post('/sub-admin/salles/{salle}/toggle-status', [App\Http\Controllers\Admin\SubAdminDashboardController::class, 'toggleSalleStatus'])->name('admin.sub-admin.salles.toggle-status');
    
    // Routes pour la gestion des promoteurs par sous-admin
    Route::get('/sub-admin/promoters', [App\Http\Controllers\Admin\SubAdminDashboardController::class, 'promotersIndex'])->name('admin.sub-admin.promoters.index');
    Route::get('/sub-admin/promoters/{promoter}', [App\Http\Controllers\Admin\SubAdminDashboardController::class, 'showPromoter'])->name('admin.sub-admin.promoters.show');
    Route::post('/sub-admin/promoters/{promoter}/toggle-status', [App\Http\Controllers\Admin\SubAdminDashboardController::class, 'togglePromoterStatus'])->name('admin.sub-admin.promoters.toggle-status');
    Route::post('/sub-admin/promoters/{promoter}/reset-password', [App\Http\Controllers\Admin\SubAdminDashboardController::class, 'resetPromoterPassword'])->name('admin.sub-admin.promoters.reset-password');
    
    // Routes pour le profil administrateur
    Route::get('/profile', function () {
        return Inertia::render('Admin/Profile', [
            'auth' => [
                'user' => auth()->user()
            ]
        ]);
    })->name('profile');
    
    Route::put('/profile', function (Illuminate\Http\Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . auth()->id(),
        ]);

        auth()->user()->update($validated);

        return back()->with('success', 'Profil mis à jour avec succès.');
    })->name('profile.update');
    
    Route::put('/password', function (Illuminate\Http\Request $request) {
        $validated = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($validated['current_password'], auth()->user()->password)) {
            return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.']);
        }

        auth()->user()->update([
            'password' => Hash::make($validated['password'])
        ]);

        return back()->with('success', 'Mot de passe mis à jour avec succès.');
    })->name('password.update');
    
    // Routes pour les paramètres
    Route::get('/settings', function () {
        return Inertia::render('Admin/Settings');
    })->name('settings');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Routes publiques pour les événements
Route::get('/events', [App\Http\Controllers\Public\EventController::class, 'index'])->name('events');
Route::get('/events/{event}', [App\Http\Controllers\Public\EventController::class, 'show'])->name('events.show');
Route::middleware('auth')->post('/events/{event}/register', [App\Http\Controllers\Public\EventController::class, 'register'])->name('events.register');

// Routes publiques pour les salles
Route::get('/salles', [App\Http\Controllers\Public\SalleController::class, 'index'])->name('public.salles');
Route::get('/salles/{salle}', [App\Http\Controllers\Public\SalleController::class, 'show'])->name('public.salles.show');

// Routes pour la recherche de salles
Route::get('/search/rooms', [App\Http\Controllers\Search\RoomController::class, 'index'])->name('search.rooms');

// Route API pour la recherche de salles à proximité par GPS
Route::get('/api/search/nearby', [App\Http\Controllers\Search\RoomController::class, 'searchNearby']);

// Routes API pour les favoris (protégées par authentification web)
Route::middleware('auth')->group(function () {
    Route::get('/api/favorites', [App\Http\Controllers\Api\FavoriteController::class, 'index']);
    Route::post('/api/favorites', [App\Http\Controllers\Api\FavoriteController::class, 'store']);
    Route::delete('/api/favorites/{salleId}', [App\Http\Controllers\Api\FavoriteController::class, 'destroy']);
});

require __DIR__.'/auth.php';
