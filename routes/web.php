<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\Auth\GoogleLoginController;

// Filament Google OAuth routes
Route::get('/admin/auth/google/redirect', [GoogleLoginController::class, 'redirectToGoogle'])->name('filament.google.redirect');
Route::get('/admin/auth/google/callback', [GoogleLoginController::class, 'handleGoogleCallback'])->name('filament.google.callback');

use App\Http\Controllers\VoteController;

Route::middleware(['auth'])->group(function () {
    Route::get('/voting', [VoteController::class, 'index'])->name('voting.index');
    Route::post('/voting', [VoteController::class, 'store'])->name('voting.store');
});
