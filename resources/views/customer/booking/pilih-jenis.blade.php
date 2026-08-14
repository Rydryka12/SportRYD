@extends('layouts.customer')

@section('content')
<style>
    /* Warna Custom */
    .text-navy { color: #12244a; }
    .text-orange { color: #d96226; }
    
    /* Background Custom untuk Ikon */
    .bg-light-blue { background-color: #e8f0fe; }
    .bg-light-orange { background-color: #fcece3; }

    /* Kotak Harga */
    .price-box {
        background-color: #fcfcfc;
        border: 1px solid #e0e0e0;
        border-radius: 0.75rem;
    }

    /* Card Pilihan Booking */
    .option-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border-radius: 1rem;
        border: 1px solid #e0e0e0;
    }
    .option-card:hover:not(.disabled-card) {
        transform: translateY(-4px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.10) !important;
        border-color: #12244a;
    }
    
    /* Kotak Ikon di dalam Card */
    .icon-box {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.5rem;
    }

    /* Banner Area */
    .banner-gradient {
        background: linear-gradient(135deg, #f0f4fd 0%, #fdf4f0 100%);
        height: 180px;
        position: relative;
    }
    .circle-initial {
        width: 70px;
        height: 70px;
        background-color: #e5e9f2;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        position: absolute;
        bottom: -35px;
        left: 50%;
        transform: translateX(-50%);
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }
</style>

<!-- Tombol Kembali -->
<a href="{{ route('customer.beranda') }}" class="text-muted small mb-3 d-inline-block text-decoration-none">
    <i class="bi bi-arrow-left"></i> Kembali
</a>

<div class="card border-0 shadow-sm rounded-4 mb-4 overflow-visible">
    <!-- Area Banner -->
    <div class="banner-gradient rounded-top-4">
        <!-- Badge Kategori -->
        <span class="badge bg-white text-dark border position-absolute top-0 start-0 m-3 px-3 py-2 rounded-pill fw-normal shadow-sm">
            {{ $lapangan->kategoriOlahraga->nama_kategori ?? 'Kategori Olahraga' }}
        </span>
        <!-- Inisial Lingkaran -->
        <div class="circle-initial fw-bold text-navy">
            {{ strtoupper(substr($lapangan->nama_lapang, 0, 1)) }}
        </div>
    </div>

    <!-- Detail Lapangan -->
    <div class="card-body px-4 px-md-5 pt-5 pb-4 mt-2">
        <h3 class="fw-bold text-navy mb-2">{{ $lapangan->nama_lapang ?? 'Lapangan Olahraga' }}</h3>
        <p class="text-muted mb-3">{{ $lapangan->deskripsi ?? 'Lapangan profesional.' }}</p>
        
        <div class="d-flex text-muted small mb-4 gap-4">
            <span><i class="bi bi-clock me-1"></i> 08:00 - 22:00 WIB</span>
            <span><i class="bi bi-record-circle me-1"></i> {{ $lapangan->kategoriOlahraga->nama_kategori ?? 'Olahraga' }}</span>
        </div>

        <!-- Box Harga -->
        <div class="price-box p-3 p-md-4 mb-5">
            <span class="text-muted small d-block mb-1">Tarif per jam</span>
            <h3 class="fw-bold text-orange mb-0">Rp {{ number_format($lapangan->tarif_per_jam, 0, ',', '.') }}</h3>
        </div>

        <!-- Judul Pilihan Booking -->
        <h5 class="fw-bold text-navy mb-1">Pilih Jenis Booking</h5>
        <p class="text-muted small mb-4">Pilih cara booking yang sesuai dengan kebutuhan Anda</p>

        <!-- Pilihan Metode Booking -->
        <div class="row g-4">
            <!-- Pilihan 1: Per Jam -->
            <div class="col-12 col-md-6">
                <a href="{{ route('customer.booking.create', $lapangan) }}" class="text-decoration-none text-dark">
                    <div class="card option-card h-100 bg-white">
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="icon-box bg-light-blue text-primary mb-3">
                                <i class="bi bi-calendar3 fs-5"></i>
                            </div>
                            <h6 class="fw-bold text-navy mb-2">Booking per Jam</h6>
                            <p class="text-muted small mb-4 flex-grow-1">Pilih tanggal & jam main, bayar DP. Cocok untuk sekali main.</p>
                            <span class="text-primary small fw-semibold">Mulai dari Rp {{ number_format($lapangan->tarif_per_jam, 0, ',', '.') }}/jam</span>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Pilihan 2: Paket Langganan -->
            <div class="col-12 col-md-6">
                <a href="{{ route('customer.paket.index', $lapangan) }}" class="text-decoration-none text-dark">
                    <div class="card option-card h-100 bg-white">
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="icon-box bg-light-orange text-orange mb-3">
                                <i class="bi bi-box-seam fs-5"></i>
                            </div>
                            <h6 class="fw-bold text-navy mb-2">Paket Langganan</h6>
                            <p class="text-muted small mb-4 flex-grow-1">Kuota sesi atau jadwal tetap rutin. Lebih hemat untuk yang sering main.</p>
                            <span class="text-orange small fw-semibold">Tipe Kuota & Jadwal Tetap tersedia</span>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection