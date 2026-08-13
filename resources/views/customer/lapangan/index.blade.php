@extends('layouts.customer')

@section('content')
<style>
    /* Menyembunyikan elemen sebelum Alpine.js siap (Mencegah kedip) */
    [x-cloak] { display: none !important; }

    /* --- WARNA UTAMA --- */
    .text-navy { color: #0f172a; }
    .text-orange { color: #f97316; }

    /* --- HERO SECTION --- */
    .hero-section {
        background: radial-gradient(circle at center top, #1e293b 0%, #0f172a 100%);
        border-radius: 1.5rem;
        padding: 5rem 2rem;
        margin-bottom: 3rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
    }
    .hero-badge {
        background-color: rgba(249, 115, 22, 0.1);
        color: #d1d5db;
        border: 1px solid rgba(249, 115, 22, 0.2);
        border-radius: 50px;
        padding: 0.4rem 1.2rem;
        font-size: 0.85rem;
        font-weight: 500;
        display: inline-block;
        margin-bottom: 1.5rem;
    }
    .hero-title {
        font-size: 2.75rem;
        font-weight: 800;
        color: white;
        line-height: 1.2;
        margin-bottom: 1rem;
    }
    .hero-subtitle {
        color: #94a3b8;
        font-size: 1rem;
        max-width: 700px;
        margin: 0 auto 2.5rem auto;
        line-height: 1.6;
    }

    /* --- SEARCH BAR --- */
    .hero-search {
        max-width: 600px;
        margin: 0 auto;
        position: relative;
    }
    .hero-search input {
        width: 100%;
        padding: 1rem 1.5rem 1rem 3rem;
        border-radius: 50px;
        border: none;
        font-size: 1rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }
    .hero-search input:focus {
        outline: none;
        box-shadow: 0 0 0 4px rgba(249, 115, 22, 0.3);
    }
    .hero-search i {
        position: absolute;
        left: 1.2rem;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        font-size: 1.2rem;
    }

    /* --- FILTER PILLS --- */
    .filter-pill {
        border-radius: 50px;
        padding: 0.5rem 1.25rem;
        font-weight: 600;
        font-size: 0.9rem;
        border: 1px solid #e5e7eb;
        background: white;
        color: #4b5563;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .filter-pill:hover {
        border-color: #cbd5e1;
        background-color: #f8fafc;
    }
    .filter-pill.active {
        background-color: #0f172a; /* Dark Navy */
        color: white;
        border-color: #0f172a;
    }

    /* --- CARDS --- */
    .lapangan-card {
        border-radius: 1rem;
        border: 1px solid #eef0f2;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .lapangan-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
    }
    .badge-kategori {
        background-color: #eaf1fb;
        color: #0f172a;
        font-weight: 600;
        border-radius: 50px;
        padding: 0.4rem 0.9rem;
        font-size: 0.75rem;
    }
    .badge-status {
        background-color: #dcfce7;
        color: #166534;
        font-weight: 600;
        border-radius: 50px;
        padding: 0.4rem 0.9rem;
        font-size: 0.75rem;
    }
    .badge-status-dot {
        width: 6px; height: 6px;
        background-color: #16a34a;
        border-radius: 50%;
        display: inline-block;
        margin-right: 4px;
    }
    .btn-orange {
        background-color: #f97316;
        color: white;
        border: none;
        font-weight: 600;
        transition: background-color 0.2s ease;
    }
    .btn-orange:hover {
        background-color: #ea580c;
        color: white;
    }
</style>

<!-- Bungkus semuanya dengan Alpine.js -->
<div x-data="lapanganFilter()">

    <!-- HERO SECTION -->
    <div class="hero-section text-center">
        <span class="hero-badge text-orange fw-bold">Anti Bentrok Jadwal</span>
        <h1 class="hero-title text-light">
            Booking Lapangan Olahraga<br>
            <span class="text-orange">Cepat & Transparan</span>
        </h1>
        <p class="hero-subtitle">
            Lihat ketersediaan lapangan real-time, pilih jam langsung dari grid, dan pesan tanpa antri. Futsal, Badminton, Basket, dan Tenis dalam satu platform.
        </p>

        <!-- Search Bar (Terkoneksi dengan Alpine) -->
        <div class="hero-search">
            <i class="bi bi-search"></i>
            <input type="text" x-model="search" placeholder="Cari lapangan...">
        </div>
    </div>

    <!-- FILTER KATEGORI -->
    <h5 class="fw-bold text-navy mb-3">Filter Kategori</h5>
    <div class="d-flex flex-wrap gap-2 mb-4">
        <!-- Tombol Semua (Mengatur activeKategori menjadi null tanpa reload) -->
        <button type="button" 
                @click="activeKategori = null" 
                class="filter-pill shadow-sm" 
                :class="{ 'active': activeKategori === null }">
            Semua
        </button>

        <!-- Tombol Kategori Dinamis -->
        @foreach ($kategoriList as $kategori)
            <button type="button" 
                    @click="activeKategori = {{ $kategori->id }}" 
                    class="filter-pill shadow-sm" 
                    :class="{ 'active': activeKategori === {{ $kategori->id }} }">
                {{ $kategori->nama_kategori }}
            </button>
        @endforeach
    </div>

    <!-- COUNTER & JUDUL LIST -->
    <h5 class="fw-bold text-navy mb-4">
        Lapangan Tersedia (<span x-text="filtered().length"></span>)
    </h5>

    <!-- GRID LAPANGAN -->
    <div class="row g-4" x-show="filtered().length > 0">
        <!-- Looping data lapangan menggunakan Alpine (Tanpa reload) -->
        <template x-for="lapangan in filtered()" :key="lapangan.id">
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card lapangan-card shadow-sm h-100 border-0">
                    <div class="card-body p-4 d-flex flex-column">
                        
                        <!-- Badges -->
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <span class="badge-kategori" x-text="lapangan.kategori"></span>
                            <span class="badge-status">
                                <span class="badge-status-dot"></span>Aktif
                            </span>
                        </div>
                        
                        <!-- Nama & Harga -->
                        <h5 class="fw-bold text-navy mb-2" x-text="lapangan.nama"></h5>
                        <div class="mb-4">
                            <span class="text-muted small d-block">Tarif per jam</span>
                            <h6 class="fw-bold text-dark mb-0" x-text="'Rp ' + lapangan.tarif_format"></h6>
                        </div>

                        <!-- Tombol Booking -->
                        <div class="mt-auto">
                            <a :href="lapangan.url" 
                               class="btn btn-orange w-100 py-2 d-flex justify-content-center align-items-center gap-2"
                               style="border-radius: 0.75rem;">
                                <span>Booking Sekarang</span>
                                <i class="bi bi-arrow-right-short fs-5"></i>
                            </a>
                        </div>
                        
                    </div>
                </div>
            </div>
        </template>
    </div>

    <!-- EMPTY STATE (Jika pencarian/filter tidak ditemukan) -->
    <div class="text-center py-5" x-show="filtered().length === 0" x-cloak>
        <div class="card border-0 shadow-sm p-5" style="border-radius: 1rem;">
            <div class="text-muted">
                <i class="bi bi-inbox fs-1 d-block mb-3 text-secondary"></i>
                <h5 class="fw-bold text-navy">Lapangan Tidak Ditemukan</h5>
                <p class="mb-0">Coba ganti kategori atau kata kunci pencarian.</p>
            </div>
        </div>
    </div>

</div>

@php
    // Kita olah datanya di blok PHP terpisah agar Blade tidak bingung
    $lapanganData = $lapanganList->map(function ($l) {
        return [
            'id' => $l->id,
            'nama' => $l->nama_lapang,
            'kategori' => $l->kategoriOlahraga->nama_kategori ?? 'Umum',
            'kategori_id' => $l->kategori_id,
            'tarif_format' => number_format($l->tarif_per_jam, 0, ',', '.'),
            'url' => route('customer.pilih-jenis', $l->id),
        ];
    })->values();
@endphp

<script>
    function lapanganFilter() {
        return {
            search: '',
            activeKategori: null,
            // Panggil variabel PHP ke dalam JavaScript
            data: @json($lapanganData),
            
            // Fungsi yang menjalankan filter secara real-time
            filtered() {
                return this.data.filter(l => {
                    const matchKategori = this.activeKategori === null || l.kategori_id === this.activeKategori;
                    const matchSearch = l.nama.toLowerCase().includes(this.search.toLowerCase());
                    return matchKategori && matchSearch;
                });
            }
        }
    }
</script>
<!-- ================================== -->
@endsection