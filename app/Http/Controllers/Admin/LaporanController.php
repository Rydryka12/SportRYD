<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lapangan;
use App\Models\LanggananCustomer;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // --- Kolom kiri: Tampilan Kasir (hari ini) ---
        $pembayaranHariIni = Pembayaran::whereDate('created_at', now()->toDateString())
            ->where('status', 'Terkonfirmasi')
            ->get();

        $totalHariIni = $pembayaranHariIni->sum('jumlah');
        $jumlahTransaksiHariIni = $pembayaranHariIni->count();

        $chartMingguan = collect(range(6, 0))->map(function ($i) {
            $tanggal = now()->subDays($i)->toDateString();
            $total = Pembayaran::where('status', 'Terkonfirmasi')
                ->whereDate('created_at', $tanggal)
                ->sum('jumlah');
            return ['tanggal' => $tanggal, 'total' => $total];
        });

        // --- Kolom kanan: Tampilan Admin (periode fleksibel) ---
        $periodeMulai = $request->query('mulai', now()->startOfMonth()->toDateString());
        $periodeSelesai = $request->query('selesai', now()->toDateString());
        $lapanganId = $request->query('lapangan_id');

        $pembayaranQuery = Pembayaran::where('status', 'Terkonfirmasi')
            ->whereBetween('created_at', [$periodeMulai . ' 00:00:00', $periodeSelesai . ' 23:59:59'])
            ->with('booking.lapangan');

        if ($lapanganId) {
            $pembayaranQuery->whereHas('booking', fn ($q) => $q->where('lapangan_id', $lapanganId));
        }

        $totalPeriode = $pembayaranQuery->sum('jumlah');

        $rekapPaket = LanggananCustomer::with('paketLangganan.kategoriOlahraga')
            ->whereBetween('created_at', [$periodeMulai . ' 00:00:00', $periodeSelesai . ' 23:59:59'])
            ->get()
            ->groupBy(fn ($l) => $l->paketLangganan->kategoriOlahraga->nama_kategori . ' — ' . $l->paketLangganan->tipe_paket)
            ->map(fn ($group) => [
                'jumlah' => $group->count(),
                'total' => $group->sum(fn ($l) => $l->paketLangganan->harga),
            ]);

        $lapanganList = Lapangan::all();

        return view('admin.laporan.index', compact(
            'totalHariIni', 'jumlahTransaksiHariIni', 'chartMingguan',
            'periodeMulai', 'periodeSelesai', 'lapanganId', 'lapanganList',
            'totalPeriode', 'rekapPaket'
        ));
    }
}
