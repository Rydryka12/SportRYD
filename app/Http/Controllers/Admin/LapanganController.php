<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\KategoriOlahraga;
use App\Models\Lapangan;

class LapanganController extends Controller
{
    public function index()
    {
        $lapanganList = Lapangan::with('kategoriOlahraga')->get();
        $kategoriList = KategoriOlahraga::all();
        return view('admin.lapangan.index', compact('lapanganList', 'kategoriList'));
    }

    public function create()
    {
        $kategoriList = KategoriOlahraga::all();
        return view('admin.lapangan.create', compact('kategoriList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lapang'  => 'required|string|max:255',
            'kategori_id'  => 'required|exists:kategori_olahraga,id',
            'deskripsi'    => 'nullable|string',
            'tarif_per_jam'=> 'required|integer|min:0',
            'status_aktif' => 'required|in:Aktif,Nonaktif',
            'foto'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('lapangan', 'public');
        }

        Lapangan::create($validated);

        return redirect()->route('admin.lapangan.index')
            ->with('success', 'Lapangan berhasil ditambahkan.');
    }

    public function edit(Lapangan $lapangan)
    {
        $kategoriList = KategoriOlahraga::all();
        return view('admin.lapangan.edit', compact('lapangan', 'kategoriList'));
    }

    public function update(Request $request, Lapangan $lapangan)
    {
        $validated = $request->validate([
            'nama_lapang'  => 'required|string|max:255',
            'kategori_id'  => 'required|exists:kategori_olahraga,id',
            'deskripsi'    => 'nullable|string',
            'tarif_per_jam'=> 'required|integer|min:0',
            'status_aktif' => 'required|in:Aktif,Nonaktif',
            'foto'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($lapangan->foto) {
                Storage::disk('public')->delete($lapangan->foto);
            }
            $validated['foto'] = $request->file('foto')->store('lapangan', 'public');
        } else {
            // Jika tidak upload foto baru, jangan overwrite yang lama
            unset($validated['foto']);
        }

        $lapangan->update($validated);

        return redirect()->route('admin.lapangan.index')
            ->with('success', 'Lapangan berhasil diperbarui.');
    }

    public function destroy(Lapangan $lapangan)
    {
        // Hapus foto dari storage jika ada
        if ($lapangan->foto) {
            Storage::disk('public')->delete($lapangan->foto);
        }

        $lapangan->delete();

        return redirect()->route('admin.lapangan.index')
            ->with('success', 'Lapangan berhasil dihapus.');
    }
}
