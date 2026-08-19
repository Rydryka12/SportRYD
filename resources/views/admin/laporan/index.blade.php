@extends('layouts.app')

@section('title', 'Laporan')
@section('sblaporan', 'active')
@section('content')
<div class="page-heading">
    <h3 class="mb-4">Laporan Pendapatan</h3>

    <section class="section">
        <div class="row g-4">
            {{-- Kolom Kiri: Tampilan Kasir --}}
            <div class="col-lg-5">
                <div class="card h-100">
                    <div class="card-header"><h5 class="mb-0">Hari Ini (Tampilan Kasir)</h5></div>
                    <div class="card-body">
                        <p class="text-muted mb-1">{{ now()->translatedFormat('d F Y') }}</p>
                        <h2 class="fw-bold text-navy mb-1">Rp{{ number_format($totalHariIni, 0, ',', '.') }}</h2>
                        <p class="text-muted mb-4">{{ $jumlahTransaksiHariIni }} transaksi</p>

                        <p class="fw-semibold mb-2">Pendapatan 7 Hari Terakhir</p>
                        @php $maxChart = max($chartMingguan->max('total'), 1); @endphp
                        <div class="d-flex align-items-end gap-2" style="height: 120px;">
                            @foreach ($chartMingguan as $c)
                                <div class="flex-fill text-center">
                                    <div class="bg-primary rounded-top mx-auto" style="width: 70%; height: {{ max(4, ($c['total'] / $maxChart) * 100) }}px;"></div>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($c['tanggal'])->translatedFormat('D') }}</small>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan: Tampilan Admin --}}
            <div class="col-lg-7">
                <div class="card h-100">
                    <div class="card-header"><h5 class="mb-0">Periode (Tampilan Admin)</h5></div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('admin.laporan.index') }}" class="row g-2 mb-4">
                            <div class="col-md-4">
                                <label class="form-label">Dari</label>
                                <input type="date" name="mulai" value="{{ $periodeMulai }}" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Sampai</label>
                                <input type="date" name="selesai" value="{{ $periodeSelesai }}" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Lapangan</label>
                                <select name="lapangan_id" class="form-select">
                                    <option value="">Semua Lapangan</option>
                                    @foreach ($lapanganList as $lap)
                                        <option value="{{ $lap->id }}" @selected($lapanganId == $lap->id)>{{ $lap->nama_lapang }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-sm">Terapkan Filter</button>
                            </div>
                        </form>

                        <h2 class="fw-bold text-navy mb-4">Rp{{ number_format($totalPeriode, 0, ',', '.') }}</h2>

                        <p class="fw-semibold mb-2">Rekap Paket Langganan (per Kategori & Tipe)</p>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr><th>Kategori & Tipe</th><th>Jumlah</th><th>Total</th></tr>
                                </thead>
                                <tbody>
                                    @forelse ($rekapPaket as $label => $data)
                                        <tr>
                                            <td>{{ $label }}</td>
                                            <td>{{ $data['jumlah'] }}</td>
                                            <td>Rp{{ number_format($data['total'], 0, ',', '.') }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" class="text-center text-muted">Belum ada paket diambil di periode ini.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
