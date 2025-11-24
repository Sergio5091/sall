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
Route::middleware(['auth'])->prefix('promoter')->name('promoter.')->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Promoter/Dashboard');
    })->name('dashboard');
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
