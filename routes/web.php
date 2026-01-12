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
Route::get('/deploy/migrate', function (Request $request) {
    // 1. Validasi Keamanan (Wajib!)
    // Ganti 'DEPLOYMENT_KEY' dengan nama variabel di .env Anda
    $secretKey = env('DEPLOYMENT_KEY');

    if (!$secretKey || $request->query('key') !== $secretKey) {
        abort(403, 'Unauthorized action.');
    }

    try {
        // 2. Jalankan Migrasi
        // --force wajib digunakan karena di hosting environment-nya biasanya 'production'
        Artisan::call('migrate', ['--force' => true]);

        // 3. Ambil Output Terminalnya
        $output = Artisan::output();

        // 4. Return Output agar bisa dibaca oleh GitHub Actions
        return response("Migration Status:\n" . $output, 200)
            ->header('Content-Type', 'text/plain');
    } catch (\Exception $e) {
        return response("Migration Failed: " . $e->getMessage(), 500);
    }
});
