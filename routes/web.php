<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\KategoriOlahragaController;
use App\Http\Controllers\Admin\LapanganController;
use App\Http\Controllers\Customer\CustomerLapanganController;
use App\Http\Controllers\Customer\BookingController;
use App\Http\Controllers\Admin\PaketLanggananController;

Route::view('/', 'welcome');

// Route::view('dashboard', 'dashboard')
//     ->middleware(['auth', 'verified'])
//     ->name('dashboard');

Route::get('/dashboard', function () {
    // Pastikan 'role' sesuai dengan nama kolom role di table users Anda
    $role = auth()->user()->role; 

    if ($role === 'Admin') {
        // Arahkan ke salah satu halaman admin, atau buat dashboard khusus admin
        return redirect()->route('admin.kategori-olahraga.index'); 
    } elseif ($role === 'Customer') {
        // Arahkan ke beranda customer
        return redirect()->route('customer.beranda'); 
    } elseif ($role === 'Kasir') {
        // Arahkan ke halaman kasir
        return redirect('/test-kasir'); 
    }

    // Jika tidak ada role yang cocok (opsional)
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth','role:Admin'])->get('/test-admin', fn () => 'OK - Kamu Admin');
Route::middleware(['auth','role:Customer'])->get('/test-customer', fn () => 'OK - Kamu Customer');
Route::middleware(['auth','role:Kasir'])->get('/test-kasir', fn () => 'OK - Kamu Kasir');

Route::middleware(['auth', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('kategori-olahraga', KategoriOlahragaController::class);
    Route::resource('lapangan', LapanganController::class);
    Route::resource('paket-langganan', PaketLanggananController::class);
});

Route::middleware(['auth', 'role:Customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/beranda', [CustomerLapanganController::class, 'index'])->name('beranda');
    // Route::get('/beranda', [CustomerLapanganController::class, 'index'])->name('beranda');
    Route::get('/lapangan/{lapangan}/pilih-jenis', [BookingController::class, 'pilihJenis'])->name('pilih-jenis');
    Route::get('/lapangan/{lapangan}/booking', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/lapangan/{lapangan}/booking', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/riwayat', [BookingController::class, 'riwayat'])->name('riwayat');

    Route::get('/lapangan/{lapangan}/booking', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/lapangan/{lapangan}/booking', [BookingController::class, 'store'])->name('booking.store');

    // Route::get('/lapangan/{lapangan}/pilih-jenis', [BookingController::class, 'pilihJenis'])->name('pilih-jenis');
});
require __DIR__.'/auth.php';
