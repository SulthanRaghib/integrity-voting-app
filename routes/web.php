<?php

use App\Http\Controllers\Auth\GoogleLoginController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
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

// CI/CD Akses terminal dengan FTP
Route::get('/run-migration', function () {
    try {
        // Menjalankan migrasi dengan flag --force (wajib untuk production)
        Artisan::call('migrate', ['--force' => true]);

        $output = Artisan::output();
        return "<h1>Migrasi Sukses!</h1><pre>$output</pre>";
    } catch (\Exception $e) {
        return "<h1>Migrasi Gagal</h1><p>" . $e->getMessage() . "</p>";
    }
});

Route::get('/clear-cache', function () {
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    return 'Cache Cleared';
});
