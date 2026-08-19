@extends('layouts.kasir')

@section('content')
<style>
    /* ── Monitoring Cards ─────────────────────── */
    .lapangan-monitor-card {
        border-radius: 0.85rem;
        border: 1.5px solid #e9ecef;
        transition: box-shadow 0.2s ease;
    }
    .lapangan-monitor-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,.07); }

    .status-dot {
        width: 10px; height: 10px; border-radius: 50%;
        display: inline-block; flex-shrink: 0;
    }
    .dot-active { background: #16a34a; animation: pulse-dot 1.5s infinite; }
    .dot-idle   { background: #9ca3af; }

    @keyframes pulse-dot {
        0%, 100% { opacity: 1; box-shadow: 0 0 0 0 rgba(22,163,74,.4); }
        50%       { opacity: .8; box-shadow: 0 0 0 5px rgba(22,163,74,0); }
    }

    .sisa-waktu-badge {
        background: #dcfce7; color: #15803d;
        border-radius: 50px; padding: 0.25rem 0.75rem;
        font-size: 0.78rem; font-weight: 700;
    }

    /* ── Approval & Pembayaran Tables ─────────── */
    .section-card {
        border-radius: 0.85rem;
        border: 1px solid #e9ecef;
    }
    .section-card .card-header {
        background: white;
        border-bottom: 1px solid #f0f1f3;
        border-radius: 0.85rem 0.85rem 0 0 !important;
        padding: 1rem 1.25rem;
    }

    .badge-menunggu-approval {
        background: #fef3e2; color: #b45309;
        border-radius: 50px; padding: 0.25rem 0.7rem;
        font-size: 0.75rem; font-weight: 600;
    }

    /* auto-refresh indicator */
    .refresh-dot {
        width: 8px; height: 8px; border-radius: 50%;
        background: #16a34a; display: inline-block;
        animation: pulse-dot 2s infinite;
    }
</style>

<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h3 class="mb-0">Panel Kasir</h3>
        <div class="d-flex align-items-center gap-2 text-muted small">
            <span class="refresh-dot"></span>
            Auto-refresh setiap 30 detik &middot; {{ $now->format('H:i') }} WIB
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ═══════════════════════════════════════════
         SECTION 1: MONITORING LAPANGAN
    ═══════════════════════════════════════════ --}}
    <section class="mb-5">
        <div class="d-flex align-items-center gap-2 mb-3">
            <i class="bi bi-display" style="font-size:1.1rem;color:#12244a;"></i>
            <h5 class="mb-0 fw-bold" style="color:#12244a;">Monitoring Lapangan — Sekarang</h5>
        </div>

        <div class="row g-3">
            @foreach ($lapanganList as $lapangan)
                @php
                    $booking = $sedangBerlangsung->get($lapangan->id);
                    $sedang  = $booking !== null;

                    if ($sedang) {
                        $jamSelesai   = \Carbon\Carbon::parse($booking->tanggal . ' ' . $booking->jam_selesai);
                        $sisaMenit    = (int) now()->diffInMinutes($jamSelesai, false);
                        $sisaMenit    = max($sisaMenit, 0);
                        $sisaJam      = intdiv($sisaMenit, 60);
                        $sisaMenitSisa = $sisaMenit % 60;
                        $sisaLabel    = $sisaJam > 0
                            ? "{$sisaJam}j {$sisaMenitSisa}m"
                            : "{$sisaMenitSisa} menit";
                    }
                @endphp

                <div class="col-12 col-sm-6 col-xl-4">
                    <div class="lapangan-monitor-card bg-white p-3 h-100">

                        {{-- Header: nama + status dot --}}
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div>
                                <span class="fw-bold" style="color:#12244a;font-size:0.95rem;">{{ $lapangan->nama_lapang }}</span>
                                <span class="text-muted small ms-1">· {{ $lapangan->kategoriOlahraga->nama_kategori ?? '' }}</span>
                            </div>
                            <span class="status-dot {{ $sedang ? 'dot-active' : 'dot-idle' }}"></span>
                        </div>

                        @if ($sedang)
                            {{-- Sedang Ada Yang Main --}}
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <div style="width:34px;height:34px;border-radius:50%;background:#e8f0fe;display:flex;align-items:center;justify-content:center;font-weight:700;color:#2563eb;font-size:0.9rem;flex-shrink:0;">
                                    {{ strtoupper(substr($booking->customer->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-semibold" style="font-size:0.88rem;color:#12244a;">{{ $booking->customer->name }}</div>
                                    <div class="text-muted" style="font-size:0.75rem;">
                                        {{ \Carbon\Carbon::parse($booking->jam_mulai)->format('H:i') }} – {{ \Carbon\Carbon::parse($booking->jam_selesai)->format('H:i') }}
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <span style="font-size:0.75rem;color:#16a34a;font-weight:600;">
                                    <i class="bi bi-circle-fill me-1" style="font-size:0.55rem;"></i>Sedang Berlangsung
                                </span>
                                <span class="sisa-waktu-badge">
                                    <i class="bi bi-hourglass-split me-1"></i>Sisa {{ $sisaLabel }}
                                </span>
                            </div>
                        @else
                            {{-- Lapangan Kosong --}}
                            <div class="d-flex align-items-center gap-2 text-muted">
                                <i class="bi bi-door-open" style="font-size:1.2rem;"></i>
                                <span style="font-size:0.85rem;">Tidak ada sesi aktif</span>
                            </div>
                        @endif

                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ═══════════════════════════════════════════
         SECTION 2: APPROVAL BOOKING CUSTOMER
    ═══════════════════════════════════════════ --}}
    <section class="mb-5">
        <div class="d-flex align-items-center gap-2 mb-3">
            <i class="bi bi-check2-circle" style="font-size:1.1rem;color:#12244a;"></i>
            <h5 class="mb-0 fw-bold" style="color:#12244a;">
                Permintaan Booking
                @if($bookingPendingApproval->count() > 0)
                    <span class="badge bg-warning text-dark ms-1" style="font-size:0.72rem;border-radius:50px;">
                        {{ $bookingPendingApproval->count() }}
                    </span>
                @endif
            </h5>
            <a href="{{ route('kasir.booking.create') }}" class="btn btn-sm btn-primary ms-auto">
                <i class="bi bi-plus-lg me-1"></i>Booking Manual
            </a>
        </div>

        <div class="section-card card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background:#f8f9fa;">
                            <tr>
                                <th class="ps-3">Pelanggan</th>
                                <th>Lapangan</th>
                                <th>Tanggal & Jam</th>
                                <th>Total</th>
                                <th width="180" class="pe-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($bookingPendingApproval as $b)
                                <tr>
                                    <td class="ps-3">
                                        <div class="fw-semibold" style="color:#12244a;">{{ $b->customer->name }}</div>
                                        <div class="text-muted small">{{ $b->customer->no_hp ?? '-' }}</div>
                                    </td>
                                    <td>{{ $b->lapangan->nama_lapang }}</td>
                                    <td>
                                        <div>{{ \Carbon\Carbon::parse($b->tanggal)->translatedFormat('d M Y') }}</div>
                                        <div class="text-muted small">
                                            {{ \Carbon\Carbon::parse($b->jam_mulai)->format('H:i') }} – {{ \Carbon\Carbon::parse($b->jam_selesai)->format('H:i') }}
                                        </div>
                                    </td>
                                    <td class="fw-semibold">Rp{{ number_format($b->harga, 0, ',', '.') }}</td>
                                    <td class="pe-3">
                                        <div class="d-flex gap-2">
                                            <form action="{{ route('kasir.booking.approve', $b) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="bi bi-check-lg me-1"></i>Setujui
                                                </button>
                                            </form>
                                            <form action="{{ route('kasir.booking.reject', $b) }}" method="POST"
                                                  onsubmit="return confirm('Tolak booking {{ $b->customer->name }}?')">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-x-lg me-1"></i>Tolak
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox d-block mb-1" style="font-size:1.4rem;"></i>
                                        Tidak ada permintaan booking
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════
         SECTION 3: KONFIRMASI PEMBAYARAN
    ═══════════════════════════════════════════ --}}
    <section>
        <div class="d-flex align-items-center gap-2 mb-3">
            <i class="bi bi-credit-card" style="font-size:1.1rem;color:#12244a;"></i>
            <h5 class="mb-0 fw-bold" style="color:#12244a;">
                Konfirmasi Pembayaran
                @if($pembayaranPending->count() > 0)
                    <span class="badge bg-danger ms-1" style="font-size:0.72rem;border-radius:50px;">
                        {{ $pembayaranPending->count() }}
                    </span>
                @endif
            </h5>
        </div>

        <div class="section-card card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background:#f8f9fa;">
                            <tr>
                                <th class="ps-3">Pelanggan</th>
                                <th>Lapangan</th>
                                <th>Tanggal & Jam</th>
                                <th>Metode</th>
                                <th>Jumlah</th>
                                <th width="130" class="pe-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pembayaranPending as $pembayaran)
                                <tr>
                                    <td class="ps-3">
                                        <div class="fw-semibold" style="color:#12244a;">{{ $pembayaran->booking->customer->name }}</div>
                                    </td>
                                    <td>{{ $pembayaran->booking->lapangan->nama_lapang }}</td>
                                    <td>
                                        <div>{{ \Carbon\Carbon::parse($pembayaran->booking->tanggal)->translatedFormat('d M Y') }}</div>
                                        <div class="text-muted small">
                                            {{ \Carbon\Carbon::parse($pembayaran->booking->jam_mulai)->format('H:i') }} – {{ \Carbon\Carbon::parse($pembayaran->booking->jam_selesai)->format('H:i') }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">{{ $pembayaran->metode }}</span>
                                    </td>
                                    <td class="fw-semibold">Rp{{ number_format($pembayaran->jumlah, 0, ',', '.') }}</td>
                                    <td class="pe-3">
                                        <form action="{{ route('kasir.pembayaran.konfirmasi', $pembayaran) }}" method="POST"
                                              onsubmit="return confirm('Konfirmasi pembayaran ini?')">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="bi bi-check-lg me-1"></i>Konfirmasi
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox d-block mb-1" style="font-size:1.4rem;"></i>
                                        Tidak ada pembayaran yang menunggu
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

{{-- Auto-refresh setiap 30 detik --}}
<script>
    setTimeout(() => window.location.reload(), 30000);
</script>
@endsection
