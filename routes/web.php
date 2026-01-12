<?php

use App\Http\Controllers\Auth\GoogleLoginController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

// Custom Unified Login (Points to Filament Login)
Route::get('/login', function () {
    return redirect()->route('filament.admin.auth.login');
})->name('login');

// Google OAuth for Voters
Route::get('/auth/google', [GoogleLoginController::class, 'redirectToGoogle'])->name('auth.google');

// Filament Google OAuth routes (Support legacy/admin callbacks)
Route::get('/admin/auth/google/redirect', [GoogleLoginController::class, 'redirectToGoogle'])->name('filament.google.redirect');
Route::get('/admin/auth/google/callback', [GoogleLoginController::class, 'handleGoogleCallback'])->name('filament.google.callback');

// Voting Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/bilik-suara', [VoteController::class, 'index'])->name('voting.index');
    Route::post('/vote', [VoteController::class, 'store'])->name('vote.store');
    Route::post('/logout', function () {
        Illuminate\Support\Facades\Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('welcome');
    })->name('logout');
});
