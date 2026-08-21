<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use App\Models\Pembayaran;
use App\Models\Pengaturan;
use App\Models\PoinCustomer;
use App\Models\SesiLangganan;
use Illuminate\Support\Facades\DB;

class SelesaikanBookingLewat extends Command
{
    protected $signature   = 'booking:selesaikan-lewat';
    protected $description = 'Tandai booking lewat sebagai Selesai (poin hanya jika DP sudah dikonfirmasi), '
                           . 'dan batalkan booking Akan Datang yang jam_mulai sudah tiba tapi DP belum dikonfirmasi.';

    public function handle(): void
    {
        $now          = now();
        $poinPerSesi  = (int) Pengaturan::get('poin_per_sesi', 1);
        $selesaiCount = 0;
        $batalCount   = 0;

        // ── 1. Batalkan booking yg jam_mulai sudah tiba tapi DP belum dikonfirmasi ──
        // Query: status = Akan Datang, jam sudah mulai (atau sudah lewat), TIDAK ada pembayaran dp_terkonfirmasi=true
        $tanggalSekarang = $now->toDateString();
        $jamSekarang = $now->format('H:i:s');
        
        $bookingBelumBayar = Booking::where('status', 'Akan Datang')
            ->where(function ($q) use ($tanggalSekarang, $jamSekarang) {
                // Sudah mulai hari ini
                $q->where('tanggal', $tanggalSekarang)
                  ->where('jam_mulai', '<=', $jamSekarang);
            })
            ->whereDoesntHave('pembayaran', function ($q) {
                $q->where('dp_terkonfirmasi', true);
            })
            ->get();

        foreach ($bookingBelumBayar as $booking) {
            DB::transaction(function () use ($booking) {
                $booking->update(['status' => 'Dibatalkan']);
                // Hapus record pembayaran pending yang belum dikonfirmasi agar tidak menggantung
                $booking->pembayaran()->where('status', 'Menunggu Konfirmasi')->delete();
            });
            $batalCount++;
        }

        // ── 2. Selesaikan booking yang jam_selesai sudah lewat ──
        // Hanya booking yg punya dp_terkonfirmasi=true yang dapat poin
        $bookingLewat = Booking::where('status', 'Akan Datang')
            ->where(function ($q) use ($tanggalSekarang, $jamSekarang) {
                $q->where('tanggal', '<', $tanggalSekarang)
                  ->orWhere(function ($q2) use ($tanggalSekarang, $jamSekarang) {
                      $q2->where('tanggal', $tanggalSekarang)
                         ->where('jam_selesai', '<=', $jamSekarang);
                  });
            })
            ->with(['pembayaran' => fn($q) => $q->where('dp_terkonfirmasi', true)])
            ->get();

        foreach ($bookingLewat as $booking) {
            DB::transaction(function () use ($booking, $poinPerSesi, $tanggalSekarang) {
                $booking->update(['status' => 'Selesai']);

                if ($booking->sesi_langganan_id) {
                    SesiLangganan::where('id', $booking->sesi_langganan_id)
                        ->update(['status' => 'Selesai']);
                }

                // Poin hanya untuk booking yang DP-nya benar-benar sudah dikonfirmasi
                $dpKonfirmasi = $booking->pembayaran->isNotEmpty();
                if ($poinPerSesi > 0 && $dpKonfirmasi) {
                    // Cegah duplikasi poin
                    $sudahAdaPoin = PoinCustomer::where('booking_id', $booking->id)
                        ->where('jenis', 'Masuk')
                        ->exists();

                    if (! $sudahAdaPoin) {
                        PoinCustomer::create([
                            'customer_id' => $booking->customer_id,
                            'booking_id'  => $booking->id,
                            'jumlah_poin' => $poinPerSesi,
                            'jenis'       => 'Masuk',
                            'keterangan'  => 'Poin dari sesi selesai tanggal ' . $booking->tanggal,
                            'tanggal'     => $tanggalSekarang,
                        ]);
                    }
                }
            });
            $selesaiCount++;
        }

        $this->info("{$selesaiCount} booking ditandai Selesai, {$batalCount} booking dibatalkan karena DP tidak dikonfirmasi.");
    }
}
