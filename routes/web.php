<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\KategoriOlahragaController;
use App\Http\Controllers\Admin\LapanganController;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth','role:Admin'])->get('/test-admin', fn () => 'OK - Kamu Admin');
Route::middleware(['auth','role:Customer'])->get('/test-customer', fn () => 'OK - Kamu Customer');
Route::middleware(['auth','role:Kasir'])->get('/test-kasir', fn () => 'OK - Kamu Kasir');

Route::middleware(['auth', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('kategori-olahraga', KategoriOlahragaController::class);
    Route::resource('lapangan', LapanganController::class);
});
require __DIR__.'/auth.php';
