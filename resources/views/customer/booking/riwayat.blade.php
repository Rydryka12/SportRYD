@extends('layouts.customer')

@section('content')
<style>
    /* --- WARNA UTAMA --- */
    .text-navy { color: #12244a; }
    .text-orange { color: #ef7d2d; }

    /* --- KOTAK POIN & PAKET --- */
    .poin-card {
        background: linear-gradient(135deg, #ef7d2d 0%, #f4a25f 100%);
        border-radius: 1rem;
        color: white;
        padding: 1.5rem;
    }
    .poin-icon-box {
        width: 44px; height: 44px;
        background-color: rgba(255,255,255,0.2);
        border-radius: 0.6rem;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem;
    }
    .btn-tukar-poin {
        background-color: white; color: #ef7d2d; border: none;
        font-weight: 600; border-radius: 0.6rem; transition: all 0.2s ease;
    }
    .btn-tukar-poin:disabled {
        opacity: 0.6; cursor: not-allowed;
    }

    .paket-card {
        border-radius: 1rem; border: 1px solid #eef0f2; padding: 1.5rem;
        transition: box-shadow 0.2s ease, transform 0.2s ease;
    }
    .paket-card:hover {
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.08) !important;
        transform: translateY(-2px);
    }
    .paket-icon-box {
        width: 36px; height: 36px;
        background-color: #e8f0fe; color: #3b82f6;
        border-radius: 0.5rem;
        display: flex; align-items: center; justify-content: center;
        font-weight: bold;
    }

    /* --- BADGE UNTUK PAKET --- */
    .badge-kuota { background-color: #e8f0fe; color: #2563eb; font-weight: 600; border-radius: 50px; padding: 0.35rem 0.85rem;}
    .badge-jadwal { background-color: #f3e8ff; color: #7e22ce; font-weight: 600; border-radius: 50px; padding: 0.35rem 0.85rem;}
    
    .btn-pakai-sesi {
        background-color: #f1f3f5; color: #adb5bd; font-weight: 600; border-radius: 0.6rem;
    }
    .btn-pakai-sesi:disabled {
        opacity: 0.7; cursor: not-allowed;
    }

    /* --- LIST RIWAYAT BOOKING --- */
    .booking-list-card { border-radius: 1rem; overflow: hidden; }
    .booking-item { padding: 1.25rem 1.5rem; transition: background-color 0.15s ease; }
    .booking-item:hover { background-color: #fafbfc; }
    .booking-item:not(:last-child) { border-bottom: 1px solid #f0f1f3; }

    .badge-status { border-radius: 50px; padding: 0.35rem 0.85rem; font-size: 0.8rem; font-weight: 600; }
    .badge-akan-datang { background-color: #e8f0fe; color: #2563eb; }
    .badge-selesai { background-color: #f1f3f5; color: #6c757d; }
    .badge-menunggu { background-color: #fef3e2; color: #d97706; }
    .badge-dibatalkan { background-color: #fdecea; color: #dc2626; }

    .btn-reschedule {
        border: 1px solid #ef7d2d; color: #ef7d2d; background: white; font-weight: 600;
        font-size: 0.85rem; border-radius: 0.5rem; padding: 0.4rem 0.9rem; transition: all 0.2s ease;
    }
    .btn-reschedule:hover { background-color: #ef7d2d; color: white; }

    .btn-batalkan {
        border: 1px solid #dc3545; color: #dc3545; background: white; font-weight: 600;
        font-size: 0.85rem; border-radius: 0.5rem; padding: 0.4rem 0.9rem; transition: all 0.2s ease;
    }
    .btn-batalkan:hover { background-color: #dc3545; color: white; }

    .empty-state-icon { font-size: 2.5rem; }
</style>

<h3 class="fw-bold text-navy mb-4">Riwayat Pemesanan &amp; Saldo Poin</h3>

<div class="row g-4 mb-5">
    <!-- Card Saldo Poin -->
    <div class="col-12 col-md-6">
        <div class="poin-card h-100">
            <div class="d-flex align-items-center gap-2 mb-3">
                <div class="poin-icon-box">
                    <i class="bi bi-gift"></i>
                </div>
                <span>Saldo Poin Anda</span>
            </div>
            <p class="display-6 fw-bold mb-3">0</p>
            <button disabled class="btn btn-tukar-poin w-100 py-2">
                Tukar Poin &rarr;
            </button>
        </div>
    </div>

    <!-- Card Ambil Paket Baru (Bisa Diklik) -->
    <div class="col-12 col-md-6">
        <a href="{{ route('customer.beranda') }}" class="text-decoration-none text-dark d-block h-100">
            <div class="paket-card bg-white shadow-sm h-100">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div class="paket-icon-box">+</div>
                    <span class="text-muted">Ingin main lagi?</span>
                </div>
                <p class="fw-bold text-navy mb-2">Ambil Paket Baru</p>
                <p class="text-muted small mb-0">Pilih lapangan, lalu pilih opsi "Paket Langganan" untuk berhemat.</p>
            </div>
        </a>
    </div>
</div>

<!-- ============================================== -->
<!-- SECTION: PAKET LANGGANAN AKTIF -->
<!-- ============================================== -->
@if ($paketAktifList->isNotEmpty())
    <h5 class="fw-bold text-navy mb-3">Paket Langganan Aktif</h5>
    <div class="row g-4 mb-5">
        @foreach ($paketAktifList as $langganan)
            <div class="col-12 col-md-6">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 1rem;">
                    <div class="card-body p-4 d-flex flex-column">
                        
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="fw-bold text-navy mb-0">{{ $langganan->paketLangganan->kategoriOlahraga->nama_kategori }}</h6>
                            <span class="{{ $langganan->paketLangganan->tipe_paket === 'Kuota' ? 'badge-kuota' : 'badge-jadwal' }} text-xs">
                                {{ $langganan->paketLangganan->tipe_paket }}
                            </span>
                        </div>
                        <p class="text-muted small mb-3">{{ $langganan->paketLangganan->nama_paket }}</p>

                        <!-- Detail Berdasarkan Tipe Paket -->
                        <div class="mt-auto">
                            @if ($langganan->paketLangganan->tipe_paket === 'Kuota')
                                <p class="text-muted small mb-3">
                                    Sisa <span class="fw-bold text-navy">{{ $langganan->sisa_sesi }}</span> sesi &middot; berlaku s.d. {{ \Carbon\Carbon::parse($langganan->tanggal_berakhir)->translatedFormat('d M Y') }}
                                </p>
                                <button disabled class="btn w-100 py-2 btn-pakai-sesi">
                                    Pakai Sesi (segera hadir)
                                </button>
                            @else
                                <p class="text-muted small mb-3">
                                    <i class="bi bi-geo-alt me-1 text-primary"></i> {{ $langganan->lapangan->nama_lapang ?? '-' }}<br>
                                    <i class="bi bi-calendar-event me-1 text-primary"></i> {{ $langganan->hari_dalam_minggu }},  
                                    {{ $langganan->jam_mulai ? \Carbon\Carbon::parse($langganan->jam_mulai)->format('H:i') : '' }} - {{ $langganan->jam_selesai ? \Carbon\Carbon::parse($langganan->jam_selesai)->format('H:i') : '' }}
                                </p>
                                <p class="text-muted mb-0" style="font-size: 0.75rem;">
                                    <i class="bi bi-info-circle me-1"></i> Berlaku s.d. {{ \Carbon\Carbon::parse($langganan->tanggal_berakhir)->translatedFormat('d M Y') }}
                                </p>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif


<!-- ============================================== -->
<!-- SECTION: RIWAYAT BOOKING -->
<!-- ============================================== -->
<h5 class="fw-bold text-navy mb-3">Riwayat Booking</h5>

<div class="card booking-list-card border-0 shadow-sm">
    @forelse ($bookingList as $booking)
        <div class="booking-item d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                    <span class="fw-bold text-navy">{{ $booking->lapangan->nama_lapang }}</span>
                    <span class="badge-status
                        @if($booking->status === 'Akan Datang') badge-akan-datang
                        @elseif($booking->status === 'Selesai') badge-selesai
                        @elseif($booking->status === 'Menunggu Approval Reschedule') badge-menunggu
                        @elseif($booking->status === 'Dibatalkan') badge-dibatalkan
                        @endif
                    ">{{ $booking->status }}</span>
                </div>
                <p class="text-muted small mb-0">
                    <i class="bi bi-calendar3 me-1"></i>{{ \Carbon\Carbon::parse($booking->tanggal)->translatedFormat('d M Y') }}
                    &nbsp;
                    <i class="bi bi-clock me-1"></i>{{ \Carbon\Carbon::parse($booking->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->jam_selesai)->format('H:i') }}
                    &nbsp;
                    <span class="fw-semibold text-orange">Rp {{ number_format($booking->harga, 0, ',', '.') }}</span>
                </p>
            </div>

            @if ($booking->status === 'Akan Datang')
                <div class="d-flex gap-2">
                    <a href="#" class="btn-reschedule text-decoration-none">
                        <i class="bi bi-arrow-repeat me-1"></i>Reschedule
                    </a>
                    <a href="#" class="btn-batalkan text-decoration-none">
                        <i class="bi bi-x-circle me-1"></i>Batalkan
                    </a>
                </div>
            @endif
        </div>
    @empty
        <div class="d-flex flex-column align-items-center justify-content-center py-5">
            <div class="empty-state-icon mb-3">📅</div>
            <p class="text-muted mb-3">Belum ada riwayat booking</p>
            <a href="{{ route('customer.beranda') }}" class="btn btn-orange-submit px-4 py-2" style="background-color:#ef7d2d; color:white; font-weight:600; border-radius:0.6rem;">
                Mulai Booking
            </a>
        </div>
    @endforelse
</div>
@endsection