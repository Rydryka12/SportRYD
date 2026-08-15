<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\Booking;
use App\Models\Lapangan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function index()
    {
        $pembayaranPending = Pembayaran::where('status', 'Menunggu Konfirmasi')
            ->with('booking.customer', 'booking.lapangan')
            ->orderBy('created_at')
            ->get();

        return view('kasir.booking.index', compact('pembayaranPending'));
    }

    public function konfirmasiPembayaran(Pembayaran $pembayaran)
    {
        $pembayaran->update([
            'status' => 'Terkonfirmasi',
            'dikonfirmasi_oleh' => auth()->id(),
        ]);

        return back()->with('success', 'Pembayaran berhasil dikonfirmasi.');
    }

    // Create
    public function create(Request $request)
    {
        $lapanganList = Lapangan::where('status_aktif', 'Aktif')->with('kategoriOlahraga')->get();

        $lapanganId = $request->query('lapangan_id');
        $tanggal = $request->query('tanggal', now()->toDateString());

        $bookingHariItu = collect();
        $lapanganTerpilih = null;

        if ($lapanganId) {
            $lapanganTerpilih = $lapanganList->firstWhere('id', (int) $lapanganId);

            if ($lapanganTerpilih) {
                $bookingHariItu = Booking::where('lapangan_id', $lapanganId)
                    ->where('tanggal', $tanggal)
                    ->whereIn('status', ['Akan Datang', 'Selesai'])
                    ->get(['jam_mulai', 'jam_selesai']);
            }
        }

        return view('kasir.booking.create', compact(
            'lapanganList', 'lapanganId', 'tanggal', 'bookingHariItu', 'lapanganTerpilih'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'lapangan_id' => 'required|exists:lapangan,id',
            'tanggal' => 'required|date|after_or_equal:today',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'metode_bayar' => 'required|in:Tunai,QRIS',
        ]);

        $lapangan = Lapangan::findOrFail($validated['lapangan_id']);

        // Cari akun pelanggan berdasar no HP; kalau belum ada, bikin baru (walk-in/telepon)
        $customer = User::where('no_hp', $validated['no_hp'])->where('role', 'Customer')->first();

        if (! $customer) {
            $customer = User::create([
                'name' => $validated['nama_pelanggan'],
                'email' => $validated['no_hp'] . '@walkin.sportryd.local', // placeholder, pelanggan walk-in blm tentu punya email
                'no_hp' => $validated['no_hp'],
                'password' => Hash::make(Str::random(16)),
                'role' => 'Customer',
                'status_akun' => 'Aktif',
            ]);
        }

        DB::transaction(function () use ($validated, $lapangan, $customer) {
            Lapangan::where('id', $lapangan->id)->lockForUpdate()->first();

            $bentrok = Booking::where('lapangan_id', $lapangan->id)
                ->where('tanggal', $validated['tanggal'])
                ->whereIn('status', ['Akan Datang', 'Selesai'])
                ->where(function ($q) use ($validated) {
                    $q->where('jam_mulai', '<', $validated['jam_selesai'])
                    ->where('jam_selesai', '>', $validated['jam_mulai']);
                })
                ->exists();

            if ($bentrok) {
                abort(409, 'Slot jam ini sudah dipesan, coba pilih jam lain.');
            }

            // FIX: bungkus abs() -> Carbon 3 bisa hasil minus tergantung urutan parameter,
            // sama kasusnya seperti bug harga minus di BookingController customer.
            $durasiJam = abs(
                Carbon::parse($validated['jam_selesai'])->diffInHours(Carbon::parse($validated['jam_mulai']))
            );

            $booking = Booking::create([
                'customer_id' => $customer->id,
                'lapangan_id' => $lapangan->id,
                'kasir_id' => auth()->id(),
                'tanggal' => $validated['tanggal'],
                'jam_mulai' => $validated['jam_mulai'],
                'jam_selesai' => $validated['jam_selesai'],
                'status' => 'Akan Datang',
                'sumber' => 'Kasir',
                'harga' => $durasiJam * $lapangan->tarif_per_jam,
            ]);

            Pembayaran::create([
                'booking_id' => $booking->id,
                'jumlah' => $booking->harga,
                'metode' => $validated['metode_bayar'],
                'status' => 'Terkonfirmasi', // Kasir terima bayarannya langsung di tempat
                'dikonfirmasi_oleh' => auth()->id(),
            ]);
        });

        return redirect()->route('kasir.booking.index')
            ->with('success', 'Booking manual berhasil dibuat untuk ' . $customer->name . '.');
    }
}