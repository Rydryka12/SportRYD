<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Lapangan;
use App\Models\RescheduleRequest;
use App\Models\SesiLangganan;
use Illuminate\Support\Facades\DB;

class RescheduleController extends Controller
{
    public function index()
    {
        $requests = RescheduleRequest::where('status', 'Menunggu')
            ->with('booking.customer', 'booking.lapangan')
            ->orderBy('created_at')
            ->get();

        return view('admin.reschedule.index', compact('requests'));
    }

    public function approve(RescheduleRequest $rescheduleRequest)
    {
        $jadwalBaru = $rescheduleRequest->jadwal_baru_array;
        $booking = $rescheduleRequest->booking;

        DB::transaction(function () use ($rescheduleRequest, $booking, $jadwalBaru) {
            // Kunci baris lapangan ini dulu -> serialize pengecekan bentrok (NFR-03)
            Lapangan::where('id', $booking->lapangan_id)->lockForUpdate()->first();

            $bentrok = Booking::where('lapangan_id', $booking->lapangan_id)
                ->where('id', '!=', $booking->id)
                ->where('tanggal', $jadwalBaru['tanggal'])
                ->whereIn('status', ['Akan Datang', 'Selesai'])
                ->where(function ($q) use ($jadwalBaru) {
                    $q->where('jam_mulai', '<', $jadwalBaru['jam_selesai'])
                      ->where('jam_selesai', '>', $jadwalBaru['jam_mulai']);
                })
                ->exists();

            if ($bentrok) {
                abort(409, 'Jadwal baru ini sudah bentrok dengan booking lain. Tolak pengajuan ini, lalu minta pelanggan ajukan ulang.');
            }

            $booking->update([
                'tanggal' => $jadwalBaru['tanggal'],
                'jam_mulai' => $jadwalBaru['jam_mulai'],
                'jam_selesai' => $jadwalBaru['jam_selesai'],
                'status' => 'Akan Datang',
            ]);

            if ($booking->sesi_langganan_id) {
                SesiLangganan::where('id', $booking->sesi_langganan_id)->update([
                    'tanggal' => $jadwalBaru['tanggal'],
                ]);
            }

            $rescheduleRequest->update([
                'status' => 'Disetujui',
                'diproses_oleh' => auth()->id(),
            ]);
        });

        return back()->with('success', 'Reschedule disetujui, jadwal booking sudah diperbarui.');
    }

    public function reject(RescheduleRequest $rescheduleRequest)
    {
        $rescheduleRequest->update([
            'status' => 'Ditolak',
            'diproses_oleh' => auth()->id(),
        ]);

        $rescheduleRequest->booking->update(['status' => 'Akan Datang']);

        return back()->with('success', 'Pengajuan ditolak, booking kembali ke jadwal semula.');
    }
}
