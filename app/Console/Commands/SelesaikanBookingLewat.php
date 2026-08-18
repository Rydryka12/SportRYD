<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Models\Booking;
use App\Models\Pengaturan;
use App\Models\PoinCustomer;
use App\Models\SesiLangganan;
use Illuminate\Support\Facades\DB;

// #[Signature('app:selesaikan-booking-lewat')]
// #[Description('Command description')]
class SelesaikanBookingLewat extends Command
{
    /**
     * Execute the console command.
     */

    protected $signature = 'booking:selesaikan-lewat';
    protected $description = 'Tandai booking yang jamnya sudah lewat sebagai Selesai, dan beri poin otomatis ke customer';
    public function handle() : void
    {
        $poinPerSesi = (int) Pengaturan::get('poin_per_sesi', 1);

        $bookingLewat = Booking::where('status', 'Akan Datang')
            ->where(function ($q) {
                $q->where('tanggal', '<', now()->toDateString())
                  ->orWhere(function ($q2) {
                      $q2->where('tanggal', now()->toDateString())
                         ->where('jam_selesai', '<=', now()->format('H:i:s'));
                  });
            })
            ->get();

        foreach ($bookingLewat as $booking) {
            DB::transaction(function () use ($booking, $poinPerSesi) {
                $booking->update(['status' => 'Selesai']);

                if ($booking->sesi_langganan_id) {
                    SesiLangganan::where('id', $booking->sesi_langganan_id)->update(['status' => 'Selesai']);
                }

                if ($poinPerSesi > 0) {
                    PoinCustomer::create([
                        'customer_id' => $booking->customer_id,
                        'booking_id' => $booking->id,
                        'jumlah_poin' => $poinPerSesi,
                        'jenis' => 'Masuk',
                        'keterangan' => 'Poin dari sesi selesai tanggal ' . $booking->tanggal,
                        'tanggal' => now()->toDateString(),
                    ]);
                }
            });
        }

        $this->info($bookingLewat->count() . ' booking ditandai selesai.');
    }
}
