<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\KategoriOlahragaController;
use App\Http\Controllers\Admin\LapanganController;
use App\Http\Controllers\Customer\CustomerLapanganController;
use App\Http\Controllers\Customer\BookingController;
use App\Http\Controllers\Customer\PaketLanggananController as CustomerPaketLanggananController;
use App\Http\Controllers\Kasir\BookingController as KasirBookingController;
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

    // Langganan
    Route::get('/lapangan/{lapangan}/paket', [CustomerPaketLanggananController::class, 'index'])->name('paket.index');
    Route::get('/paket/{paketLangganan}/kuota', [CustomerPaketLanggananController::class, 'ambilKuota'])->name('paket.kuota.create');
    Route::post('/paket/{paketLangganan}/kuota', [CustomerPaketLanggananController::class, 'storeKuota'])->name('paket.kuota.store');

    // Jadwal Tetap
    Route::get('/paket/{paketLangganan}/jadwal-tetap', [CustomerPaketLanggananController::class, 'ambilJadwalTetap'])->name('paket.jadwal-tetap.create');
    Route::post('/paket/{paketLangganan}/jadwal-tetap', [CustomerPaketLanggananController::class, 'storeJadwalTetap'])->name('paket.jadwal-tetap.store');

    // Route::get('/lapangan/{lapangan}/pilih-jenis', [BookingController::class, 'pilihJenis'])->name('pilih-jenis');
});

// KASIR
Route::middleware(['auth', 'role:Kasir'])->prefix('kasir')->name('kasir.')->group(function () {
    Route::get('/booking', [KasirBookingController::class, 'index'])->name('booking.index');
    Route::post('/pembayaran/{pembayaran}/konfirmasi', [KasirBookingController::class, 'konfirmasiPembayaran'])->name('pembayaran.konfirmasi');
    Route::get('/booking/manual', [KasirBookingController::class, 'create'])->name('booking.create');
    Route::post('/booking/manual', [KasirBookingController::class, 'store'])->name('booking.store');
});
require __DIR__.'/auth.php';
