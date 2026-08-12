<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriOlahraga;
use App\Models\PaketLangganan;
use Illuminate\Http\Request;

class PaketLanggananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $kategoriList = KategoriOlahraga::all();
        $kategoriId = $request->query('kategori_id', $kategoriList->first()->id ?? null);

        $paketList = PaketLangganan::with('kategoriOlahraga')
            ->when($kategoriId, fn ($query) => $query->where('kategori_id', $kategoriId))
            ->get();

        return view('admin.paket-langganan.index', compact('paketList', 'kategoriList', 'kategoriId'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategoriList = KategoriOlahraga::all();
        return view('admin.paket-langganan.create', compact('kategoriList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategori_olahraga,id',
            'nama_paket' => 'required|string|max:255',
            'tipe_paket' => 'required|in:Kuota,Jadwal Tetap',
            'jumlah_sesi' => 'required|integer|min:1',
            'durasi_jam_per_sesi' => 'required|integer|min:1',
            'masa_berlaku_hari' => 'required|integer|min:1',
            'harga' => 'required|integer|min:0',
            'status_aktif' => 'required|in:Aktif,Nonaktif',
        ]);

        PaketLangganan::create($validated);

        return redirect()->route('admin.paket-langganan.index', ['kategori_id' => $validated['kategori_id']])
            ->with('success', 'Paket langganan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PaketLangganan $paketLangganan)
    {
        $kategoriList = KategoriOlahraga::all();
        return view('admin.paket-langganan.edit', compact('paketLangganan', 'kategoriList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PaketLangganan $paketLangganan)
    {
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategori_olahraga,id',
            'nama_paket' => 'required|string|max:255',
            'tipe_paket' => 'required|in:Kuota,Jadwal Tetap',
            'jumlah_sesi' => 'required|integer|min:1',
            'durasi_jam_per_sesi' => 'required|integer|min:1',
            'masa_berlaku_hari' => 'required|integer|min:1',
            'harga' => 'required|integer|min:0',
            'status_aktif' => 'required|in:Aktif,Nonaktif',
        ]);

        $paketLangganan->update($validated);

        return redirect()->route('admin.paket-langganan.index', ['kategori_id' => $validated['kategori_id']])
            ->with('success', 'Paket langganan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaketLangganan $paketLangganan)
    {
        $kategoriId = $paketLangganan->kategori_id;
        $paketLangganan->delete();

        return redirect()->route('admin.paket-langganan.index', ['kategori_id' => $kategoriId])
            ->with('success', 'Paket langganan berhasil dihapus.');
    
    }
}
