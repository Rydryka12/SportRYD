<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\KatalogTukarKuota;
use App\Models\KatalogVoucher;
use App\Models\LanggananCustomer;
use App\Models\PoinCustomer;
use App\Models\VoucherCustomer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PoinController extends Controller
{
    public function index()
    {
        $saldoPoin = $this->hitungSaldo(auth()->id());

        $voucherKatalog = KatalogVoucher::where('status_aktif', 'Aktif')->with('kategoriOlahraga')->get();
        $voucherAktifSaya = VoucherCustomer::where('customer_id', auth()->id())
            ->where('status', 'Aktif')
            ->with('katalogVoucher')
            ->get();

        $tukarKuotaKatalog = KatalogTukarKuota::where('status_aktif', 'Aktif')->with('kategoriOlahraga')->get();

        // kategori yang customer ini punya Paket Kuota aktif -> syarat wajib buat tukar ke kuota
        $kategoriKuotaAktif = LanggananCustomer::where('customer_id', auth()->id())
            ->where('status', 'Aktif')
            ->whereHas('paketLangganan', fn ($q) => $q->where('tipe_paket', 'Kuota'))
            ->with('paketLangganan')
            ->get()
            ->pluck('paketLangganan.kategori_id')
            ->unique();

        return view('customer.poin.index', compact(
            'saldoPoin', 'voucherKatalog', 'voucherAktifSaya', 'tukarKuotaKatalog', 'kategoriKuotaAktif'
        ));
    }

    public function tukarVoucher(Request $request, KatalogVoucher $voucher)
    {
        $saldoPoin = $this->hitungSaldo(auth()->id());

        if ($saldoPoin < $voucher->biaya_poin) {
            return back()->with('error', 'Poin kamu belum cukup buat voucher ini.');
        }

        DB::transaction(function () use ($voucher) {
            VoucherCustomer::create([
                'customer_id' => auth()->id(),
                'voucher_id' => $voucher->id,
                'kode_voucher' => 'V' . strtoupper(Str::random(8)),
                'tanggal_tukar' => now()->toDateString(),
                'tanggal_expired' => now()->addDays($voucher->masa_berlaku_hari)->toDateString(),
                'status' => 'Aktif',
            ]);

            PoinCustomer::create([
                'customer_id' => auth()->id(),
                'jumlah_poin' => $voucher->biaya_poin,
                'jenis' => 'Keluar',
                'keterangan' => 'Tukar poin ke voucher: ' . $voucher->nama_voucher,
                'tanggal' => now()->toDateString(),
            ]);
        });

        return redirect()->route('customer.poin.index')->with('success', 'Berhasil tukar poin ke voucher ' . $voucher->nama_voucher . '!');
    }

    public function tukarKuota(Request $request, KatalogTukarKuota $tukarKuota)
    {
        $saldoPoin = $this->hitungSaldo(auth()->id());

        if ($saldoPoin < $tukarKuota->biaya_poin) {
            return back()->with('error', 'Poin kamu belum cukup buat penukaran ini.');
        }

        $langganan = LanggananCustomer::where('customer_id', auth()->id())
            ->where('status', 'Aktif')
            ->whereHas('paketLangganan', function ($q) use ($tukarKuota) {
                $q->where('tipe_paket', 'Kuota')->where('kategori_id', $tukarKuota->kategori_id);
            })
            ->first();

        if (! $langganan) {
            return back()->with('error', 'Kamu belum punya Paket Kuota aktif di kategori ' . $tukarKuota->kategoriOlahraga->nama_kategori . '.');
        }

        DB::transaction(function () use ($tukarKuota, $langganan) {
            $langganan->increment('sisa_sesi', $tukarKuota->jumlah_sesi_didapat);

            PoinCustomer::create([
                'customer_id' => auth()->id(),
                'langganan_customer_id' => $langganan->id,
                'jumlah_poin' => $tukarKuota->biaya_poin,
                'jenis' => 'Keluar',
                'keterangan' => 'Tukar poin ke +' . $tukarKuota->jumlah_sesi_didapat . ' sesi kuota ' . $tukarKuota->kategoriOlahraga->nama_kategori,
                'tanggal' => now()->toDateString(),
            ]);
        });

        return redirect()->route('customer.poin.index')->with('success', 'Berhasil tukar poin, sisa sesi bertambah ' . $tukarKuota->jumlah_sesi_didapat . '!');
    }

    private function hitungSaldo(int $customerId): int
    {
        $masuk = PoinCustomer::where('customer_id', $customerId)->where('jenis', 'Masuk')->sum('jumlah_poin');
        $keluar = PoinCustomer::where('customer_id', $customerId)->where('jenis', 'Keluar')->sum('jumlah_poin');

        return $masuk - $keluar;
    }
}
