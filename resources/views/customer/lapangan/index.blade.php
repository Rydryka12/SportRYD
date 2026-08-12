@extends('layouts.customer')

@section('content')
<style>
    /* Custom Styling untuk Tema Mazer SportRYD */
    .text-navy { color: #12244a; }
    
    .btn-navy { 
        background-color: #12244a; 
        color: white; 
        border: 1px solid #12244a; 
    }
    .btn-navy:hover { 
        background-color: #0d1a35; 
        color: white; 
    }
    
    .btn-outline-navy { 
        background-color: white; 
        color: #12244a; 
        border: 1px solid #12244a; 
    }
    .btn-outline-navy:hover { 
        background-color: #12244a; 
        color: white; 
    }

    .btn-orange { 
        background-color: #ef7d2d; 
        color: white; 
        border: none; 
        font-weight: 500; 
    }
    .btn-orange:hover { 
        background-color: #d96b22; 
        color: white; 
    }

    .lapangan-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .lapangan-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    }
</style>

<!-- Header Halaman -->
<div class="d-flex align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-navy mb-1">Daftar Lapangan</h3>
        <p class="text-muted mb-0">Pilih lapangan olahraga favoritmu dan lakukan booking sekarang.</p>
    </div>
</div>

<!-- Filter Kategori Olahraga -->
<div class="d-flex flex-wrap gap-2 mb-4">
    <!-- Tombol Semua -->
    <a href="{{ route('customer.beranda') }}" 
       class="btn btn-sm rounded-pill px-4 py-2 fw-semibold {{ !$kategoriId ? 'btn-navy shadow-sm' : 'btn-outline-navy' }}">
        Semua
    </a>

    <!-- Looping Kategori dari Database -->
    @foreach ($kategoriList as $kategori)
        <a href="{{ route('customer.beranda', ['kategori_id' => $kategori->id]) }}" 
           class="btn btn-sm rounded-pill px-4 py-2 fw-semibold {{ $kategoriId == $kategori->id ? 'btn-navy shadow-sm' : 'btn-outline-navy' }}">
            {{ $kategori->nama_kategori }}
        </a>
    @endforeach
</div>

<!-- Grid List Lapangan -->
<div class="row g-4">
    @forelse ($lapanganList as $lapangan)
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card lapangan-card shadow-sm h-100 border-0" style="border-radius: 1rem;">
                <div class="card-body p-4 d-flex flex-column">
                    
                    <!-- Judul Lapangan & Badge Kategori -->
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="fw-bold text-navy mb-0">{{ $lapangan->nama_lapang }}</h5>
                        <span class="badge bg-light text-secondary border rounded-pill px-3 py-2">
                            {{ $lapangan->kategoriOlahraga->nama_kategori ?? 'Umum' }}
                        </span>
                    </div>

                    <!-- Harga / Tarif -->
                    <div class="mb-4">
                        <span class="text-muted small">Tarif per jam:</span>
                        <h6 class="fw-bold text-dark mb-0">Rp{{ number_format($lapangan->tarif_per_jam, 0, ',', '.') }}</h6>
                    </div>
                    
                    <!-- Tombol Aksi (Booking)  -->
                    <div class="mt-auto"> 
                        <a href="" 
                           class="btn btn-orange w-100 py-2 d-flex justify-content-center align-items-center gap-2" 
                           style="border-radius: 0.75rem;">
                            <span>Booking Sekarang</span> 
                            <i class="bi bi-arrow-right-short fs-5"></i>
                        </a>
                    </div>

                </div>
            </div>
        </div>
    @empty
        <!-- Tampilan Jika Data Kosong -->
        <div class="col-12 text-center py-5">
            <div class="card border-0 shadow-sm p-5" style="border-radius: 1rem;">
                <div class="text-muted">
                    <i class="bi bi-inbox fs-1 d-block mb-3 text-secondary"></i>
                    <h5 class="fw-bold text-navy">Lapangan Tidak Ditemukan</h5>
                    <p class="mb-0">Belum ada lapangan yang tersedia untuk kategori olahraga ini.</p>
                </div>
            </div>
        </div>
    @endforelse
</div>
@endsection