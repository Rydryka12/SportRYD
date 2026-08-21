@extends('layouts.kasir')

@section('content')
<style>
    :root {
        --k-bg-card:    #ffffff;
        --k-border:     #e9ecef;
        --k-text:       #212529;
        --k-text-muted: #6c757d;
        --k-text-head:  #12244a;
        --k-thead-bg:   #f8f9fa;
        --k-hover-bg:   rgba(0,0,0,0.02);
    }
    html[data-bs-theme="dark"] {
        --k-bg-card:    #1e2d40;
        --k-border:     rgba(255,255,255,0.1);
        --k-text:       #e2e8f0;
        --k-text-muted: #94a3b8;
        --k-text-head:  #e2e8f0;
        --k-thead-bg:   rgba(255,255,255,0.05);
        --k-hover-bg:   rgba(255,255,255,0.04);
    }

    /* ── Stat cards ── */
    .stat-card {
        background: var(--k-bg-card);
        border: 1px solid var(--k-border);
        border-radius: 0.85rem;
        padding: 1.25rem 1.5rem;
        display: flex; align-items: center; gap: 1rem;
        transition: box-shadow 0.2s, transform 0.2s;
        text-decoration: none;
    }
    .stat-card:hover { box-shadow: 0 6px 18px rgba(0,0,0,.1); transform: translateY(-2px); }
    .stat-icon { width: 52px; height: 52px; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0; }
    .stat-label { font-size: 0.78rem; color: var(--k-text-muted); font-weight: 600; margin-bottom: 0.2rem; }
    .stat-value { font-size: 1.5rem; font-weight: 800; color: var(--k-text); line-height: 1.1; }

    /* ── Notif cards ── */
    .notif-card {
        background: var(--k-bg-card);
        border: 1px solid var(--k-border);
        border-radius: 0.85rem;
        padding: 1.25rem 1.5rem;
        display: flex; align-items: center; justify-content: space-between; gap: 1rem;
        transition: box-shadow 0.2s, transform 0.2s;
        text-decoration: none;
    }
    .notif-card:hover { box-shadow: 0 6px 18px rgba(0,0,0,.1); transform: translateY(-2px); }
    .notif-icon { width: 46px; height: 46px; border-radius: 0.65rem; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0; }
    .notif-label { font-size: 0.78rem; color: var(--k-text-muted); font-weight: 600; margin-bottom: 0.2rem; }
    .notif-value { font-size: 1.3rem; font-weight: 800; color: var(--k-text); line-height: 1.1; }
    .notif-arrow { color: var(--k-text-muted); font-size: 1.1rem; }

    /* ── Laporan ringkas ── */
    .laporan-card {
        background: var(--k-bg-card);
        border: 1px solid var(--k-border);
        border-radius: 0.85rem;
        overflow: hidden;
    }
    .laporan-card .card-head {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--k-border);
        display: flex; align-items: center; justify-content: space-between;
    }
    .laporan-card table thead th {
        background: var(--k-thead-bg) !important;
        color: var(--k-text-muted) !important;
        font-size: 0.78rem; font-weight: 600;
        padding: 0.65rem 0.85rem;
        border-bottom: 1px solid var(--k-border);
    }
    .laporan-card table tbody tr { border-bottom: 1px solid var(--k-border); }
    .laporan-card table tbody tr:last-child { border-bottom: none; }
    .laporan-card table tbody tr:hover { background: var(--k-hover-bg); }
    .laporan-card table tbody td { color: var(--k-text); padding: 0.7rem 0.85rem; font-size: 0.88rem; }

    /* ── Chart bar ── */
    .chart-card {
        background: var(--k-bg-card);
        border: 1px solid var(--k-border);
        border-radius: 0.85rem;
        padding: 1.25rem 1.5rem 1rem;
    }
    .bar-track { background: var(--k-thead-bg); border-radius: 4px; height: 8px; flex: 1; overflow: hidden; }
    .bar-fill  { background: #ef7d2d; height: 100%; border-radius: 4px; transition: width .4s ease; }
    .bar-label { font-size: 0.78rem; color: var(--k-text-muted); min-width: 32px; }
    .bar-value { font-size: 0.78rem; font-weight: 700; color: var(--k-text); min-width: 80px; text-align: right; }

    /* ── Monitor cards ── */
    .monitor-card {
        background: var(--k-bg-card);
        border: 1.5px solid var(--k-border);
        border-radius: 0.85rem;
        transition: box-shadow 0.2s;
    }
    .monitor-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,.1); }
    .monitor-name  { color: var(--k-text); font-weight: 700; font-size: 0.88rem; }
    .monitor-cat   { color: var(--k-text-muted); font-size: 0.78rem; }
    .monitor-player{ color: var(--k-text); font-weight: 600; font-size: 0.83rem; }
    .monitor-jam   { color: var(--k-text-muted); font-size: 0.73rem; }
    .monitor-idle  { color: var(--k-text-muted); font-size: 0.82rem; }
    .status-dot    { width: 9px; height: 9px; border-radius: 50%; display: inline-block; flex-shrink: 0; }
    .dot-active    { background: #16a34a; animation: pulse-dot 1.5s infinite; }
    .dot-idle      { background: #9ca3af; }
    @keyframes pulse-dot { 0%,100%{opacity:1;box-shadow:0 0 0 0 rgba(22,163,74,.4);} 50%{opacity:.8;box-shadow:0 0 0 5px rgba(22,163,74,0);} }
    .sisa-badge { background:#dcfce7;color:#15803d;border-radius:50px;padding:.2rem .65rem;font-size:.73rem;font-weight:700; }
    html[data-bs-theme="dark"] .sisa-badge { background:rgba(22,163,74,.2);color:#4ade80; }
    .avatar-sm { width:30px;height:30px;border-radius:50%;background:#e8f0fe;color:#2563eb;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.82rem;flex-shrink:0; }
    html[data-bs-theme="dark"] .avatar-sm { background:rgba(37,99,235,.25);color:#93c5fd; }
    .refresh-dot { width: 7px; height: 7px; border-radius: 50%; background: #16a34a; display: inline-block; animation: pulse 2s infinite; }
    @keyframes pulse { 0%,100%{opacity:1;} 50%{opacity:.4;} }

    /* ── Urgent badge ── */
    .badge-urgent { background: #fdecea; color: #dc2626; border-radius: 50px; padding: 0.2rem 0.65rem; font-size: 0.72rem; font-weight: 700; }
    .badge-warn   { background: #fef3e2; color: #b45309; border-radius: 50px; padding: 0.2rem 0.65rem; font-size: 0.72rem; font-weight: 700; }
    html[data-bs-theme="dark"] .badge-urgent { background: rgba(220,38,38,.2); color: #fca5a5; }
    html[data-bs-theme="dark"] .badge-warn   { background: rgba(180,83,9,.2);  color: #fcd34d; }
</style>

<div class="page-heading">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h3 class="mb-0">Dashboard Kasir</h3>
            <div class="small mt-1" style="color:var(--k-text-muted);">
                {{ $now->translatedFormat('l, d F Y') }} · {{ $now->format('H:i') }} WIB
            </div>
        </div>
        <div class="d-flex align-items-center gap-2 small" style="color:var(--k-text-muted);">
            <span class="refresh-dot"></span>Waktu main diperbarui real-time
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ═══════════════════════════════
         4 STAT CARDS
    ═══════════════════════════════ --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:rgba(37,99,235,.12);">
                    <i class="bi bi-calendar-check" style="color:#2563eb;"></i>
                </div>
                <div>
                    <div class="stat-label">Booking Hari Ini</div>
                    <div class="stat-value">{{ $bookingHariIni }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:rgba(22,163,74,.12);">
                    <i class="bi bi-cash-stack" style="color:#16a34a;"></i>
                </div>
                <div>
                    <div class="stat-label">Pendapatan Hari Ini</div>
                    <div class="stat-value" style="font-size:1.1rem;">Rp{{ number_format($pendapatanHariIni, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:rgba(217,119,6,.12);">
                    <i class="bi bi-hourglass-split" style="color:#d97706;"></i>
                </div>
                <div>
                    <div class="stat-label">Menunggu Approval</div>
                    <div class="stat-value">{{ $pendingApproval }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:rgba(220,38,38,.12);">
                    <i class="bi bi-credit-card" style="color:#dc2626;"></i>
                </div>
                <div>
                    <div class="stat-label">DP Belum Dikonfirmasi</div>
                    <div class="stat-value">{{ $pendingPembayaran }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════
         MONITORING LAPANGAN
    ═══════════════════════════════ --}}
    <div class="d-flex align-items-center gap-2 mb-3">
        <i class="bi bi-display section-heading" style="font-size:1rem;"></i>
        <h6 class="mb-0 section-heading">Monitoring Lapangan — Sekarang</h6>
    </div>
    <div class="row g-2 mb-4">
        @foreach ($lapanganList as $lapangan)
            @php
                $bk     = $sedangBerlangsung->get($lapangan->id);
                $sedang = $bk !== null;
                $sisaDetik = 0;
                $totalDetik = 1;
                if ($sedang) {
                    $jamSelesai  = \Carbon\Carbon::parse($bk->tanggal . ' ' . $bk->jam_selesai);
                    $jamMulai    = \Carbon\Carbon::parse($bk->tanggal . ' ' . $bk->jam_mulai);
                    $sisaDetik   = max((int) $now->diffInSeconds($jamSelesai, false), 0);
                    $totalDetik  = max((int) $jamMulai->diffInSeconds($jamSelesai), 1);
                }
            @endphp
            <div class="col-12 col-sm-6 col-xl-4">
                @if ($sedang)
                    <div class="monitor-card p-3 h-100"
                         x-data="monitorTimer({{ $sisaDetik }}, {{ $totalDetik }})"
                         x-init="mulai()">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div>
                                <span class="monitor-name">{{ $lapangan->nama_lapang }}</span>
                                <span class="monitor-cat ms-1">· {{ $lapangan->kategoriOlahraga->nama_kategori ?? '' }}</span>
                            </div>
                            <span class="status-dot dot-active"></span>
                        </div>
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <div class="avatar-sm">{{ strtoupper(substr($bk->customer->name, 0, 1)) }}</div>
                            <div>
                                <div class="monitor-player">{{ $bk->customer->name }}</div>
                                <div class="monitor-jam">{{ \Carbon\Carbon::parse($bk->jam_mulai)->format('H:i') }}–{{ \Carbon\Carbon::parse($bk->jam_selesai)->format('H:i') }}</div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span style="font-size:0.72rem;color:#16a34a;font-weight:600;">
                                <i class="bi bi-circle-fill me-1" style="font-size:0.45rem;"></i>Berlangsung
                            </span>
                            <span class="sisa-badge">
                                <i class="bi bi-hourglass-split me-1"></i>
                                <span x-text="formatSisa()"></span>
                            </span>
                        </div>
                        {{-- Progress bar --}}
                        <div style="background:rgba(22,163,74,0.12);border-radius:50px;height:5px;overflow:hidden;">
                            <div style="background:#16a34a;height:100%;border-radius:50px;transition:width 1s linear;"
                                 :style="`width:${pct}%;`"></div>
                        </div>
                    </div>
                @else
                    <div class="monitor-card p-3 h-100">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div>
                                <span class="monitor-name">{{ $lapangan->nama_lapang }}</span>
                                <span class="monitor-cat ms-1">· {{ $lapangan->kategoriOlahraga->nama_kategori ?? '' }}</span>
                            </div>
                            <span class="status-dot dot-idle"></span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-door-open monitor-idle"></i>
                            <span class="monitor-idle">Kosong</span>
                        </div>
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    {{-- ═══════════════════════════════
         3 NOTIF CARDS (link ke halaman masing-masing)
    ═══════════════════════════════ --}}
    <h6 class="section-heading mb-3"><i class="bi bi-bell me-2"></i>Notifikasi Aktif</h6>    <div class="row g-3 mb-4">

        {{-- Notif: Permintaan Booking --}}
        <div class="col-12 col-md-4">
            <a href="{{ route('kasir.booking.index') }}" class="notif-card d-flex">
                <div class="d-flex align-items-center gap-3 flex-grow-1">
                    <div class="notif-icon" style="background:rgba(234,179,8,.12);">
                        <i class="bi bi-calendar-plus" style="color:#d97706;"></i>
                    </div>
                    <div>
                        <div class="notif-label">Permintaan Booking</div>
                        <div class="notif-value">
                            {{ $pendingApproval }}
                            @if($pendingApproval > 0)
                                <span class="badge-warn ms-1">Perlu aksi</span>
                            @endif
                        </div>
                    </div>
                </div>
                <i class="bi bi-arrow-right notif-arrow"></i>
            </a>
        </div>

        {{-- Notif: Konfirmasi DP --}}
        <div class="col-12 col-md-4">
            <a href="{{ route('kasir.booking.index') }}#konfirmasi-dp" class="notif-card d-flex">
                <div class="d-flex align-items-center gap-3 flex-grow-1">
                    <div class="notif-icon" style="background:rgba(220,38,38,.12);">
                        <i class="bi bi-cash-coin" style="color:#dc2626;"></i>
                    </div>
                    <div>
                        <div class="notif-label">Konfirmasi DP</div>
                        <div class="notif-value">
                            {{ $pendingPembayaran }}
                            @if($dpMendekat > 0)
                                <span class="badge-urgent ms-1">{{ $dpMendekat }} segera!</span>
                            @elseif($pendingPembayaran > 0)
                                <span class="badge-warn ms-1">Perlu aksi</span>
                            @endif
                        </div>
                    </div>
                </div>
                <i class="bi bi-arrow-right notif-arrow"></i>
            </a>
        </div>

        {{-- Notif: Reschedule --}}
        <div class="col-12 col-md-4">
            <a href="{{ route('kasir.reschedule.index') }}" class="notif-card d-flex">
                <div class="d-flex align-items-center gap-3 flex-grow-1">
                    <div class="notif-icon" style="background:rgba(124,58,237,.12);">
                        <i class="bi bi-arrow-repeat" style="color:#7c3aed;"></i>
                    </div>
                    <div>
                        <div class="notif-label">Reschedule Customer</div>
                        <div class="notif-value">
                            {{ $pendingReschedule }}
                            @if($pendingReschedule > 0)
                                <span class="badge-warn ms-1">Menunggu</span>
                            @endif
                        </div>
                    </div>
                </div>
                <i class="bi bi-arrow-right notif-arrow"></i>
            </a>
        </div>
    </div>

    {{-- ═══════════════════════════════
         LAPORAN RINGKAS + CHART (2 kolom)
    ═══════════════════════════════ --}}
    <div class="row g-3">

        {{-- Tabel 5 transaksi terakhir hari ini --}}
        <div class="col-12 col-xl-7">
            <div class="laporan-card">
                <div class="card-head">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-receipt section-heading"></i>
                        <h6 class="mb-0 section-heading">Transaksi Hari Ini</h6>
                    </div>
                    <a href="{{ route('kasir.laporan.index') }}"
                       class="btn btn-sm btn-outline-secondary"
                       style="font-size:0.8rem;">
                        Laporan Lengkap <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th class="ps-3">Pelanggan</th>
                                <th>Lapangan</th>
                                <th>Jam Main</th>
                                <th class="pe-3 text-end">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($transaksiTerakhir as $p)
                                <tr>
                                    <td class="ps-3" style="font-weight:600;">{{ $p->booking->customer->name }}</td>
                                    <td style="color:var(--k-text-muted);font-size:0.82rem;">{{ $p->booking->lapangan->nama_lapang }}</td>
                                    <td style="color:var(--k-text-muted);font-size:0.82rem;">
                                        {{ \Carbon\Carbon::parse($p->booking->jam_mulai)->format('H:i') }}–{{ \Carbon\Carbon::parse($p->booking->jam_selesai)->format('H:i') }}
                                    </td>
                                    <td class="pe-3 text-end" style="font-weight:700;">Rp{{ number_format($p->jumlah, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4" style="color:var(--k-text-muted);">
                                        <i class="bi bi-inbox d-block mb-1" style="font-size:1.3rem;"></i>
                                        Belum ada transaksi hari ini
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if($totalHariIni > 0)
                        <tfoot>
                            <tr style="border-top:2px solid var(--k-border);">
                                <td colspan="3" class="ps-3 fw-bold" style="color:var(--k-text);padding:0.7rem 0.85rem;">Total Hari Ini</td>
                                <td class="pe-3 text-end fw-bold" style="color:#16a34a;padding:0.7rem 0.85rem;">Rp{{ number_format($totalHariIni, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>

        {{-- Chart pendapatan 7 hari --}}
        <div class="col-12 col-xl-5">
            <div class="chart-card h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-bar-chart section-heading"></i>
                        <h6 class="mb-0 section-heading">Pendapatan 7 Hari</h6>
                    </div>
                    <a href="{{ route('kasir.laporan.index') }}"
                       style="font-size:0.78rem;color:#ef7d2d;text-decoration:none;font-weight:700;">
                        Detail <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                @php $maxChart = $chartMingguan->max('total') ?: 1; @endphp
                @foreach ($chartMingguan as $item)
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="bar-label">{{ $item['label'] }}</span>
                        <div class="bar-track">
                            <div class="bar-fill" style="width:{{ round($item['total']/$maxChart*100) }}%;"></div>
                        </div>
                        <span class="bar-value">Rp{{ number_format($item['total'],0,',','.') }}</span>
                    </div>
                @endforeach

                <hr style="border-color:var(--k-border);margin:1rem 0 0.75rem;">
                <div class="d-flex justify-content-between align-items-center">
                    <span style="font-size:0.8rem;color:var(--k-text-muted);">Total 7 hari</span>
                    <span style="font-size:0.95rem;font-weight:800;color:var(--k-text);">
                        Rp{{ number_format($chartMingguan->sum('total'), 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
function monitorTimer(sisaDetikAwal, totalDetik) {
    return {
        sisa: sisaDetikAwal,
        total: totalDetik,
        pct: totalDetik > 0 ? Math.round(sisaDetikAwal / totalDetik * 100) : 0,

        mulai() {
            const iv = setInterval(() => {
                if (this.sisa <= 0) { clearInterval(iv); this.sisa = 0; this.pct = 0; return; }
                this.sisa--;
                this.pct = Math.round(this.sisa / this.total * 100);
            }, 1000);
        },

        formatSisa() {
            const j = Math.floor(this.sisa / 3600);
            const m = Math.floor((this.sisa % 3600) / 60);
            const d = this.sisa % 60;
            if (j > 0) return j + 'j ' + String(m).padStart(2,'0') + 'm';
            return String(m).padStart(2,'0') + ':' + String(d).padStart(2,'0');
        }
    };
}
</script>
@endsection
