<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TrackController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('test', [HomeController::class, 'test'])->name('test');
Route::get('tracks', [TrackController::class, 'index'])->name('tracks');
Route::get('tracks/create', [TrackController::class, 'create'])->name('tracks.create');
Route::post('tracks', [TrackController::class, 'store'])->name('tracks.store');
require __DIR__.'/settings.php';
