<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\UserHomeController;
use App\Http\Controllers\Profile\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\HybridRecommendation;
use App\Livewire\Admin\KostIndex;
use App\Livewire\Admin\KriteriaIndex;

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

// ── HALAMAN UTAMA ─────────────────────────────────────────────────────────
Route::get('/home',        [UserHomeController::class, 'index'])->name('user.home');
Route::get('/home/search', [UserHomeController::class, 'search'])->name('user.search');

// ── TOGGLE FAVORIT (AJAX, disimpan ke Session) ───────────────────────────
Route::post('/favorit/toggle', [UserHomeController::class, 'toggleFavorit'])
    ->name('favorit.toggle');

// ── PLACEHOLDER (dikembangkan setelah database siap) ─────────────────────
Route::get('/kost',    fn() => view('user.coming_soon', ['halaman' => 'Daftar Kost']))->name('user.kost');
Route::get('/favorit', fn() => view('user.coming_soon', ['halaman' => 'Favorit']))->name('user.favorit');
Route::get('/riwayat', fn() => view('user.coming_soon', ['halaman' => 'Riwayat']))->name('user.riwayat');

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/kost', KostIndex::class)->name('kost');
    Route::get('/rekomendasi-hybrid', HybridRecommendation::class)->name('rekomendasi-hybrid');
});

// Profil
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/photo', [ProfileController::class, 'upload'])->name('profile.upload');
;

// Preferensi
Route::get('/preferensi', function () {
    return view('user.preferensi_1', [
        'title' => 'Preferensi Kost - roomor',
    ]);
})->name('preferensi');
