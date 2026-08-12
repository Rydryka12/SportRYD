<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KategoriOlahraga;
use App\Models\Lapangan;

class LapanganController extends Controller
{
    public function index()
    {
        $lapanganList = Lapangan::with('kategoriOlahraga')->get();
        return view('admin.lapangan.index', compact('lapanganList'));
    }

    public function create()
    {
        $kategoriList = KategoriOlahraga::all();
        return view('admin.lapangan.create', compact('kategoriList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lapang' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori_olahraga,id',
            'deskripsi' => 'nullable|string',
            'tarif_per_jam' => 'required|integer|min:0',
            'status_aktif' => 'required|in:Aktif,Nonaktif',
        ]);

        Lapangan::create($validated);

        return redirect()->route('admin.lapangan.index')->with('success', 'Lapangan berhasil ditambahkan.');
    }

    public function edit(Lapangan $lapangan)
    {
        $kategoriList = KategoriOlahraga::all();
        return view('admin.lapangan.edit', compact('lapangan', 'kategoriList'));
    }

    public function update(Request $request, Lapangan $lapangan)
    {
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategori_olahraga,id',
            'nama_lapang' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tarif_per_jam' => 'required|integer|min:0',
            'status_aktif' => 'required|in:Aktif,Nonaktif',
        ]);

        $lapangan->update($validated);

        return redirect()->route('admin.lapangan.index')->with('success', 'Lapangan berhasil diperbarui.');
    }

    public function destroy(Lapangan $lapangan)
    {
        $lapangan->delete();

        return redirect()->route('admin.lapangan.index')->with('success', 'Lapangan berhasil dihapus.');
    }
}
