<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\KategoriOlahragaController;
use App\Http\Controllers\Admin\LapanganController;
use App\Http\Controllers\Customer\CustomerLapanganController;
use App\Http\Controllers\Customer\BookingController;
use App\Http\Controllers\Customer\PaketLanggananController as CustomerPaketLanggananController;
use App\Http\Controllers\Kasir\BookingController as KasirBookingController;
use App\Http\Controllers\Kasir\DashboardController as KasirDashboardController;
use App\Http\Controllers\Admin\PaketLanggananController;
use App\Http\Controllers\Admin\PoinVoucherController;
use App\Http\Controllers\Customer\PoinController;
use App\Http\Controllers\Customer\RescheduleController;
use App\Http\Controllers\Admin\RescheduleController as AdminRescheduleController;
use App\Http\Controllers\Kasir\RescheduleController as KasirRescheduleController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Kasir\LaporanController as KasirLaporanController;
use App\Http\Controllers\Admin\PelangganController;
use App\Http\Controllers\Customer\PakaiSesiController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Auth;





Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
})->name('home');

Route::post('/logout', function () {
    Auth::guard('web')->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('login');
})->middleware('auth')->name('logout');

// Route::view('dashboard', 'dashboard')
//     ->middleware(['auth', 'verified'])
//     ->name('dashboard');

