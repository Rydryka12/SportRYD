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

    .k-card {
        background: var(--k-bg-card);
        border: 1px solid var(--k-border);
        border-radius: 0.85rem;
        overflow: hidden;
    }
    .k-card .k-card-head {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--k-border);
        display: flex; align-items: center; justify-content: space-between;
    }
    .k-card table thead th {
        background: var(--k-thead-bg) !important;
        color: var(--k-thead-text) !important;
        font-size: 0.79rem; font-weight: 600;
        padding: 0.65rem 0.85rem;
        border-bottom: 1px solid var(--k-border);
    }
    .k-card table tbody tr { border-bottom: 1px solid var(--k-border); }
    .k-card table tbody tr:last-child { border-bottom: none; }
    .k-card table tbody tr:hover { background: var(--k-hover-bg); }
    .k-card table tbody td { color: var(--k-text); padding: 0.7rem 0.85rem; font-size: 0.88rem; vertical-align: middle; }
    .k-card table tfoot td { padding: 0.75rem 0.85rem; border-top: 2px solid var(--k-border); font-weight: 700; color: var(--k-text); }

    .stat-mini {
        background: var(--k-bg-card);
        border: 1px solid var(--k-border);
        border-radius: 0.85rem;
        padding: 1.1rem 1.25rem;
        display: flex; align-items: center; gap: 0.85rem;
    }
    .stat-mini-icon {
        width: 44px; height: 44px; border-radius: 0.65rem;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem; flex-shrink: 0;
    }
    .stat-mini-label { font-size: 0.75rem; color: var(--k-text-muted); font-weight: 600; margin-bottom: 0.15rem; }
    .stat-mini-value { font-size: 1.25rem; font-weight: 800; color: var(--k-text); line-height: 1.1; }

    .section-heading { color: var(--k-text-head); font-weight: 700; }

    .bar-wrap { display: flex; align-items: center; gap: 8px; margin-bottom: 8px; }
    .bar-day  { font-size: 0.75rem; color: var(--k-text-muted); min-width: 75px; }
    .bar-track { flex:1; background: var(--k-thead-bg); border-radius: 4px; height: 8px; overflow: hidden; }
    .bar-fill  { background: #ef7d2d; height: 100%; border-radius: 4px; transition: width .4s ease; }
    .bar-val   { font-size: 0.75rem; font-weight: 700; color: var(--k-text); min-width: 90px; text-align: right; }

    /* Status badges */
    .badge-selesai    { background: #f1f3f5; color: #6c757d; border-radius: 50px; padding: .25rem .7rem; font-size: .75rem; font-weight: 600; }
    .badge-akan-datang{ background: #e8f0fe; color: #2563eb; border-radius: 50px; padding: .25rem .7rem; font-size: .75rem; font-weight: 600; }
    .badge-dibatalkan { background: #fdecea; color: #dc2626; border-radius: 50px; padding: .25rem .7rem; font-size: .75rem; font-weight: 600; }
    .badge-metode     { background: var(--k-thead-bg); color: var(--k-text); border: 1px solid var(--k-border); border-radius: 50px; padding: .2rem .65rem; font-size: .75rem; font-weight: 600; }

    .date-nav-btn {
        border: 1px solid var(--k-border);
        background: var(--k-bg-card);
        color: var(--k-text);
        border-radius: 0.5rem;
        padding: 0.35rem 0.75rem;
        font-size: 0.85rem;
        text-decoration: none;
        transition: all .15s;
    }
    .date-nav-btn:hover { background: var(--k-thead-bg); color: var(--k-text); }
</style>

<div class="page-heading">

    {{-- ── Header + navigasi tanggal ── --}}
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
        <div>
            <h3 class="mb-0">Laporan Harian</h3>
            <div class="small mt-1" style="color:var(--k-text-muted);">
                {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('kasir.laporan.index', ['tanggal' => \Carbon\Carbon::parse($tanggal)->subDay()->toDateString()]) }}"
               class="date-nav-btn"><i class="bi bi-chevron-left me-1"></i>Kemarin</a>

            <form method="GET" action="{{ route('kasir.laporan.index') }}" class="d-flex align-items-center gap-1">
                <input type="date" name="tanggal" value="{{ $tanggal }}"
                       class="form-control form-control-sm"
                       style="border-radius:.5rem;border:1px solid var(--k-border);background:var(--k-bg-card);color:var(--k-text);"
                       onchange="this.form.submit()">
            </form>

            @if($tanggal < now()->toDateString())
                <a href="{{ route('kasir.laporan.index', ['tanggal' => \Carbon\Carbon::parse($tanggal)->addDay()->toDateString()]) }}"
                   class="date-nav-btn">Besok <i class="bi bi-chevron-right ms-1"></i></a>
            @endif
        </div>
    </div>

    {{-- ── 4 Stat mini ── --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="stat-mini">
                <div class="stat-mini-icon" style="background:rgba(22,163,74,.12);">
                    <i class="bi bi-cash-stack" style="color:#16a34a;"></i>
                </div>
                <div>
                    <div class="stat-mini-label">Total Pendapatan</div>
                    <div class="stat-mini-value" style="font-size:1.1rem;">Rp{{ number_format($totalHariItu, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="stat-mini">
                <div class="stat-mini-icon" style="background:rgba(37,99,235,.12);">
                    <i class="bi bi-receipt" style="color:#2563eb;"></i>
                </div>
                <div>
                    <div class="stat-mini-label">Jumlah Transaksi</div>
                    <div class="stat-mini-value">{{ $jumlahTransaksiHariItu }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="stat-mini">
                <div class="stat-mini-icon" style="background:rgba(124,58,237,.12);">
                    <i class="bi bi-calendar-check" style="color:#7c3aed;"></i>
                </div>
                <div>
                    <div class="stat-mini-label">Total Sesi</div>
                    <div class="stat-mini-value">{{ $bookingSelesai->count() }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="stat-mini">
                <div class="stat-mini-icon" style="background:rgba(220,38,38,.12);">
                    <i class="bi bi-x-circle" style="color:#dc2626;"></i>
                </div>
                <div>
                    <div class="stat-mini-label">Booking Dibatalkan</div>
                    <div class="stat-mini-value">{{ $bookingDibatalkan->count() }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Rekap per lapangan + chart (2 kolom) ── --}}
    <div class="row g-3 mb-4">

        {{-- Rekap per lapangan --}}
        <div class="col-12 col-xl-5">
            <div class="k-card h-100">
                <div class="k-card-head">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-pie-chart section-heading"></i>
                        <h6 class="mb-0 section-heading">Rekap per Lapangan</h6>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th class="ps-3">Lapangan</th>
                                <th class="text-center">Sesi</th>
                                <th class="pe-3 text-end">Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($rekapLapangan as $nama => $data)
                                <tr>
                                    <td class="ps-3" style="font-weight:600;">{{ $nama }}</td>
                                    <td class="text-center" style="color:var(--k-text-muted);">{{ $data['jumlah_transaksi'] }}</td>
                                    <td class="pe-3 text-end" style="font-weight:700;color:#16a34a;">Rp{{ number_format($data['total'], 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4" style="color:var(--k-text-muted);">
                                        <i class="bi bi-inbox d-block mb-1" style="font-size:1.2rem;"></i>
                                        Tidak ada data
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if($rekapLapangan->count() > 0)
                        <tfoot>
                            <tr>
                                <td class="ps-3">Total</td>
                                <td class="text-center">{{ $jumlahTransaksiHariItu }}</td>
                                <td class="pe-3 text-end" style="color:#16a34a;">Rp{{ number_format($totalHariItu, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>

        {{-- Chart 7 hari --}}
        <div class="col-12 col-xl-7">
            <div class="k-card h-100">
                <div class="k-card-head">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-bar-chart section-heading"></i>
                        <h6 class="mb-0 section-heading">Tren Pendapatan 7 Hari Terakhir</h6>
                    </div>
                    <span style="font-size:0.8rem;font-weight:700;color:#16a34a;">
                        Total: Rp{{ number_format($totalMinggu, 0, ',', '.') }}
                    </span>
                </div>
                <div class="p-3">
                    @php $maxChart = max($chartMingguan->max('total'), 1); @endphp
                    @foreach ($chartMingguan as $item)
                        <div class="bar-wrap">
                            <span class="bar-day">{{ $item['label'] }}</span>
                            <div class="bar-track">
                                <div class="bar-fill" style="width:{{ round($item['total']/$maxChart*100) }}%;
                                    {{ $item['tanggal'] === $tanggal ? 'background:#2563eb;' : '' }}"></div>
                            </div>
                            <span class="bar-val">Rp{{ number_format($item['total'],0,',','.') }}</span>
                        </div>
                    @endforeach
                    <div class="small mt-2" style="color:var(--k-text-muted);">
                        <span style="display:inline-block;width:12px;height:6px;background:#ef7d2d;border-radius:3px;"></span> Hari lain &nbsp;
                        <span style="display:inline-block;width:12px;height:6px;background:#2563eb;border-radius:3px;"></span> Tanggal dipilih
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Detail transaksi hari ini ── --}}
    <div class="k-card mb-4">
        <div class="k-card-head">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-list-ul section-heading"></i>
                <h6 class="mb-0 section-heading">Detail Transaksi — {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</h6>
            </div>
            <span style="font-size:0.82rem;color:var(--k-text-muted);">{{ $jumlahTransaksiHariItu }} transaksi</span>
        </div>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th class="ps-3">#</th>
                        <th>Pelanggan</th>
                        <th>Lapangan</th>
                        <th>Jam Main</th>
                        <th>Metode</th>
                        <th>Dikonfirmasi oleh</th>
                        <th class="pe-3 text-end">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pembayaranHariItu as $i => $p)
                        <tr>
                            <td class="ps-3" style="color:var(--k-text-muted);">{{ $i + 1 }}</td>
                            <td style="font-weight:600;">
                                {{ $p->booking->customer->name }}
                                <div style="font-size:0.75rem;color:var(--k-text-muted);">{{ $p->booking->customer->no_hp ?? '-' }}</div>
                            </td>
                            <td>
                                {{ $p->booking->lapangan->nama_lapang }}
                                <div style="font-size:0.75rem;color:var(--k-text-muted);">{{ $p->booking->lapangan->kategoriOlahraga->nama_kategori ?? '' }}</div>
                            </td>
                            <td style="color:var(--k-text-muted);">
                                {{ \Carbon\Carbon::parse($p->booking->tanggal)->translatedFormat('d M') }},
                                {{ \Carbon\Carbon::parse($p->booking->jam_mulai)->format('H:i') }}–{{ \Carbon\Carbon::parse($p->booking->jam_selesai)->format('H:i') }}
                            </td>
                            <td><span class="badge-metode">{{ $p->metode }}</span></td>
                            <td style="color:var(--k-text-muted);font-size:0.82rem;">{{ $p->dikonfirmasiOleh->name ?? '-' }}</td>
                            <td class="pe-3 text-end" style="font-weight:700;">Rp{{ number_format($p->jumlah, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4" style="color:var(--k-text-muted);">
                                <i class="bi bi-inbox d-block mb-1" style="font-size:1.3rem;"></i>
                                Tidak ada transaksi pada tanggal ini
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if($jumlahTransaksiHariItu > 0)
                <tfoot>
                    <tr>
                        <td colspan="6" class="ps-3">Total</td>
                        <td class="pe-3 text-end" style="color:#16a34a;">Rp{{ number_format($totalHariItu, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>

    {{-- ── Jadwal sesi hari ini ── --}}
    <div class="k-card mb-4">
        <div class="k-card-head">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-calendar3 section-heading"></i>
                <h6 class="mb-0 section-heading">Jadwal Sesi — {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</h6>
            </div>
            <span style="font-size:0.82rem;color:var(--k-text-muted);">{{ $bookingSelesai->count() }} sesi</span>
        </div>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th class="ps-3">Jam</th>
                        <th>Pelanggan</th>
                        <th>Lapangan</th>
                        <th>Durasi</th>
                        <th>Sumber</th>
                        <th class="pe-3">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bookingSelesai as $b)
                        @php
                            $durasi = abs(\Carbon\Carbon::parse($b->jam_mulai)->diffInHours(\Carbon\Carbon::parse($b->jam_selesai)));
                        @endphp
                        <tr>
                            <td class="ps-3" style="font-weight:600;">
                                {{ \Carbon\Carbon::parse($b->jam_mulai)->format('H:i') }}–{{ \Carbon\Carbon::parse($b->jam_selesai)->format('H:i') }}
                            </td>
                            <td>
                                {{ $b->customer->name }}
                                <div style="font-size:0.75rem;color:var(--k-text-muted);">{{ $b->customer->no_hp ?? '-' }}</div>
                            </td>
                            <td>
                                {{ $b->lapangan->nama_lapang }}
                                <div style="font-size:0.75rem;color:var(--k-text-muted);">{{ $b->lapangan->kategoriOlahraga->nama_kategori ?? '' }}</div>
                            </td>
                            <td style="color:var(--k-text-muted);">{{ $durasi }} jam</td>
                            <td>
                                <span class="badge-metode">{{ $b->sumber }}</span>
                            </td>
                            <td class="pe-3">
                                @if($b->status === 'Selesai')
                                    <span class="badge-selesai">Selesai</span>
                                @elseif($b->status === 'Akan Datang')
                                    <span class="badge-akan-datang">Akan Datang</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4" style="color:var(--k-text-muted);">
                                <i class="bi bi-inbox d-block mb-1" style="font-size:1.3rem;"></i>
                                Tidak ada sesi pada tanggal ini
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ── Booking dibatalkan ── --}}
    @if($bookingDibatalkan->count() > 0)
    <div class="k-card">
        <div class="k-card-head">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-x-circle" style="color:#dc2626;"></i>
                <h6 class="mb-0 section-heading">Booking Dibatalkan</h6>
            </div>
            <span style="font-size:0.82rem;color:#dc2626;font-weight:600;">{{ $bookingDibatalkan->count() }} booking</span>
        </div>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th class="ps-3">Pelanggan</th>
                        <th>Lapangan</th>
                        <th>Jam (Rencana)</th>
                        <th class="pe-3">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bookingDibatalkan as $b)
                        <tr>
                            <td class="ps-3" style="font-weight:600;">{{ $b->customer->name }}</td>
                            <td>{{ $b->lapangan->nama_lapang }}</td>
                            <td style="color:var(--k-text-muted);">
                                {{ \Carbon\Carbon::parse($b->jam_mulai)->format('H:i') }}–{{ \Carbon\Carbon::parse($b->jam_selesai)->format('H:i') }}
                            </td>
                            <td class="pe-3">
                                <span class="badge-dibatalkan">Dibatalkan</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

</div>
@endsection
