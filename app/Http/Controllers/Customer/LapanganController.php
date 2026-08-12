<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\KategoriOlahraga;
use App\Models\Lapangan;
use Illuminate\Http\Request;

class LapanganController extends Controller
{
    public function index(Request $request)
    {
        $kategoriList = KategoriOlahraga::all();
        $kategoriId = $request->query('kategori_id');

        $lapanganList = Lapangan::where('status_aktif', 'Aktif')
            ->when($kategoriId, fn ($q) => $q->where('kategori_id', $kategoriId))
            ->with('kategoriOlahraga')
            ->get();

        return view('customer.lapangan.index', compact('lapanganList', 'kategoriList', 'kategoriId'));
    }
}