Route::get('/dashboard', function () {
    // Pastikan 'role' sesuai dengan nama kolom role di table users Anda
    // $role = auth()->user()->role; 
    return match (auth()->user()->role) {
        'Admin' => redirect()->route('admin.dashboard'),
        // 'Admin' => 'Tembus! Berarti masalahnya ada di Middleware Admin',
        'Kasir' => redirect()->route('kasir.dashboard'),
        'Customer' => redirect()->route('customer.beranda'),
        default => redirect('/'),
    };
    // Jika tidak ada role yang cocok (opsional)
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth','role:Admin'])->get('/test-admin', fn () => 'OK - Kamu Admin');
Route::middleware(['auth','role:Customer'])->get('/test-customer', fn () => 'OK - Kamu Customer');
Route::middleware(['auth','role:Kasir'])->get('/test-kasir', fn () => 'OK - Kamu Kasir');


// Admin
Route::middleware(['auth', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('kategori-olahraga', KategoriOlahragaController::class);
    Route::resource('lapangan', LapanganController::class);
    Route::resource('paket-langganan', PaketLanggananController::class);

    Route::prefix('poin-voucher')->name('poin-voucher.')->group(function () {
        Route::get('/', [PoinVoucherController::class, 'index'])->name('index');
        Route::post('/rasio', [PoinVoucherController::class, 'updateRasioPoin'])->name('rasio.update');

        Route::get('/voucher/create', [PoinVoucherController::class, 'voucherCreate'])->name('voucher.create');
        Route::post('/voucher', [PoinVoucherController::class, 'voucherStore'])->name('voucher.store');
        Route::get('/voucher/{voucher}/edit', [PoinVoucherController::class, 'voucherEdit'])->name('voucher.edit');
        Route::put('/voucher/{voucher}', [PoinVoucherController::class, 'voucherUpdate'])->name('voucher.update');
        Route::delete('/voucher/{voucher}', [PoinVoucherController::class, 'voucherDestroy'])->name('voucher.destroy');

        Route::get('/tukar-kuota/create', [PoinVoucherController::class, 'tukarKuotaCreate'])->name('tukar-kuota.create');
        Route::post('/tukar-kuota', [PoinVoucherController::class, 'tukarKuotaStore'])->name('tukar-kuota.store');
        Route::get('/tukar-kuota/{tukarKuota}/edit', [PoinVoucherController::class, 'tukarKuotaEdit'])->name('tukar-kuota.edit');
        Route::put('/tukar-kuota/{tukarKuota}', [PoinVoucherController::class, 'tukarKuotaUpdate'])->name('tukar-kuota.update');
        Route::delete('/tukar-kuota/{tukarKuota}', [PoinVoucherController::class, 'tukarKuotaDestroy'])->name('tukar-kuota.destroy');
    });

    Route::prefix('reschedule')->name('reschedule.')->group(function () {
        Route::get('/', [AdminRescheduleController::class, 'index'])->name('index');
        Route::post('/{rescheduleRequest}/approve', [AdminRescheduleController::class, 'approve'])->name('approve');
        Route::post('/{rescheduleRequest}/reject', [AdminRescheduleController::class, 'reject'])->name('reject');
    });
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/pelanggan', [PelangganController::class, 'index'])->name('pelanggan.index');
    Route::get('/pelanggan/{pelanggan}', [PelangganController::class, 'show'])->name('pelanggan.show');
 });
//  end Admin

// Customer
Route::middleware(['auth', 'role:Customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/beranda', [CustomerLapanganController::class, 'index'])->name('beranda');
    Route::get('/lapangan/{lapangan}/booking', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/lapangan/{lapangan}/booking', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/riwayat', [BookingController::class, 'riwayat'])->name('riwayat');
    Route::patch('/booking/{booking}/cancel', [BookingController::class, 'cancel'])->name('booking.cancel');

    // Langganan
    Route::get('/lapangan/{lapangan}/paket', [CustomerPaketLanggananController::class, 'index'])->name('paket.index');
    Route::get('/paket/{paketLangganan}/kuota', [CustomerPaketLanggananController::class, 'ambilKuota'])->name('paket.kuota.create');
    Route::post('/paket/{paketLangganan}/kuota', [CustomerPaketLanggananController::class, 'storeKuota'])->name('paket.kuota.store');

    // Jadwal Tetap
    Route::get('/paket/{paketLangganan}/jadwal-tetap', [CustomerPaketLanggananController::class, 'ambilJadwalTetap'])->name('paket.jadwal-tetap.create');
    Route::post('/paket/{paketLangganan}/jadwal-tetap', [CustomerPaketLanggananController::class, 'storeJadwalTetap'])->name('paket.jadwal-tetap.store');

    // POINT customer
    Route::get('/poin', [PoinController::class, 'index'])->name('poin.index');
    Route::post('/poin/voucher/{voucher}/tukar', [PoinController::class, 'tukarVoucher'])->name('poin.tukar-voucher');
    Route::post('/poin/kuota/{tukarKuota}/tukar', [PoinController::class, 'tukarKuota'])->name('poin.tukar-kuota');

    Route::get('/booking/{booking}/reschedule', [RescheduleController::class, 'create'])->name('reschedule.create');
    Route::post('/booking/{booking}/reschedule', [RescheduleController::class, 'store'])->name('reschedule.store');
    Route::get('/paket/{langganan}/pakai-sesi', [PakaiSesiController::class, 'create'])->name('paket.pakai-sesi.create');
Route::post('/paket/{langganan}/pakai-sesi', [PakaiSesiController::class, 'store'])->name('paket.pakai-sesi.store');
});
// End Customer

// KASIR
Route::middleware(['auth', 'role:Kasir'])->prefix('kasir')->name('kasir.')->group(function () {
    Route::get('/dashboard', [KasirDashboardController::class, 'index'])->name('dashboard');
    Route::get('/booking', [KasirBookingController::class, 'index'])->name('booking.index');
    Route::post('/pembayaran/{pembayaran}/konfirmasi', [KasirBookingController::class, 'konfirmasiPembayaran'])->name('pembayaran.konfirmasi');
    Route::delete('/pembayaran/{pembayaran}/tolak', [KasirBookingController::class, 'tolakDp'])->name('pembayaran.tolak');
    Route::patch('/booking/{booking}/approve', [KasirBookingController::class, 'approve'])->name('booking.approve');
    Route::patch('/booking/{booking}/reject', [KasirBookingController::class, 'reject'])->name('booking.reject');
    Route::get('/booking/manual', [KasirBookingController::class, 'create'])->name('booking.create');
    Route::post('/booking/manual', [KasirBookingController::class, 'store'])->name('booking.store');
    Route::get('/booking/slots', [KasirBookingController::class, 'slots'])->name('booking.slots');

    Route::prefix('reschedule')->name('reschedule.')->group(function () {
        Route::get('/', [KasirRescheduleController::class, 'index'])->name('index');
        Route::get('/{booking}', [KasirRescheduleController::class, 'create'])->name('create');
        Route::post('/{booking}', [KasirRescheduleController::class, 'store'])->name('store');
    });
    Route::get('/laporan', [KasirLaporanController::class, 'index'])->name('laporan.index');
});
// End Kasir

require __DIR__.'/auth.php';
