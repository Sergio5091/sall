<?php

use App\Http\Controllers\Search\RoomController;
use App\Http\Controllers\Api\FavoriteController;
use Illuminate\Support\Facades\Route;

// Routes API pour la recherche
Route::get('/search/nearby', [RoomController::class, 'searchNearby']);

// Routes API pour les favoris (protégées par authentification web)
Route::middleware('auth')->group(function () {
    Route::get('/favorites', [FavoriteController::class, 'index']);
    Route::post('/favorites', [FavoriteController::class, 'store']);
    Route::delete('/favorites/{salleId}', [FavoriteController::class, 'destroy']);
});
