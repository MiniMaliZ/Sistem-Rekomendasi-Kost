<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\KostIndex;
use App\Livewire\Admin\KriteriaIndex;
use App\Livewire\Admin\UserIndex;
use App\Livewire\Admin\RekomendasiIndex;
use App\Livewire\Admin\FeedbackIndex;
use App\Livewire\Admin\RiwayatIndex;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/kost', KostIndex::class)->name('kost');
    Route::get('/kriteria', KriteriaIndex::class)->name('kriteria');
    Route::get('/users', UserIndex::class)->name('users');
    Route::get('/rekomendasi', RekomendasiIndex::class)->name('rekomendasi');
    Route::get('/feedback', FeedbackIndex::class)->name('feedback');
    Route::get('/riwayat', RiwayatIndex::class)->name('riwayat');
});
