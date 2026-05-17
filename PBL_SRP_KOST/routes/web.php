<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — roomor Sistem Rekomendasi Kost
|--------------------------------------------------------------------------
*/

// Landing Page
Route::get('/', function () {
    return view('welcome', [
        'title' => 'roomor - Sistem Rekomendasi Kost',
    ]);
})->name('home');

// Tentang Kami
Route::get('/tentang', function () {
    return view('tentang', [
        'title' => 'Tentang Kami - roomor',
    ]);
})->name('tentang');

// ── Guest routes ─────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {

    // Login
    Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    // Register
    Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');

    // Reset Password Langsung (tanpa token/email)
    Route::get('/forgot-password', function () {
        return view('auth.forgot-password');
    })->name('password.request');

    Route::post('/forgot-password', function (Request $request) {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ], [
            'email.required'     => 'Email wajib diisi.',
            'email.email'        => 'Format email tidak valid.',
            'password.required'  => 'Password baru wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min'       => 'Password minimal 8 karakter.',
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan dalam sistem kami.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')
            ->with('status', 'Password berhasil direset. Silakan masuk.');

    })->name('password.direct-reset');

});

// ── Authenticated routes ──────────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// USER
    Route::get('/user_home', function () {
        return view('user_home');
    })->name('user_home');

    Route::get('/user_listkost', function () {
        return view('user_listkost');
    })->name('user_listkost');

    Route::get('/user_fav', function () {
        return view('user_fav');
    })->name('user_fav');

    Route::get('/user_history', function () {
        return view('user_history');
    })->name('user_history');
