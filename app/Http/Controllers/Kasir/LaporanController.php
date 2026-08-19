<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index()
    {
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

        return view('kasir.laporan.index', compact('totalHariIni', 'jumlahTransaksiHariIni', 'chartMingguan'));
    }
}
