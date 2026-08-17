<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KatalogTukarKuota;
use App\Models\KatalogVoucher;
use App\Models\KategoriOlahraga;
use App\Models\Pengaturan;
use Illuminate\Http\Request;

class PoinVoucherController extends Controller
{
    public function index()
    {
        $rasioPoin = Pengaturan::get('poin_per_sesi', 1);
        $voucherList = KatalogVoucher::with('kategoriOlahraga')->get();
        $tukarKuotaList = KatalogTukarKuota::with('kategoriOlahraga')->get();

        return view('admin.poin-voucher.index', compact('rasioPoin', 'voucherList', 'tukarKuotaList'));
    }

    public function updateRasioPoin(Request $request)
    {
        $validated = $request->validate(['poin_per_sesi' => 'required|integer|min:0']);
        Pengaturan::set('poin_per_sesi', $validated['poin_per_sesi']);

        return back()->with('success', 'Rasio poin berhasil diperbarui.');
    }

    // ---- Katalog Voucher ----

    public function voucherCreate()
    {
        $kategoriList = KategoriOlahraga::all();
        return view('admin.poin-voucher.voucher-create', compact('kategoriList'));
    }

    public function voucherStore(Request $request)
    {
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategori_olahraga,id',
            'nama_voucher' => 'required|string|max:255',
            'biaya_poin' => 'required|integer|min:1',
            'nilai_potongan' => 'required|integer|min:0',
            'masa_berlaku_hari' => 'required|integer|min:1',
            'status_aktif' => 'required|in:Aktif,Nonaktif',
        ]);

        KatalogVoucher::create($validated);

        return redirect()->route('admin.poin-voucher.index')->with('success', 'Voucher berhasil ditambahkan.');
    }

    public function voucherEdit(KatalogVoucher $voucher)
    {
        $kategoriList = KategoriOlahraga::all();
        return view('admin.poin-voucher.voucher-edit', compact('voucher', 'kategoriList'));
    }

    public function voucherUpdate(Request $request, KatalogVoucher $voucher)
    {
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategori_olahraga,id',
            'nama_voucher' => 'required|string|max:255',
            'biaya_poin' => 'required|integer|min:1',
            'nilai_potongan' => 'required|integer|min:0',
            'masa_berlaku_hari' => 'required|integer|min:1',
            'status_aktif' => 'required|in:Aktif,Nonaktif',
        ]);

        $voucher->update($validated);

        return redirect()->route('admin.poin-voucher.index')->with('success', 'Voucher berhasil diperbarui.');
    }

    public function voucherDestroy(KatalogVoucher $voucher)
    {
        $voucher->delete();

        return redirect()->route('admin.poin-voucher.index')->with('success', 'Voucher berhasil dihapus.');
    }

    // ---- Katalog Tukar Kuota ----

    public function tukarKuotaCreate()
    {
        $kategoriList = KategoriOlahraga::all();
        return view('admin.poin-voucher.tukar-kuota-create', compact('kategoriList'));
    }

    public function tukarKuotaStore(Request $request)
    {
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategori_olahraga,id',
            'biaya_poin' => 'required|integer|min:1',
            'jumlah_sesi_didapat' => 'required|integer|min:1',
            'status_aktif' => 'required|in:Aktif,Nonaktif',
        ]);

        KatalogTukarKuota::create($validated);

        return redirect()->route('admin.poin-voucher.index')->with('success', 'Rasio tukar kuota berhasil ditambahkan.');
    }

    public function tukarKuotaEdit(KatalogTukarKuota $tukarKuota)
    {
        $kategoriList = KategoriOlahraga::all();
        return view('admin.poin-voucher.tukar-kuota-edit', compact('tukarKuota', 'kategoriList'));
    }

    public function tukarKuotaUpdate(Request $request, KatalogTukarKuota $tukarKuota)
    {
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategori_olahraga,id',
            'biaya_poin' => 'required|integer|min:1',
            'jumlah_sesi_didapat' => 'required|integer|min:1',
            'status_aktif' => 'required|in:Aktif,Nonaktif',
        ]);

        $tukarKuota->update($validated);

        return redirect()->route('admin.poin-voucher.index')->with('success', 'Rasio tukar kuota berhasil diperbarui.');
    }

    public function tukarKuotaDestroy(KatalogTukarKuota $tukarKuota)
    {
        $tukarKuota->delete();

        return redirect()->route('admin.poin-voucher.index')->with('success', 'Rasio tukar kuota berhasil dihapus.');
    }
}
