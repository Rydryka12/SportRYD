<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KategoriOlahraga;

class KategoriOlahragaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategoriList = KategoriOlahraga::withCount('lapangan')->get();
        return view('admin.kategori-olahraga.index', compact('kategoriList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.kategori-olahraga.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori_olahraga,nama_kategori',
            'deskripsi' => 'nullable|string|max:255',
        ]);

        KategoriOlahraga::create($validated);

        return redirect()->route('admin.kategori-olahraga.index')->with('success', 'Kategori olahraga berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    // public function show(string $id)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KategoriOlahraga $kategoriOlahraga)
    {

        return view('admin.kategori-olahraga.edit', [
            'kategori' => $kategoriOlahraga
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KategoriOlahraga $kategoriOlahraga)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori_olahraga,nama_kategori,' . $kategoriOlahraga->id,
            'deskripsi' => 'nullable|string|max:255',
        ]);

        $kategoriOlahraga->update($validated);

        return redirect()->route('admin.kategori-olahraga.index')->with('success', 'Kategori olahraga berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KategoriOlahraga $kategoriOlahraga)
    {
        if ($kategoriOlahraga->lapangan()->exists()) {
            return back()->with('error', 'Kategori ini masih dipakai lapangan, tidak bisa dihapus.');
        }

        $kategoriOlahraga->delete();

        return redirect()->route('admin.kategori-olahraga.index')->with('success', 'Kategori olahraga berhasil dihapus.');
    
    }
}
