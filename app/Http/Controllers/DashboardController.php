<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Lapangan;
use App\Models\Pembayaran;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $lapanganAktif = Lapangan::where('status_aktif', 'Aktif')->count();
        $bookingHariIni = Booking::whereDate('tanggal', now()->toDateString())->count();
        $totalPelanggan = User::where('role', 'Customer')->count();
        $pendapatanHariIni = Pembayaran::whereDate('created_at', now()->toDateString())
            ->where('status', 'Terkonfirmasi')
            ->sum('jumlah');

        $chartMingguan = collect(range(6, 0))->map(function ($i) {
            $tanggal = now()->subDays($i)->toDateString();
            $total = Pembayaran::where('status', 'Terkonfirmasi')
                ->whereDate('created_at', $tanggal)
                ->sum('jumlah');
            return ['tanggal' => $tanggal, 'total' => $total];
        });

        return view('dashboard', compact(
            'lapanganAktif', 'bookingHariIni', 'totalPelanggan', 'pendapatanHariIni', 'chartMingguan'
        ));
    }
}
