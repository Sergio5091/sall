<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('welcome');

Route::get('/search/rooms', function () {
    return Inertia::render('Search/Rooms');
})->name('search.rooms');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Routes protégées par rôle (temporairement sans middleware de rôle pour tester)
use App\Http\Controllers\Promoter\DashboardController;
use App\Http\Controllers\Promoter\NotificationsController;
use App\Http\Controllers\Promoter\SalleController;
use App\Http\Controllers\Promoter\EventController;

Route::middleware(['auth'])->prefix('promoter')->name('promoter.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Routes pour la gestion des salles
    Route::get('/venues', [SalleController::class, 'index'])->name('venues');
    Route::get('/venues/create', [SalleController::class, 'create'])->name('venues.create');
    Route::post('/venues', [SalleController::class, 'store'])->name('venues.store');
    Route::get('/venues/{salle}', [SalleController::class, 'show'])->name('venues.show');
    Route::get('/venues/{salle}/edit', [SalleController::class, 'edit'])->name('venues.edit');
    Route::put('/venues/{salle}', [SalleController::class, 'update'])->name('venues.update');
    Route::delete('/venues/{salle}', [SalleController::class, 'destroy'])->name('venues.destroy');
    
    // Routes pour la gestion des événements
    Route::get('/events', [EventController::class, 'index'])->name('events');
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');
    Route::post('/events/{event}/duplicate', [EventController::class, 'duplicate'])->name('events.duplicate');
    Route::post('/events/{event}/publish', [EventController::class, 'publish'])->name('events.publish');
    Route::post('/events/{event}/cancel', [EventController::class, 'cancel'])->name('events.cancel');
    Route::get('/notifications', [NotificationsController::class, 'index'])->name('notifications');
    Route::post('/notifications/{id}/read', [NotificationsController::class, 'markAsRead'])->name('notifications.read');
    Route::delete('/notifications/{id}', [NotificationsController::class, 'delete'])->name('notifications.delete');
    Route::post('/notifications/read-all', [NotificationsController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::delete('/notifications/delete-all', [NotificationsController::class, 'deleteAll'])->name('notifications.delete-all');
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
    Route::get('/promoteurs', [App\Http\Controllers\Admin\UserController::class, 'promoters'])->name('promoteurs.index');
    Route::get('/promoteurs/{user}', [App\Http\Controllers\Admin\UserController::class, 'showPromoter'])->name('promoteurs.show');
    Route::patch('/promoteurs/{user}/toggle-status', [App\Http\Controllers\Admin\UserController::class, 'togglePromoterStatus'])->name('promoteurs.toggle-status');
    Route::delete('/promoteurs/{user}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('promoteurs.destroy');
    
    // Routes pour la gestion des clients (admin)
    Route::get('/clients', [App\Http\Controllers\Admin\UserController::class, 'clients'])->name('clients.index');
    Route::get('/clients/{user}', [App\Http\Controllers\Admin\UserController::class, 'showClient'])->name('clients.show');
    Route::patch('/clients/{user}/toggle-status', [App\Http\Controllers\Admin\UserController::class, 'toggleClientStatus'])->name('clients.toggle-status');
    Route::delete('/clients/{user}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('clients.destroy');
    
    // Routes pour la gestion des événements (admin)
    Route::get('/events', [App\Http\Controllers\Admin\EventController::class, 'index'])->name('events.index');
    Route::get('/events/{event}', [App\Http\Controllers\Admin\EventController::class, 'show'])->name('events.show');
    Route::delete('/events/{event}', [App\Http\Controllers\Admin\EventController::class, 'destroy'])->name('events.destroy');
    Route::patch('/events/{event}/toggle-status', [App\Http\Controllers\Admin\EventController::class, 'toggleStatus'])->name('events.toggle-status');
    
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
