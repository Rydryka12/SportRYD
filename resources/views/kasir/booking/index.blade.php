@extends('layouts.kasir')

@section('content')
<style>
    /* ── CSS Variables dark-mode aware ── */
    :root {
        --k-bg-card:    #ffffff;
        --k-border:     #e9ecef;
        --k-text:       #212529;
        --k-text-muted: #6c757d;
        --k-text-head:  #12244a;
        --k-thead-bg:   #f8f9fa;
        --k-thead-text: #495057;
        --k-hover-bg:   rgba(0,0,0,0.02);
    }
    html[data-bs-theme="dark"] {
        --k-bg-card:    #1e2d40;
        --k-border:     rgba(255,255,255,0.1);
        --k-text:       #e2e8f0;
        --k-text-muted: #94a3b8;
        --k-text-head:  #e2e8f0;
        --k-thead-bg:   rgba(255,255,255,0.05);
        --k-thead-text: #94a3b8;
        --k-hover-bg:   rgba(255,255,255,0.04);
    }

    /* ── Monitor cards ── */
    .lapangan-monitor-card,
    .sisa-waktu-badge { display: none; } /* removed from this page */

    /* ── Section cards ── */
    .section-card {
        border-radius: 0.85rem;
        border: 1px solid var(--k-border);
        background: var(--k-bg-card);
        overflow: hidden;
    }

    /* ── Tables ── */
    .section-card table thead tr th {
        background: var(--k-thead-bg) !important;
        color: var(--k-thead-text) !important;
        font-weight: 600;
        font-size: 0.82rem;
        border-bottom: 1px solid var(--k-border);
        padding-top: 0.75rem;
        padding-bottom: 0.75rem;
    }
    .section-card table tbody tr { border-bottom: 1px solid var(--k-border); }
    .section-card table tbody tr:last-child { border-bottom: none; }
    .section-card table tbody tr:hover { background: var(--k-hover-bg); }
    .section-card table tbody td { color: var(--k-text); padding: 0.85rem 0.75rem; }
    .cell-name     { color: var(--k-text); font-weight: 600; }
    .cell-sub      { color: var(--k-text-muted); font-size: 0.8rem; }
    .cell-bold     { color: var(--k-text); font-weight: 600; }

    /* ── Metode badge ── */
    .badge-metode {
        background: var(--k-thead-bg);
        color: var(--k-text);
        border: 1px solid var(--k-border);
        border-radius: 50px;
        padding: 0.2rem 0.65rem;
        font-size: 0.75rem;
        font-weight: 600;
    }

    /* ── Section heading ── */
    .section-heading { color: var(--k-text-head); font-weight: 700; }

    /* ── Refresh dot ── */
    .refresh-dot {
        width: 8px; height: 8px; border-radius: 50%;
        background: #16a34a; display: inline-block;
        animation: pulse-dot 2s infinite;
    }

    /* ── Avatar circle ── */
    .avatar-sm {
        width: 34px; height: 34px; border-radius: 50%;
        background: #e8f0fe; color: #2563eb;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 0.9rem; flex-shrink: 0;
    }
    html[data-bs-theme="dark"] .avatar-sm {
        background: rgba(37,99,235,0.25); color: #93c5fd;
    }

    /* ── Empty state ── */
    .empty-cell { color: var(--k-text-muted); padding: 2rem 0; }
</style>

<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h3 class="mb-0">Panel Kasir</h3>
        <div class="d-flex align-items-center gap-2 small" style="color:var(--k-text-muted);">
            <span class="refresh-dot"></span>
            {{ $now->format('H:i') }} WIB
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ══════════════════════════════════
         SECTION 1: PERMINTAAN BOOKING
    ══════════════════════════════════ --}}
    <section class="mb-5">
        <div class="d-flex align-items-center gap-2 mb-3">
            <i class="bi bi-check2-circle section-heading" style="font-size:1.1rem;"></i>
            <h5 class="mb-0 section-heading">
                Permintaan Booking
                @if($bookingPendingApproval->count() > 0)
                    <span class="badge bg-warning text-dark ms-1" style="font-size:0.72rem;border-radius:50px;">{{ $bookingPendingApproval->count() }}</span>
                @endif
            </h5>
            <a href="{{ route('kasir.booking.create') }}" class="btn btn-sm btn-primary ms-auto">
                <i class="bi bi-plus-lg me-1"></i>Booking Manual
            </a>
        </div>

        <div class="section-card">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th class="ps-3">Pelanggan</th>
                            <th>Lapangan</th>
                            <th>Tanggal & Jam</th>
                            <th>Total</th>
                            <th width="185" class="pe-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bookingPendingApproval as $b)
                            <tr>
                                <td class="ps-3">
                                    <div class="cell-name">{{ $b->customer->name }}</div>
                                    <div class="cell-sub">{{ $b->customer->no_hp ?? '-' }}</div>
                                </td>
                                <td class="cell-bold">{{ $b->lapangan->nama_lapang }}</td>
                                <td>
                                    <div class="cell-bold">{{ \Carbon\Carbon::parse($b->tanggal)->translatedFormat('d M Y') }}</div>
                                    <div class="cell-sub">{{ \Carbon\Carbon::parse($b->jam_mulai)->format('H:i') }} – {{ \Carbon\Carbon::parse($b->jam_selesai)->format('H:i') }}</div>
                                </td>
                                <td class="cell-bold">Rp{{ number_format($b->harga, 0, ',', '.') }}</td>
                                <td class="pe-3">
                                    <div class="d-flex gap-2">
                                        <form action="{{ route('kasir.booking.approve', $b) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-check-lg me-1"></i>Setujui</button>
                                        </form>
                                        <form action="{{ route('kasir.booking.reject', $b) }}" method="POST"
                                              onsubmit="return confirm('Tolak booking {{ $b->customer->name }}?')">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-x-lg me-1"></i>Tolak</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center empty-cell"><i class="bi bi-inbox d-block mb-1" style="font-size:1.4rem;"></i>Tidak ada permintaan booking</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════
         SECTION 3: KONFIRMASI DP
    ══════════════════════════════════ --}}
    <section>
        <div class="d-flex align-items-center gap-2 mb-2">
            <i class="bi bi-cash-coin section-heading" style="font-size:1.1rem;"></i>
            <h5 class="mb-0 section-heading">
                Konfirmasi DP
                @if($pembayaranPending->count() > 0)
                    <span class="badge bg-danger ms-1" style="font-size:0.72rem;border-radius:50px;">{{ $pembayaranPending->count() }}</span>
                @endif
            </h5>
        </div>

        {{-- Info alur DP --}}
        <div class="d-flex align-items-start gap-2 mb-3 p-3 rounded-3"
             style="background:rgba(234,179,8,0.08);border:1px solid rgba(234,179,8,0.25);">
            <i class="bi bi-info-circle mt-1" style="color:#b45309;flex-shrink:0;"></i>
            <p class="mb-0 small" style="color:var(--k-text);">
                <strong>Penting:</strong> Konfirmasi DP <strong>sebelum jam main tiba</strong>.
                Jika DP tidak dikonfirmasi saat jam main dimulai, booking otomatis <strong>dibatalkan</strong>
                oleh sistem dan poin tidak diberikan ke customer.
                Setelah DP dikonfirmasi, waktu main berjalan otomatis dan poin akan diberikan saat sesi selesai.
            </p>
        </div>

        <div class="section-card">
            @include('kasir._dp_table')
        </div>
    </section>
</div>

@endsection
