<?php

use App\Http\Controllers\Auth\GoogleLoginController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

// Filament Google OAuth routes
Route::get('/admin/auth/google/redirect', [GoogleLoginController::class, 'redirectToGoogle'])->name('filament.google.redirect');
Route::get('/admin/auth/google/callback', [GoogleLoginController::class, 'handleGoogleCallback'])->name('filament.google.callback');

// Voting Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/bilik-suara', [VoteController::class, 'index'])->name('voting.index');
    Route::post('/vote', [VoteController::class, 'store'])->name('vote.store');
});
