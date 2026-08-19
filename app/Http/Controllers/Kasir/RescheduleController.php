<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Lapangan;
use App\Models\SesiLangganan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RescheduleController extends Controller
{
    public function index(Request $request)
    {
        $tanggal = $request->query('tanggal', now()->toDateString());

        $bookingList = Booking::where('status', 'Akan Datang')
            ->where('tanggal', $tanggal)
            ->with('customer', 'lapangan')
            ->orderBy('jam_mulai')
            ->get();

        return view('kasir.reschedule.index', compact('bookingList', 'tanggal'));
    }

    public function create(Request $request, Booking $booking)
    {
        if ($booking->status !== 'Akan Datang') {
            abort(403, 'Booking ini tidak bisa di-reschedule.');
        }

        $tanggalBaru = $request->query('tanggal', $booking->tanggal);

        $bookingHariItu = Booking::where('lapangan_id', $booking->lapangan_id)
            ->where('tanggal', $tanggalBaru)
            ->where('id', '!=', $booking->id)
            ->whereIn('status', ['Akan Datang', 'Selesai'])
            ->get(['jam_mulai', 'jam_selesai']);

        $slotTerisi = [];
        foreach ($bookingHariItu as $b) {
            $mulai   = \Carbon\Carbon::parse($b->jam_mulai)->hour;
            $selesai = \Carbon\Carbon::parse($b->jam_selesai)->hour;
            for ($j = $mulai; $j < $selesai; $j++) {
                $slotTerisi[] = $j;
            }
        }

        return view('kasir.reschedule.create', compact('booking', 'tanggalBaru', 'bookingHariItu', 'slotTerisi'));
    }

    public function store(Request $request, Booking $booking)
    {
        if ($booking->status !== 'Akan Datang') {
            abort(403);
        }

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        DB::transaction(function () use ($booking, $validated) {
            Lapangan::where('id', $booking->lapangan_id)->lockForUpdate()->first();

            $bentrok = Booking::where('lapangan_id', $booking->lapangan_id)
                ->where('id', '!=', $booking->id)
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

            $booking->update([
                'tanggal' => $validated['tanggal'],
                'jam_mulai' => $validated['jam_mulai'],
                'jam_selesai' => $validated['jam_selesai'],
            ]);

            // Kalau bagian dari Paket Langganan, cuma sesi INI yang tanggalnya berubah,
            // sesi lain di langganan_customer_id yang sama nggak disentuh sama sekali.
            if ($booking->sesi_langganan_id) {
                SesiLangganan::where('id', $booking->sesi_langganan_id)->update([
                    'tanggal' => $validated['tanggal'],
                ]);
            }
        });

        return redirect()->route('kasir.reschedule.index')->with('success', 'Jadwal booking berhasil diubah.');
    }
}
