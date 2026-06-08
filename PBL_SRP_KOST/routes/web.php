<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\User\ListkostController;
use App\Http\Controllers\User\FavoritController;
use App\Http\Controllers\User\HistoryController;
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

// Landing Page — dapat diakses siapa saja (guest maupun user yang sudah login)
Route::get('/', function () {
    return view('welcome', [
        'title' => 'roomor - Sistem Rekomendasi Kost',
    ]);
})->name('landing');

// Tentang Kami — dapat diakses siapa saja
Route::get('/tentang', function () {
    return view('tentang', [
        'title' => 'Tentang Kami - roomor',
    ]);
})->name('tentang');

// ── Guest-only routes (redirect ke dashboard jika sudah login) ────────────
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

// ── Authenticated-only routes ─────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Profil — hanya user yang sudah login
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/photo', [ProfileController::class, 'upload'])->name('profile.upload');

    // PERBAIKAN: favorit.toggle diarahkan ke FavoritController@toggle
    Route::post('/favorit/toggle', [FavoritController::class, 'toggle'])
        ->name('favorit.toggle');

    // Hapus satu kost dari favorit (non-AJAX)
    Route::delete('/favorit/{kostId}', [FavoritController::class, 'destroy'])
        ->name('favorit.destroy');

    // Admin routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', Dashboard::class)->name('dashboard');
        Route::get('/kost', KostIndex::class)->name('kost');
    });

    // Preferensi — hanya user yang sudah login
    Route::get('/preferensi', function () {
        return view('user.preferensi_1', [
            'title' => 'Preferensi Kost - roomor',
        ]);
    })->name('preferensi');
});

// ── Public routes — dapat diakses guest maupun user yang sudah login ──────

// Dashboard utama
Route::get('/dashboard', [DashboardController::class, 'index'])->name('user.dashboard');
Route::get('/dashboard/search', [DashboardController::class, 'search'])->name('user.search');

// Halaman favorit — dapat diakses guest (controller yang menangani state guest vs auth)
Route::get('/favorite', [FavoritController::class, 'index'])->name('user.favorit');

// History — dapat diakses guest (controller menangani state guest dengan warning banner,
// tanpa redirect ke login)
Route::get('/history', [HistoryController::class, 'index'])->name('user.history');

// Daftar & detail kost — dapat diakses siapa saja
Route::get('/kost', [ListkostController::class, 'index'])->name('user.kost');
Route::get('/kost/{kost}', [ListkostController::class, 'show'])->name('user.kost.show');
