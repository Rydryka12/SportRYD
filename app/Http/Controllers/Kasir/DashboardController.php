<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Lapangan;
use App\Models\Pembayaran;
use App\Models\RescheduleRequest;
use Illuminate\Support\Facades\Artisan;

class DashboardController extends Controller
{
    public function index()
    {
        Artisan::call('booking:selesaikan-lewat');

        $now = now();

        // ── Stat cards ────────────────────────────────────
        $bookingHariIni    = Booking::whereDate('tanggal', $now->toDateString())
                                ->whereIn('status', ['Akan Datang', 'Selesai'])
                                ->count();

        $pendapatanHariIni = Pembayaran::whereDate('created_at', $now->toDateString())
                                ->where('status', 'Terkonfirmasi')
                                ->sum('jumlah');

        $pendingApproval   = Booking::where('status', 'Menunggu Approval')->count();

        $pendingPembayaran = Pembayaran::where('status', 'Menunggu Konfirmasi')->count();

        // ── Monitoring lapangan sekarang ────────────────────
        $lapanganList = Lapangan::where('status_aktif', 'Aktif')
            ->with('kategoriOlahraga')
            ->orderBy('nama_lapang')
            ->get();

        $sedangBerlangsung = Booking::where('status', 'Akan Datang')
            ->where('tanggal', $now->toDateString())
            ->where('jam_mulai', '<=', $now->format('H:i:s'))
            ->where('jam_selesai', '>', $now->format('H:i:s'))
            ->with('customer', 'lapangan')
            ->get()
            ->keyBy('lapangan_id');

        // ── Notif: DP yang jam mainnya < 30 menit lagi ───
        $dpMendekat = Pembayaran::where('status', 'Menunggu Konfirmasi')
            ->whereHas('booking', function ($q) use ($now) {
                $q->where('tanggal', $now->toDateString())
                  ->where('jam_mulai', '<=', $now->copy()->addMinutes(30)->format('H:i:s'))
                  ->where('jam_mulai', '>', $now->format('H:i:s'));
            })
            ->count();

        // ── Notif: Reschedule menunggu ────────────────────
        $pendingReschedule = RescheduleRequest::where('status', 'Menunggu')->count();

        // ── Laporan ringkas: 5 transaksi terakhir hari ini ─
        $transaksiTerakhir = Pembayaran::whereDate('created_at', $now->toDateString())
            ->where('status', 'Terkonfirmasi')
            ->with('booking.customer', 'booking.lapangan')
            ->latest()
            ->take(5)
            ->get();

        $totalHariIni = Pembayaran::whereDate('created_at', $now->toDateString())
            ->where('status', 'Terkonfirmasi')
            ->sum('jumlah');

        // ── Chart pendapatan 7 hari ────────────────────────
        $chartMingguan = collect(range(6, 0))->map(function ($i) {
            $tanggal = now()->subDays($i)->toDateString();
            $total   = Pembayaran::where('status', 'Terkonfirmasi')
                ->whereDate('created_at', $tanggal)
                ->sum('jumlah');
            return [
                'label' => now()->subDays($i)->translatedFormat('D'),
                'total' => $total,
            ];
        });

        return view('kasir.dashboard.index', compact(
            'now',
            'bookingHariIni',
            'pendapatanHariIni',
            'pendingApproval',
            'pendingPembayaran',
            'dpMendekat',
            'pendingReschedule',
            'lapanganList',
            'sedangBerlangsung',
            'transaksiTerakhir',
            'totalHariIni',
            'chartMingguan'
        ));
    }
}
