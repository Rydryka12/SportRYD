<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Lapangan;
use App\Models\LanggananCustomer;
use App\Models\SesiLangganan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PakaiSesiController extends Controller
{
    public function create(Request $request, LanggananCustomer $langganan)
    {
        if ($langganan->customer_id !== auth()->id()
            || $langganan->status !== 'Aktif'
            || $langganan->paketLangganan->tipe_paket !== 'Kuota') {
            abort(403);
        }

        if ($langganan->sisa_sesi <= 0) {
            return redirect()->route('customer.riwayat')->with('error', 'Sisa sesi kamu sudah habis.');
        }

        $lapanganList = Lapangan::where('kategori_id', $langganan->paketLangganan->kategori_id)
            ->where('status_aktif', 'Aktif')
            ->get();

        $lapanganId = $request->query('lapangan_id');
        $tanggal = $request->query('tanggal', now()->toDateString());

        $bookingHariItu = collect();
        if ($lapanganId) {
            $bookingHariItu = Booking::where('lapangan_id', $lapanganId)
                ->where('tanggal', $tanggal)
                ->whereIn('status', ['Akan Datang', 'Selesai'])
                ->get(['jam_mulai', 'jam_selesai']);
        }

        $durasi = (int) $langganan->paketLangganan->durasi_jam_per_sesi;

        return view('customer.paket.pakai-sesi', compact('langganan', 'lapanganList', 'lapanganId', 'tanggal', 'bookingHariItu', 'durasi'));
    }
    public function store(Request $request, LanggananCustomer $langganan)
    {
        if ($langganan->customer_id !== auth()->id()
            || $langganan->status !== 'Aktif'
            || $langganan->paketLangganan->tipe_paket !== 'Kuota') {
            abort(403);
        }

        $durasi = (int) $langganan->paketLangganan->durasi_jam_per_sesi;

        $validated = $request->validate([
            'lapangan_id' => 'required|exists:lapangan,id',
            'tanggal' => 'required|date|after_or_equal:today',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        // Pastikan durasi yang dikirim sesuai paket (anti manipulasi form)
        $durasiDikirim = \Carbon\Carbon::parse($validated['jam_selesai'])
            ->diffInHours(\Carbon\Carbon::parse($validated['jam_mulai']));
        if ($durasiDikirim !== $durasi) {
            abort(422, 'Durasi sesi tidak sesuai dengan paket.');
        }

        DB::transaction(function () use ($validated, $langganan, $durasi) {
            Lapangan::where('id', $validated['lapangan_id'])->lockForUpdate()->first();

            $bentrok = Booking::where('lapangan_id', $validated['lapangan_id'])
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

            // re-check sisa_sesi di dalam transaction, jaga-jaga double klik
            $langganan->refresh();
            if ($langganan->sisa_sesi < $durasi) {
                abort(409, 'Sisa sesi kamu tidak cukup untuk durasi ' . $durasi . ' jam.');
            }

            $booking = Booking::create([
                'customer_id' => auth()->id(),
                'lapangan_id' => $validated['lapangan_id'],
                'tanggal' => $validated['tanggal'],
                'jam_mulai' => $validated['jam_mulai'],
                'jam_selesai' => $validated['jam_selesai'],
                'status' => 'Akan Datang',
                'sumber' => 'Customer',
                'harga' => 0,
            ]);

            $sesi = SesiLangganan::create([
                'langganan_customer_id' => $langganan->id,
                'booking_id' => $booking->id,
                'tanggal' => $validated['tanggal'],
                'status' => 'Akan Datang',
            ]);

            $booking->update(['sesi_langganan_id' => $sesi->id]);

            // Kurangi sisa_sesi sebesar durasi_jam_per_sesi (bukan selalu 1)
            $langganan->decrement('sisa_sesi', $durasi);
        });

        return redirect()->route('customer.riwayat')->with('success', 'Sesi berhasil dipakai! Sisa sesi berkurang ' . $durasi . '.');
    
    }
}
