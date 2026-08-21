<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Lapangan;
use App\Models\Booking;
use App\Models\LanggananCustomer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PoinCustomer;

class BookingController extends Controller
{
    // Create — tampilkan grid slot jam
    public function create(Request $request, Lapangan $lapangan)
    {
        $tanggal = $request->query('tanggal', now()->toDateString());

        // Hanya 'Akan Datang' + 'Selesai' yang blokir slot.
        // 'Menunggu Approval' TIDAK memblokir slot — slot baru terkunci setelah kasir approve.
        $bookingHariItu = Booking::where('lapangan_id', $lapangan->id)
            ->where('tanggal', $tanggal)
            ->whereIn('status', ['Akan Datang', 'Selesai'])
            ->get(['jam_mulai', 'jam_selesai']);

        return view('customer.booking.create', compact('lapangan', 'tanggal', 'bookingHariItu'));
    }

    // Store — booking masuk dengan status 'Menunggu Approval', kasir yang approve
    public function store(Request $request, Lapangan $lapangan)
    {
        $validated = $request->validate([
            'tanggal'    => 'required|date|after_or_equal:today',
            'jam_mulai'  => 'required|date_format:H:i',
            'jam_selesai'=> 'required|date_format:H:i|after:jam_mulai',
        ]);

        DB::transaction(function () use ($validated, $lapangan) {
            Lapangan::where('id', $lapangan->id)->lockForUpdate()->first();

            // Cek bentrok hanya terhadap booking yang sudah diapprove (Akan Datang / Selesai)
            $bentrok = Booking::where('lapangan_id', $lapangan->id)
                ->where('tanggal', $validated['tanggal'])
                ->whereIn('status', ['Akan Datang', 'Selesai'])
                ->where(function ($q) use ($validated) {
                    $q->where('jam_mulai', '<', $validated['jam_selesai'])
                      ->where('jam_selesai', '>', $validated['jam_mulai']);
                })
                ->exists();

            if ($bentrok) {
                abort(409, 'Slot jam ini sudah terisi, coba pilih jam lain.');
            }

            $durasiJam = abs(
                Carbon::parse($validated['jam_selesai'])->diffInHours(Carbon::parse($validated['jam_mulai']))
            );

            // Status masuk 'Menunggu Approval' — kasir harus approve dulu
            // Tidak ada record Pembayaran sampai kasir approve
            Booking::create([
                'customer_id' => auth()->id(),
                'lapangan_id' => $lapangan->id,
                'tanggal'     => $validated['tanggal'],
                'jam_mulai'   => $validated['jam_mulai'],
                'jam_selesai' => $validated['jam_selesai'],
                'status'      => 'Menunggu Approval',
                'sumber'      => 'Customer',
                'harga'       => $durasiJam * $lapangan->tarif_per_jam,
            ]);
        });

        return redirect()->route('customer.riwayat')
            ->with('success', 'Permintaan booking ' . $lapangan->nama_lapang . ' berhasil dikirim! Menunggu konfirmasi kasir.');
    }

    // Cancel — bisa untuk 'Akan Datang' dan 'Menunggu Approval'
    public function cancel(Booking $booking)
    {
        abort_if($booking->customer_id !== auth()->id(), 403);
        abort_if(
            ! in_array($booking->status, ['Akan Datang', 'Menunggu Approval']),
            422,
            'Booking tidak bisa dibatalkan.'
        );

        $booking->update(['status' => 'Dibatalkan']);

        return redirect()->route('customer.riwayat')
            ->with('success', 'Booking ' . $booking->lapangan->nama_lapang . ' berhasil dibatalkan.');
    }

    // Riwayat
    public function riwayat()
    {
        $now = now();

        // Booking sedang berlangsung milik customer ini
        $sesiAktif = Booking::where('customer_id', auth()->id())
            ->where('status', 'Akan Datang')
            ->where('tanggal', $now->toDateString())
            ->where('jam_mulai', '<=', $now->format('H:i:s'))
            ->where('jam_selesai', '>', $now->format('H:i:s'))
            ->with('lapangan.kategoriOlahraga')
            ->first();

        $bookingList = Booking::where('customer_id', auth()->id())
            ->with('lapangan')
            ->orderByDesc('tanggal')
            ->orderByDesc('jam_mulai')
            ->get()
            ->map(function ($booking) use ($now) {
                // Tentukan apakah sedang berlangsung (computed, tidak disimpan ke DB)
                if (
                    $booking->status === 'Akan Datang'
                    && $booking->tanggal === $now->toDateString()
                    && $booking->jam_mulai <= $now->format('H:i:s')
                    && $booking->jam_selesai > $now->format('H:i:s')
                ) {
                    $booking->display_status = 'Sedang Berlangsung';
                } else {
                    $booking->display_status = $booking->status;
                }
                return $booking;
            });

        $paketAktifList = LanggananCustomer::where('customer_id', auth()->id())
            ->where('status', 'Aktif')
            ->with(['paketLangganan.kategoriOlahraga', 'lapangan'])
            ->get();

        $saldoMasuk  = PoinCustomer::where('customer_id', auth()->id())->where('jenis', 'Masuk')->sum('jumlah_poin');
        $saldoKeluar = PoinCustomer::where('customer_id', auth()->id())->where('jenis', 'Keluar')->sum('jumlah_poin');
        $saldoPoin   = $saldoMasuk - $saldoKeluar;

        return view('customer.booking.riwayat', compact('bookingList', 'paketAktifList', 'saldoPoin', 'sesiAktif', 'now'));
    }
}
