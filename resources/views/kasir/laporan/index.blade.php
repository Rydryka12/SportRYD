@extends('layouts.kasir')

@section('content')
<div class="page-heading">
    <h3 class="mb-4">Laporan Harian</h3>

    <section class="section">
        <div class="card">
            <div class="card-body">
                <p class="text-muted mb-1">{{ now()->translatedFormat('d F Y') }}</p>
                <h2 class="fw-bold text-navy mb-1">Rp{{ number_format($totalHariIni, 0, ',', '.') }}</h2>
                <p class="text-muted mb-4">{{ $jumlahTransaksiHariIni }} transaksi</p>

                <p class="fw-semibold mb-2">Pendapatan 7 Hari Terakhir</p>
                @php $maxChart = max($chartMingguan->max('total'), 1); @endphp
                <div class="d-flex align-items-end gap-2" style="height: 120px; max-width: 400px;">
                    @foreach ($chartMingguan as $c)
                        <div class="flex-fill text-center">
                            <div class="bg-primary rounded-top mx-auto" style="width: 70%; height: {{ max(4, ($c['total'] / $maxChart) * 100) }}px;"></div>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($c['tanggal'])->translatedFormat('D') }}</small>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
