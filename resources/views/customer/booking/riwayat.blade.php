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
    .badge-berlangsung { background-color: #dcfce7; color: #16a34a; }
    .badge-menunggu-approval { background-color: #fef3e2; color: #b45309; }
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
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.3; }
    }
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
            <p class="text-4xl font-bold mb-4">{{ $saldoPoin }}</p>
            <a href="{{ route('customer.poin.index') }}" class="w-100 btn btn-light text-orange fw-semibold">
                Tukar Poin &rarr;
            </a>
        </div>
    </div>



    <!--    \App\Models\PoinCustomer::create([
       'customer_id' => 2, // ganti sesuai ID akun customer kamu
       'jumlah_poin' => 100,
       'jenis' => 'Masuk',
       'keterangan' => 'Test manual',
       'tanggal' => now()->toDateString(),
   ]); -->

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
                                <a href="{{ route('customer.paket.pakai-sesi.create', $langganan) }}" class="w-100 btn btn-primary btn-sm">
                                    Pakai Sesi
                                </a>
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

{{-- Flash message --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert" style="border-radius:0.75rem;">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert" style="border-radius:0.75rem;">
        <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div x-data="{ confirmOpen: false, targetForm: null, bookingInfo: '' }">

<div class="card booking-list-card border-0 shadow-sm">
    @forelse ($bookingList as $booking)
        <div class="booking-item d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                    <span class="fw-bold text-navy">{{ $booking->lapangan->nama_lapang }}</span>
                    <span class="badge-status
                        @if($booking->display_status === 'Sedang Berlangsung') badge-berlangsung
                        @elseif($booking->display_status === 'Akan Datang') badge-akan-datang
                        @elseif($booking->display_status === 'Menunggu Approval') badge-menunggu-approval
                        @elseif($booking->display_status === 'Selesai') badge-selesai
                        @elseif($booking->display_status === 'Menunggu Approval Reschedule') badge-menunggu
                        @elseif($booking->display_status === 'Dibatalkan') badge-dibatalkan
                        @endif
                    ">
                        @if($booking->display_status === 'Sedang Berlangsung')
                            <span style="display:inline-block;width:7px;height:7px;border-radius:50%;background:#16a34a;margin-right:4px;vertical-align:middle;animation:pulse 1.5s infinite;"></span>
                        @endif
                        {{ $booking->display_status }}
                    </span>
                </div>
                <p class="text-muted small mb-0">
                    <i class="bi bi-calendar3 me-1"></i>{{ \Carbon\Carbon::parse($booking->tanggal)->translatedFormat('d M Y') }}
                    &nbsp;
                    <i class="bi bi-clock me-1"></i>{{ \Carbon\Carbon::parse($booking->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->jam_selesai)->format('H:i') }}
                    &nbsp;
                    <span class="fw-semibold text-orange">Rp {{ number_format($booking->harga, 0, ',', '.') }}</span>
                </p>
                @if($booking->display_status === 'Menunggu Approval')
                    <p class="small mb-0 mt-1" style="color:#b45309;">
                        <i class="bi bi-hourglass-split me-1"></i>Menunggu konfirmasi dari kasir
                    </p>
                @endif
            </div>

            @if ($booking->display_status === 'Akan Datang')
                <div class="d-flex gap-2">
                    <a href="{{ route('customer.reschedule.create', $booking) }}" class="btn-reschedule text-decoration-none">
                        <i class="bi bi-arrow-repeat me-1"></i>Reschedule
                    </a>
                    <form id="cancel-form-{{ $booking->id }}" method="POST" action="{{ route('customer.booking.cancel', $booking) }}">
                        @csrf @method('PATCH')
                    </form>
                    <button type="button" class="btn-batalkan"
                            @click="targetForm='cancel-form-{{ $booking->id }}';bookingInfo='{{ $booking->lapangan->nama_lapang }} · {{ \Carbon\Carbon::parse($booking->tanggal)->translatedFormat('d M Y') }} {{ \Carbon\Carbon::parse($booking->jam_mulai)->format('H:i') }}–{{ \Carbon\Carbon::parse($booking->jam_selesai)->format('H:i') }}';confirmOpen=true">
                        <i class="bi bi-x-circle me-1"></i>Batalkan
                    </button>
                </div>
            @elseif ($booking->display_status === 'Menunggu Approval')
                <div class="d-flex gap-2">
                    <form id="cancel-form-{{ $booking->id }}" method="POST" action="{{ route('customer.booking.cancel', $booking) }}">
                        @csrf @method('PATCH')
                    </form>
                    <button type="button" class="btn-batalkan"
                            @click="targetForm='cancel-form-{{ $booking->id }}';bookingInfo='{{ $booking->lapangan->nama_lapang }} · {{ \Carbon\Carbon::parse($booking->tanggal)->translatedFormat('d M Y') }} {{ \Carbon\Carbon::parse($booking->jam_mulai)->format('H:i') }}–{{ \Carbon\Carbon::parse($booking->jam_selesai)->format('H:i') }}';confirmOpen=true">
                        <i class="bi bi-x-circle me-1"></i>Batalkan
                    </button>
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

{{-- Modal konfirmasi batalkan --}}
<template x-teleport="body">
    <div x-show="confirmOpen"
         x-cloak
         @keydown.escape.window="confirmOpen = false"
         @click.self="confirmOpen = false"
         style="position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,0.5);">

        <div x-show="confirmOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);background:white;border-radius:1rem;width:calc(100% - 2rem);max-width:440px;box-shadow:0 20px 25px -5px rgba(0,0,0,.2);overflow:hidden;">

            <div style="padding:1.75rem 1.75rem 1.5rem;">
                {{-- Ikon --}}
                <div style="width:52px;height:52px;background:#fdecea;border-radius:50%;display:flex;align-items:center;justify-content:center;margin-bottom:1rem;">
                    <i class="bi bi-exclamation-triangle" style="color:#dc3545;font-size:1.4rem;"></i>
                </div>

                <h5 class="fw-bold mb-1" style="color:#12244a;">Batalkan Booking?</h5>
                <p class="text-muted small mb-1">Booking berikut akan dibatalkan:</p>
                <p class="fw-semibold mb-3" style="color:#12244a;font-size:0.9rem;" x-text="bookingInfo"></p>
                <p class="text-muted small mb-4">Tindakan ini tidak dapat dibatalkan.</p>

                <div class="d-flex gap-2 justify-content-end">
                    <button type="button"
                            @click="confirmOpen = false"
                            style="border:1px solid #dee2e6;background:white;color:#6b7280;font-weight:600;border-radius:0.5rem;padding:0.5rem 1.25rem;cursor:pointer;">
                        Kembali
                    </button>
                    <button type="button"
                            @click="document.getElementById(targetForm).submit()"
                            style="background:#dc3545;border:none;color:white;font-weight:600;border-radius:0.5rem;padding:0.5rem 1.25rem;cursor:pointer;">
                        Ya, Batalkan
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

</div>{{-- end x-data --}}
@endsection