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
use Illuminate\Support\Facades\Artisan;

class BookingController extends Controller
{
    public function index()
    {
        Artisan::call('booking:selesaikan-lewat');

        $now = now();

        // Booking menunggu approval dari customer
        $bookingPendingApproval = Booking::where('status', 'Menunggu Approval')
            ->with('customer', 'lapangan')
            ->orderBy('created_at')
            ->get();

        // Pembayaran menunggu konfirmasi DP
        $pembayaranPending = Pembayaran::where('status', 'Menunggu Konfirmasi')
            ->with('booking.customer', 'booking.lapangan')
            ->orderBy('created_at')
            ->get();

        return view('kasir.booking.index', compact(
            'bookingPendingApproval',
            'pembayaranPending',
            'now'
        ));
    }

    // Approve booking customer → status Akan Datang + buat record Pembayaran
    public function approve(Booking $booking)
    {
        abort_if($booking->status !== 'Menunggu Approval', 422, 'Booking tidak dalam status menunggu approval.');

        DB::transaction(function () use ($booking) {
            // Cek bentrok ulang saat approve (bisa saja slot sudah dipakai booking lain selama menunggu)
            Lapangan::where('id', $booking->lapangan_id)->lockForUpdate()->first();

            $bentrok = Booking::where('lapangan_id', $booking->lapangan_id)
                ->where('tanggal', $booking->tanggal)
                ->whereIn('status', ['Akan Datang', 'Selesai'])
                ->where('id', '!=', $booking->id)
                ->where(function ($q) use ($booking) {
                    $q->where('jam_mulai', '<', $booking->jam_selesai)
                      ->where('jam_selesai', '>', $booking->jam_mulai);
                })
                ->exists();

            if ($bentrok) {
                abort(409, 'Slot jam ini sudah terisi oleh booking lain, tidak bisa diapprove.');
            }

            $booking->update([
                'status'   => 'Akan Datang',
                'kasir_id' => auth()->id(),
            ]);

            // Buat record pembayaran setelah diapprove
            Pembayaran::create([
                'booking_id' => $booking->id,
                'jumlah'     => $booking->harga,
                'metode'     => 'Cash',
                'status'     => 'Menunggu Konfirmasi',
            ]);
        });

        return back()->with('success', 'Booking ' . $booking->customer->name . ' berhasil diapprove.');
    }

    // Reject booking customer → status Dibatalkan
    public function reject(Booking $booking)
    {
        abort_if($booking->status !== 'Menunggu Approval', 422, 'Booking tidak dalam status menunggu approval.');

        $booking->update(['status' => 'Dibatalkan']);

        return back()->with('success', 'Booking ' . $booking->customer->name . ' ditolak.');
    }

    public function konfirmasiPembayaran(Pembayaran $pembayaran)
    {
        $pembayaran->update([
            'status'            => 'Terkonfirmasi',
            'dp_terkonfirmasi'  => true,
            'dikonfirmasi_oleh' => auth()->id(),
        ]);

        return back()->with('success', 'Pembayaran DP berhasil dikonfirmasi. Waktu main customer akan berjalan otomatis.');
    }

    // Tolak / batalkan DP — booking kembali dibatalkan
    public function tolakDp(Pembayaran $pembayaran)
    {
        DB::transaction(function () use ($pembayaran) {
            $pembayaran->booking()->update(['status' => 'Dibatalkan']);
            $pembayaran->delete();
        });

        return back()->with('success', 'DP ditolak dan booking ' . $pembayaran->booking->customer->name . ' dibatalkan.');
    }

    // JSON endpoint: slot terisi untuk lapangan + tanggal tertentu (dipakai Alpine fetch di form booking manual)
    public function slots(Request $request)
    {
        $lapanganId = $request->query('lapangan_id');
        $tanggal    = $request->query('tanggal', now()->toDateString());

        if (! $lapanganId) {
            return response()->json(['slot_terisi' => [], 'lapangan' => null]);
        }

        $lapangan = Lapangan::find($lapanganId);
        if (! $lapangan) {
            return response()->json(['slot_terisi' => [], 'lapangan' => null]);
        }

        $bookings = Booking::where('lapangan_id', $lapanganId)
            ->where('tanggal', $tanggal)
            ->whereIn('status', ['Akan Datang', 'Selesai'])
            ->when($request->query('exclude'), fn($q, $ex) => $q->where('id', '!=', $ex))
            ->get(['jam_mulai', 'jam_selesai']);

        $slotTerisi = [];
        foreach ($bookings as $b) {
            $mulai   = Carbon::parse($b->jam_mulai)->hour;
            $selesai = Carbon::parse($b->jam_selesai)->hour;
            for ($j = $mulai; $j < $selesai; $j++) {
                $slotTerisi[] = $j;
            }
        }

        return response()->json([
            'slot_terisi' => $slotTerisi,
            'lapangan'    => [
                'id'            => $lapangan->id,
                'nama_lapang'   => $lapangan->nama_lapang,
                'tarif_per_jam' => $lapangan->tarif_per_jam,
                'kategori'      => $lapangan->kategoriOlahraga->nama_kategori ?? '',
            ],
        ]);
    }

    // Create — form booking manual kasir
    public function create(Request $request)
    {
        $lapanganList     = Lapangan::where('status_aktif', 'Aktif')->with('kategoriOlahraga')->get();
        $lapanganId       = $request->query('lapangan_id');
        $tanggal          = $request->query('tanggal', now()->toDateString());
        $bookingHariItu   = collect();
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

    // Store — booking manual kasir langsung Akan Datang + pembayaran Terkonfirmasi
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'no_hp'          => 'required|string|max:20',
            'lapangan_id'    => 'required|exists:lapangan,id',
            'tanggal'        => 'required|date|after_or_equal:today',
            'jam_mulai'      => 'required|date_format:H:i',
            'jam_selesai'    => 'required|date_format:H:i|after:jam_mulai',
            'metode_bayar'   => 'required|in:Cash',
        ]);

        $lapangan = Lapangan::findOrFail($validated['lapangan_id']);

        $customer = User::where('no_hp', $validated['no_hp'])->where('role', 'Customer')->first();
        if (! $customer) {
            $customer = User::create([
                'name'        => $validated['nama_pelanggan'],
                'email'       => $validated['no_hp'] . '@walkin.sportryd.local',
                'no_hp'       => $validated['no_hp'],
                'password'    => Hash::make(Str::random(16)),
                'role'        => 'Customer',
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

            $durasiJam = abs(
                Carbon::parse($validated['jam_selesai'])->diffInHours(Carbon::parse($validated['jam_mulai']))
            );

            $booking = Booking::create([
                'customer_id' => $customer->id,
                'lapangan_id' => $lapangan->id,
                'kasir_id'    => auth()->id(),
                'tanggal'     => $validated['tanggal'],
                'jam_mulai'   => $validated['jam_mulai'],
                'jam_selesai' => $validated['jam_selesai'],
                'status'      => 'Akan Datang', // Kasir langsung Akan Datang, tidak perlu approval
                'sumber'      => 'Kasir',
                'harga'       => $durasiJam * $lapangan->tarif_per_jam,
            ]);

            Pembayaran::create([
                'booking_id'        => $booking->id,
                'jumlah'            => $booking->harga,
                'metode'            => $validated['metode_bayar'],
                'status'            => 'Terkonfirmasi',
                'dp_terkonfirmasi'  => true,
                'dikonfirmasi_oleh' => auth()->id(),
            ]);
        });

        return redirect()->route('kasir.booking.index')
            ->with('success', 'Booking manual berhasil dibuat untuk ' . $customer->name . '.');
    }
}
