@extends('layouts.customer')

@section('content')
<style>
    /* Menyembunyikan elemen sebelum Alpine.js siap (Mencegah kedip) */
    [x-cloak] { display: none !important; }
    .position-fixed[x-cloak] { display: none !important; }

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
    .option-card {
        border-radius: 0.75rem;
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        cursor: pointer;
    }
    .option-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 14px rgba(0,0,0,0.09);
        border-color: #94a3b8 !important;
    }
</style>

<!-- Bungkus semuanya dengan Alpine.js -->
@php
    $lapanganData = $lapanganList->map(function ($l) {
        return [
            'id'          => $l->id,
            'nama'        => $l->nama_lapang,
            'kategori'    => $l->kategoriOlahraga->nama_kategori ?? 'Umum',
            'kategori_id' => $l->kategori_id,
            'tarif_format'=> number_format($l->tarif_per_jam, 0, ',', '.'),
            'foto'        => $l->foto ? asset('storage/' . $l->foto) : null,
            'url_booking' => route('customer.booking.create', $l->id),
            'url_paket'   => route('customer.paket.index', $l->id),
        ];
    })->values();
@endphp

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
                    <!-- Foto / Placeholder gradient -->
                    <template x-if="lapangan.foto">
                        <img :src="lapangan.foto" :alt="lapangan.nama"
                             style="width:100%;height:160px;object-fit:cover;">
                    </template>
                    <template x-if="!lapangan.foto">
                        <div :style="`width:100%;height:160px;display:flex;align-items:center;justify-content:center;font-size:2.5rem;font-weight:800;color:white;background:${gradientColor(lapangan.kategori_id)};`"
                             x-text="lapangan.nama.charAt(0).toUpperCase()">
                        </div>
                    </template>

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
                            <button type="button"
                               @click="bukaPilihJenis(lapangan)"
                               class="btn btn-orange w-100 py-2 d-flex justify-content-center align-items-center gap-2"
                               style="border-radius: 0.75rem;">
                                <span>Booking Sekarang</span>
                                <i class="bi bi-arrow-right-short fs-5"></i>
                            </button>
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

    <!-- ═══════════════════════════════════════
         MODAL PILIH JENIS BOOKING
    ═══════════════════════════════════════ -->
    <template x-teleport="body">
        <div x-show="$store.modal.open"
             x-cloak
             @keydown.escape.window="$store.modal.open = false"
             @click.self="$store.modal.open = false"
             style="position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,0.55);">

            <div x-show="$store.modal.open"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 translate-y-2"
                 style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);background:white;border-radius:1rem;width:calc(100% - 2rem);max-width:520px;overflow:hidden;box-shadow:0 20px 25px -5px rgba(0,0,0,.2);">

                <!-- Banner atas -->
                <div style="height:88px;position:relative;overflow:hidden;">
                    <template x-if="$store.modal.lapangan?.foto">
                        <img :src="$store.modal.lapangan.foto" :alt="$store.modal.lapangan.nama"
                             style="width:100%;height:100%;object-fit:cover;filter:brightness(0.7);">
                    </template>
                    <template x-if="!$store.modal.lapangan?.foto">
                        <div :style="`width:100%;height:100%;background:linear-gradient(135deg,#f0f4fd 0%,#fdf4f0 100%);`"></div>
                    </template>

                    <span style="position:absolute;top:12px;left:12px;background:white;border:1px solid #dee2e6;border-radius:50px;padding:4px 14px;font-size:0.78rem;font-weight:600;color:#12244a;"
                          x-text="$store.modal.lapangan?.kategori"></span>
                    <div style="position:absolute;bottom:-26px;left:50%;transform:translateX(-50%);width:52px;height:52px;background:#e5e9f2;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:1.4rem;font-weight:800;color:#12244a;box-shadow:0 4px 8px rgba(0,0,0,.1);"
                         x-text="$store.modal.lapangan ? $store.modal.lapangan.nama.charAt(0).toUpperCase() : ''"></div>
                    <!-- Tombol tutup -->
                    <button type="button"
                            @click.stop="$store.modal.open = false"
                            style="position:absolute;top:10px;right:10px;background:rgba(255,255,255,0.9);border:none;cursor:pointer;font-size:1.1rem;color:#6b7280;line-height:1;padding:4px;border-radius:50%;">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <!-- Konten modal -->
                <div style="padding:2.5rem 2rem 1.75rem;">
                    <h5 class="fw-bold text-center mb-1" style="color:#12244a;" x-text="$store.modal.lapangan?.nama"></h5>
                    <p class="text-center text-muted small mb-4">
                        Rp <span x-text="$store.modal.lapangan?.tarif_format"></span>/jam
                    </p>

                    <p class="fw-bold mb-1" style="color:#12244a;font-size:0.95rem;">Pilih Jenis Booking</p>
                    <p class="text-muted small mb-3">Pilih cara booking yang sesuai kebutuhan kamu</p>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                        <!-- Booking per Jam -->
                        <a x-bind:href="$store.modal.lapangan?.url_booking"
                           @click.stop
                           style="text-decoration:none;color:inherit;display:block;">
                            <div style="border:1.5px solid #dee2e6;border-radius:0.75rem;padding:1rem;cursor:pointer;transition:all 0.2s ease;height:100%;"
                                 onmouseover="this.style.borderColor='#94a3b8';this.style.transform='translateY(-2px)';this.style.boxShadow='0 6px 14px rgba(0,0,0,.08)'"
                                 onmouseout="this.style.borderColor='#dee2e6';this.style.transform='';this.style.boxShadow=''">
                                <div style="width:40px;height:40px;background:#e8f0fe;border-radius:0.5rem;display:flex;align-items:center;justify-content:center;margin-bottom:0.75rem;">
                                    <i class="bi bi-calendar3" style="color:#2563eb;font-size:1.1rem;"></i>
                                </div>
                                <div style="font-weight:700;font-size:0.88rem;color:#12244a;margin-bottom:0.35rem;">Booking per Jam</div>
                                <div style="font-size:0.76rem;color:#6b7280;margin-bottom:0.5rem;">Pilih tanggal & jam, bayar DP. Cocok untuk sekali main.</div>
                                <div style="font-size:0.76rem;font-weight:600;color:#2563eb;">Mulai Rp <span x-text="$store.modal.lapangan?.tarif_format"></span>/jam</div>
                            </div>
                        </a>

                        <!-- Paket Langganan -->
                        <a x-bind:href="$store.modal.lapangan?.url_paket"
                           @click.stop
                           style="text-decoration:none;color:inherit;display:block;">
                            <div style="border:1.5px solid #dee2e6;border-radius:0.75rem;padding:1rem;cursor:pointer;transition:all 0.2s ease;height:100%;"
                                 onmouseover="this.style.borderColor='#94a3b8';this.style.transform='translateY(-2px)';this.style.boxShadow='0 6px 14px rgba(0,0,0,.08)'"
                                 onmouseout="this.style.borderColor='#dee2e6';this.style.transform='';this.style.boxShadow=''">
                                <div style="width:40px;height:40px;background:#fcece3;border-radius:0.5rem;display:flex;align-items:center;justify-content:center;margin-bottom:0.75rem;">
                                    <i class="bi bi-box-seam" style="color:#ef7d2d;font-size:1.1rem;"></i>
                                </div>
                                <div style="font-weight:700;font-size:0.88rem;color:#12244a;margin-bottom:0.35rem;">Paket Langganan</div>
                                <div style="font-size:0.76rem;color:#6b7280;margin-bottom:0.5rem;">Kuota sesi atau jadwal tetap. Lebih hemat untuk yang rutin.</div>
                                <div style="font-size:0.76rem;font-weight:600;color:#ef7d2d;">Kuota &amp; Jadwal Tetap tersedia</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </template>

</div>{{-- end x-data lapanganFilter --}}

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.store('modal', {
            open: false,
            lapangan: null,
        });
    });

    function lapanganFilter() {
        const gradients = [
            'linear-gradient(135deg,#12244a,#1b3060)',
            'linear-gradient(135deg,#ef7d2d,#d8691b)',
            'linear-gradient(135deg,#2563eb,#1d4ed8)',
            'linear-gradient(135deg,#16a34a,#15803d)',
            'linear-gradient(135deg,#7c3aed,#6d28d9)',
            'linear-gradient(135deg,#dc2626,#b91c1c)',
        ];

        return {
            search: '',
            activeKategori: null,
            data: @json($lapanganData),

            filtered() {
                return this.data.filter(l => {
                    const matchKategori = this.activeKategori === null || l.kategori_id === this.activeKategori;
                    const matchSearch = l.nama.toLowerCase().includes(this.search.toLowerCase());
                    return matchKategori && matchSearch;
                });
            },

            gradientColor(kategoriId) {
                return gradients[(kategoriId - 1) % gradients.length];
            },

            bukaPilihJenis(lapangan) {
                Alpine.store('modal').lapangan = lapangan;
                Alpine.store('modal').open = true;
            },
        }
    }
</script>
@endsection