<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PoinCustomer;
use App\Models\User;
use App\Models\Booking;
use App\Models\LanggananCustomer;
use App\Models\VoucherCustomer;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $pelangganList = User::where('role', 'Customer')
            ->when($search, function ($q) use ($search) {
                $q->where(function ($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%")
                       ->orWhere('no_hp', 'like', "%{$search}%");
                });
            })
            ->withCount(['langgananCustomer as paket_aktif_count' => function ($q) {
                $q->where('status', 'Aktif');
            }])
            ->get()
            ->map(function ($user) {
                $masuk = PoinCustomer::where('customer_id', $user->id)->where('jenis', 'Masuk')->sum('jumlah_poin');
                $keluar = PoinCustomer::where('customer_id', $user->id)->where('jenis', 'Keluar')->sum('jumlah_poin');
                $user->saldo_poin = $masuk - $keluar;
                return $user;
            });

        return view('admin.pelanggan.index', compact('pelangganList', 'search'));
    }
    public function show(User $pelanggan)
    {
        if ($pelanggan->role !== 'Customer') {
            abort(404);
        }

        $masuk = PoinCustomer::where('customer_id', $pelanggan->id)->where('jenis', 'Masuk')->sum('jumlah_poin');
        $keluar = PoinCustomer::where('customer_id', $pelanggan->id)->where('jenis', 'Keluar')->sum('jumlah_poin');
        $saldoPoin = $masuk - $keluar;

        $bookingList = Booking::where('customer_id', $pelanggan->id)
            ->with('lapangan')
            ->orderByDesc('tanggal')
            ->orderByDesc('jam_mulai')
            ->get();

        $poinHistori = PoinCustomer::where('customer_id', $pelanggan->id)
            ->orderByDesc('tanggal')
            ->orderByDesc('created_at')
            ->get();

        $voucherList = VoucherCustomer::where('customer_id', $pelanggan->id)
            ->with('katalogVoucher')
            ->orderByDesc('tanggal_tukar')
            ->get();

        $langgananList = LanggananCustomer::where('customer_id', $pelanggan->id)
            ->with('paketLangganan.kategoriOlahraga', 'lapangan')
            ->orderByDesc('tanggal_mulai')
            ->get();

        return view('admin.pelanggan.show', compact(
            'pelanggan', 'saldoPoin', 'bookingList', 'poinHistori', 'voucherList', 'langgananList'
        ));
    }
}
