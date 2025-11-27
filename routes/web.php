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
    Route::get('/venues/edit', [SalleController::class, 'edit'])->name('venues.edit');
    Route::put('/venues', [SalleController::class, 'update'])->name('venues.update');
    Route::patch('/venues/coordinates', [SalleController::class, 'updateCoordinates'])->name('venues.coordinates.update');
    Route::delete('/venues', [SalleController::class, 'destroy'])->name('venues.destroy');
    
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

Route::middleware(['auth'])->prefix('client')->name('client.')->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Client/Dashboard');
    })->name('dashboard');
    Route::get('/profile', function () {
        return Inertia::render('Client/Profile');
    })->name('profile');
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Admin/Dashboard');
    })->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
