<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\TrackController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('test', [HomeController::class, 'test'])->name('test');

Route::middleware(['auth', 'verified'])->name('tracks.')->prefix('tracks')->group(function () {
    Route::get('/', [TrackController::class, 'index'])->name('index');
    Route::middleware('admin')->group(function () {
        Route::resource('tracks', TrackController::class)->except(['index', 'show']);
    });
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('playlists', \App\Http\Controllers\PlaylistController::class);
    Route::post('playlists/{playlist}/tracks', [\App\Http\Controllers\PlaylistTrackController::class, 'store'])->name('playlists.tracks.store');
    Route::delete('playlists/{playlist}/tracks/{track}', [\App\Http\Controllers\PlaylistTrackController::class, 'destroy'])->name('playlists.tracks.destroy');

    Route::resource('api-keys', \App\Http\Controllers\ApiKeyController::class)->parameters([
        'api-keys' => 'apiKey:slug',
    ]);
});

require __DIR__.'/settings.php';
