<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // Tanggal yang dipilih (default hari ini)
        $tanggal = $request->input('tanggal', now()->toDateString());

        // ── Ringkasan hari terpilih ──────────────────────
        $pembayaranHariItu = Pembayaran::whereDate('created_at', $tanggal)
            ->where('status', 'Terkonfirmasi')
            ->with('booking.customer', 'booking.lapangan', 'dikonfirmasiOleh')
            ->latest()
            ->get();

        $totalHariItu           = $pembayaranHariItu->sum('jumlah');
        $jumlahTransaksiHariItu = $pembayaranHariItu->count();

        // ── Booking yang selesai di tanggal itu (untuk rekap sesi) ──
        $bookingSelesai = Booking::whereDate('tanggal', $tanggal)
            ->whereIn('status', ['Selesai', 'Akan Datang'])
            ->with('lapangan.kategoriOlahraga', 'customer')
            ->orderBy('jam_mulai')
            ->get();

        // ── Rekap per lapangan ──────────────────────────
        $rekapLapangan = $pembayaranHariItu->groupBy(fn($p) => $p->booking->lapangan->nama_lapang ?? '-')
            ->map(fn($group) => [
                'jumlah_transaksi' => $group->count(),
                'total'            => $group->sum('jumlah'),
            ]);

        // ── Booking dibatalkan hari ini ─────────────────
        $bookingDibatalkan = Booking::whereDate('updated_at', $tanggal)
            ->where('status', 'Dibatalkan')
            ->with('customer', 'lapangan')
            ->orderBy('jam_mulai')
            ->get();

        // ── Chart 7 hari terakhir ────────────────────────
        $chartMingguan = collect(range(6, 0))->map(function ($i) {
            $tgl   = now()->subDays($i)->toDateString();
            $total = Pembayaran::where('status', 'Terkonfirmasi')
                ->whereDate('created_at', $tgl)
                ->sum('jumlah');
            return ['tanggal' => $tgl, 'label' => now()->subDays($i)->translatedFormat('D, d M'), 'total' => $total];
        });

        $totalMinggu = $chartMingguan->sum('total');

        return view('kasir.laporan.index', compact(
            'tanggal',
            'pembayaranHariItu',
            'totalHariItu',
            'jumlahTransaksiHariItu',
            'bookingSelesai',
            'rekapLapangan',
            'bookingDibatalkan',
            'chartMingguan',
            'totalMinggu'
        ));
    }
}
