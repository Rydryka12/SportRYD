<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\RescheduleRequest;
use Illuminate\Http\Request;

class RescheduleController extends Controller
{
    public function create(Booking $booking, Request $request)
    {
        if ($booking->customer_id !== auth()->id() || $booking->status !== 'Akan Datang') {
            abort(403);
        }

        $lapangan = $booking->lapangan;
        $tanggal = $request->query('tanggal', $booking->tanggal);

        // Ambil booking lain di lapangan yg sama & tanggal yg dipilih, exclude booking ini sendiri
        $bookingHariItu = Booking::where('lapangan_id', $lapangan->id)
            ->where('tanggal', $tanggal)
            ->where('id', '!=', $booking->id)
            ->whereIn('status', ['Akan Datang', 'Selesai'])
            ->get(['jam_mulai', 'jam_selesai']);

            // Durasi dikunci sama seperti booking asli, misal 2 jam -> reschedule tetap 2 jam
        $durasiAsli = abs(
            \Carbon\Carbon::parse($booking->jam_selesai)->diffInHours(\Carbon\Carbon::parse($booking->jam_mulai))
        );
        return view('customer.reschedule.create', compact('booking', 'lapangan', 'tanggal', 'bookingHariItu', 'durasiAsli'));
    }

    public function store(Request $request, Booking $booking)
    {
        if ($booking->customer_id !== auth()->id() || $booking->status !== 'Akan Datang') {
            abort(403);
        }

        $validated = $request->validate([
            'tanggal_baru' => 'required|date|after_or_equal:today',
            'jam_mulai_baru' => 'required|date_format:H:i',
            'jam_selesai_baru' => 'required|date_format:H:i|after:jam_mulai_baru',
            'alasan' => 'nullable|string|max:255',
        ]);

        RescheduleRequest::create([
            'booking_id' => $booking->id,
            'diajukan_oleh' => auth()->id(),
            'status' => 'Menunggu',
            'jadwal_baru' => json_encode([
                'tanggal' => $validated['tanggal_baru'],
                'jam_mulai' => $validated['jam_mulai_baru'],
                'jam_selesai' => $validated['jam_selesai_baru'],
                'alasan' => $validated['alasan'] ?? null,
            ]),
        ]);

        $booking->update(['status' => 'Menunggu Approval Reschedule']);

        return redirect()->route('customer.riwayat')
            ->with('success', 'Pengajuan reschedule terkirim, menunggu persetujuan Admin.');
    }
}
