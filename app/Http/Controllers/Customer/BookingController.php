<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Lapangan;
use App\Models\Booking;
use App\Models\LanggananCustomer;
use App\Models\Pembayaran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function pilihJenis(Lapangan $lapangan)
    {
        return view('customer.booking.pilih-jenis', compact ('lapangan'));
    }

    // Create
    public function create(Request $request, Lapangan $lapangan)
    {
        $tanggal = $request->query('tanggal', now()->toDateString());

        $bookingHariItu = Booking::where('lapangan_id', $lapangan->id)
            ->where('tanggal', $tanggal)
            ->whereIn('status', ['Akan Datang', 'Selesai'])
            ->get(['jam_mulai', 'jam_selesai']);

        return view('customer.booking.create', compact('lapangan', 'tanggal', 'bookingHariItu'));
    }

    // Store
    public function store(Request $request, Lapangan $lapangan)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date|after_or_equal:today',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        DB::transaction(function () use ($validated, $lapangan) {
            // Kunci baris lapangan ini dulu -> semua request booking utk lapangan yg sama
            // diproses satu per satu (bukan bersamaan), baru dicek bentrok. Ini yang bikin
            // NFR-03 (anti bentrok jadwal) benar-benar kepegang.
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
                abort(409, 'Slot jam ini baru saja dipesan orang lain, coba pilih jam lain.');
            }

            // $durasiJam = Carbon::parse($validated['jam_selesai'])->diffInHours(Carbon::parse($validated['jam_mulai']));
            $durasiJam = abs(
                Carbon::parse($validated['jam_selesai'])->diffInHours(Carbon::parse($validated['jam_mulai']))
            );

            Booking::create([
                'customer_id' => auth()->id(),
                'lapangan_id' => $lapangan->id,
                'tanggal' => $validated['tanggal'],
                'jam_mulai' => $validated['jam_mulai'],
                'jam_selesai' => $validated['jam_selesai'],
                'status' => 'Akan Datang',
                'sumber' => 'Customer',
                'harga' => $durasiJam * $lapangan->tarif_per_jam,
            ]);
            Pembayaran::create([
                'booking_id' => $booking->id,
                'jumlah' => $booking->harga,
                'metode' => 'QRIS',
                'status' => 'Menunggu Konfirmasi',
            ]);
        });

        return redirect()->route('customer.riwayat')
            ->with('success', 'Booking berhasil! ' . $lapangan->nama_lapang . ' - ' . $validated['tanggal'] . '.');
    }

    // riwayat
    public function riwayat()
    {
        $bookingList = Booking::where('customer_id', auth()->id())
            ->with('lapangan')
            ->orderByDesc('tanggal')
            ->orderByDesc('jam_mulai')
            ->get();

        $paketAktifList = LanggananCustomer::where('customer_id', auth()->id())
        ->where('status', 'Aktif')
        ->with(['paketLangganan.kategoriOlahraga', 'lapangan'])
        ->get();

        return view('customer.booking.riwayat', compact('bookingList', 'paketAktifList'));
    }
}
