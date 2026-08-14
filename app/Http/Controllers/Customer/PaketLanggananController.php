<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Lapangan;
use App\Models\Booking;
use App\Models\LanggananCustomer;
use App\Models\PaketLangganan;
use App\Models\SesiLangganan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaketLanggananController extends Controller
{
    private array $hariMap = [
        'Senin' => 1, 'Selasa' => 2, 'Rabu' => 3, 'Kamis' => 4,
        'Jumat' => 5, 'Sabtu' => 6, 'Minggu' => 7,
    ];

    public function index(Lapangan $lapangan)
    {
        $paketList = PaketLangganan::where('kategori_id', $lapangan->kategori_id)
            ->where('status_aktif', 'Aktif')
            ->get();

        return view('customer.paket.index', compact('lapangan', 'paketList'));
    }

    public function ambilKuota(PaketLangganan $paketLangganan)
    {
        if ($paketLangganan->tipe_paket !== 'Kuota') {
            return redirect()->back()->with('error', 'Paket ini bukan tipe Kuota.');
        }

        return view('customer.paket.ambil-kuota', compact('paketLangganan'));
    }

    public function storeKuota(Request $request, PaketLangganan $paketLangganan)
    {
        if ($paketLangganan->tipe_paket !== 'Kuota') {
            abort(422, 'Paket ini bukan tipe Kuota.');
        }

        LanggananCustomer::create([
            'customer_id' => auth()->id(),
            'paket_id' => $paketLangganan->id,
            'sisa_sesi' => $paketLangganan->jumlah_sesi,
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_berakhir' => now()->addDays($paketLangganan->masa_berlaku_hari)->toDateString(),
            'status' => 'Aktif',
        ]);

        return redirect()->route('customer.riwayat')
            ->with('success', 'Paket ' . $paketLangganan->nama_paket . ' berhasil diambil!');
    }

    public function ambilJadwalTetap(Request $request, PaketLangganan $paketLangganan)
    {
        if ($paketLangganan->tipe_paket !== 'Jadwal Tetap') {
            return redirect()->back()->with('error', 'Paket ini bukan tipe Jadwal Tetap.');
        }

        $lapanganList = Lapangan::where('kategori_id', $paketLangganan->kategori_id)
            ->where('status_aktif', 'Aktif')
            ->get();

        // Ambil semua booking existing (status aktif) untuk lapangan2 kategori ini,
        // dikirim sekali sebagai JSON supaya preview bentrok dihitung instan di Alpine.js
        // tanpa perlu reload / request tiap ganti pilihan.
        $horizon = now()->addMonths(4)->toDateString();

        $bookingTerpakai = Booking::whereIn('lapangan_id', $lapanganList->pluck('id'))
            ->whereBetween('tanggal', [now()->toDateString(), $horizon])
            ->whereIn('status', ['Akan Datang', 'Selesai'])
            ->get(['lapangan_id', 'tanggal', 'jam_mulai', 'jam_selesai'])
            ->groupBy('lapangan_id')
            ->map(function ($items) {
                return $items->map(fn ($b) => [
                    'tanggal' => $b->tanggal,
                    'jam_mulai' => substr($b->jam_mulai, 0, 5),
                    'jam_selesai' => substr($b->jam_selesai, 0, 5),
                ])->values();
            });

        return view('customer.paket.ambil-jadwal-tetap', compact(
            'paketLangganan', 'lapanganList', 'bookingTerpakai'
        ));
    }

    public function storeJadwalTetap(Request $request, PaketLangganan $paketLangganan)
    {
        if ($paketLangganan->tipe_paket !== 'Jadwal Tetap') {
            abort(422, 'Paket ini bukan tipe Jadwal Tetap.');
        }

        $validated = $request->validate([
            'lapangan_id' => 'required|exists:lapangan,id',
            'hari' => 'required|array|min:1',
            'hari.*' => 'in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai' => 'required|date_format:H:i',
        ]);

        $jamSelesai = Carbon::parse($validated['jam_mulai'])
            ->addHours($paketLangganan->durasi_jam_per_sesi)
            ->format('H:i');

        $tanggalList = $this->generateTanggalRutinMultiHari($validated['hari'], $paketLangganan->jumlah_sesi);

        return DB::transaction(function () use ($validated, $paketLangganan, $jamSelesai, $tanggalList) {
            // Kunci baris lapangan ini dulu -> dua orang gak bisa daftar Jadwal Tetap
            // di lapangan+jam yang tumpang tindih secara bersamaan (NFR-03)
            Lapangan::where('id', $validated['lapangan_id'])->lockForUpdate()->first();

            foreach ($tanggalList as $tanggal) {
                $bentrok = Booking::where('lapangan_id', $validated['lapangan_id'])
                    ->where('tanggal', $tanggal->toDateString())
                    ->whereIn('status', ['Akan Datang', 'Selesai'])
                    ->where(function ($q) use ($validated, $jamSelesai) {
                        $q->where('jam_mulai', '<', $jamSelesai)
                          ->where('jam_selesai', '>', $validated['jam_mulai']);
                    })
                    ->exists();

                if ($bentrok) {
                    abort(409, 'Slot tanggal ' . $tanggal->translatedFormat('d M Y') . ' sudah tidak tersedia, coba ganti lapangan/hari/jam.');
                }
            }

            $langganan = LanggananCustomer::create([
                'customer_id' => auth()->id(),
                'paket_id' => $paketLangganan->id,
                'lapangan_id' => $validated['lapangan_id'],
                'hari_dalam_minggu' => json_encode($validated['hari']), // simpan multi-hari sbg JSON
                'jam_mulai' => $validated['jam_mulai'],
                'jam_selesai' => $jamSelesai,
                'sisa_sesi' => 0, // tidak dipakai utk tipe Jadwal Tetap
                'tanggal_mulai' => $tanggalList[0]->toDateString(),
                'tanggal_berakhir' => end($tanggalList)->toDateString(),
                'status' => 'Aktif',
            ]);

            foreach ($tanggalList as $tanggal) {
                $booking = Booking::create([
                    'customer_id' => auth()->id(),
                    'lapangan_id' => $validated['lapangan_id'],
                    'tanggal' => $tanggal->toDateString(),
                    'jam_mulai' => $validated['jam_mulai'],
                    'jam_selesai' => $jamSelesai,
                    'status' => 'Akan Datang',
                    'sumber' => 'Customer',
                    'harga' => 0, // sudah dibayar di muka lewat paket
                ]);

                $sesi = SesiLangganan::create([
                    'langganan_customer_id' => $langganan->id,
                    'booking_id' => $booking->id,
                    'tanggal' => $tanggal->toDateString(),
                    'status' => 'Akan Datang',
                ]);

                $booking->update(['sesi_langganan_id' => $sesi->id]);
            }

            return redirect()->route('customer.riwayat')
                ->with('success', 'Paket ' . $paketLangganan->nama_paket . ' berhasil diambil, jadwal rutin sudah terkunci!');
        });
    }

    /**
     * Generate tanggal ke depan yang jatuh pada salah satu hari yang dipilih,
     * bergantian secara kronologis (bukan semua hari-A dulu baru hari-B).
     * Contoh: pilih Selasa & Jumat -> Selasa, Jumat, Selasa, Jumat, dst.
     */
    private function generateTanggalRutinMultiHari(array $hariList, int $jumlahSesi): array
    {
        $targetIsoList = collect($hariList)->map(fn ($h) => $this->hariMap[$h])->all();

        $hasil = [];
        $tanggal = now()->copy();
        $pengaman = 0;

        while (count($hasil) < $jumlahSesi && $pengaman < 400) {
            $tanggal = $tanggal->copy()->addDay();
            $pengaman++;

            if (in_array($tanggal->dayOfWeekIso, $targetIsoList)) {
                $hasil[] = $tanggal->copy();
            }
        }

        return $hasil;
    }
}
