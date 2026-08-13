<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\KategoriOlahraga;
use App\Models\Lapangan;
use Illuminate\Http\Request;

class CustomerLapanganController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil semua kategori untuk tombol filter
        $kategoriList = KategoriOlahraga::orderBy('nama_kategori')->get();

        // 2. Ambil SEMUA lapangan yang aktif beserta relasi kategorinya
        $lapanganList = Lapangan::with('kategoriOlahraga')
            ->where('status_aktif', 'Aktif')
            ->get();

        // 3. Kirim data ke view (Pastikan tidak ada compact 'kategoriId' di sini)
        return view('customer.lapangan.index', compact('kategoriList', 'lapanganList'));
    }
}
