@extends('layouts.app')
@section('title', 'Dashboard')
@section('sbdashboard', 'active')



@section('content')
<div class="page-heading">
    <h3 class="mb-4">Dashboard</h3>

    <section class="section">
        <div class="row g-3 mb-4">
            <div class="col-md-3 col-6">
                <div class="card">
                    <div class="card-body">
                        <p class="text-muted small mb-1">Lapangan Aktif</p>
                        <h3 class="fw-bold mb-0" style="color:#12244a;">{{ $lapanganAktif }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card">
                    <div class="card-body">
                        <p class="text-muted small mb-1">Booking Hari Ini</p>
                        <h3 class="fw-bold mb-0" style="color:#12244a;">{{ $bookingHariIni }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card">
                    <div class="card-body">
                        <p class="text-muted small mb-1">Pelanggan</p>
                        <h3 class="fw-bold mb-0" style="color:#12244a;">{{ $totalPelanggan }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card">
                    <div class="card-body">
                        <p class="text-muted small mb-1">Pendapatan Hari Ini</p>
                        <h4 class="fw-bold mb-0" style="color:#12244a;">Rp{{ number_format($pendapatanHariIni, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h5 class="mb-0">Pendapatan Mingguan</h5></div>
            <div class="card-body">
                @php $maxChart = max($chartMingguan->max('total'), 1); @endphp
                <div class="d-flex align-items-end gap-3" style="height: 180px;">
                    @foreach ($chartMingguan as $c)
                        <div class="flex-fill text-center">
                            <div class="bg-primary rounded-top mx-auto" style="width: 60%; height: {{ max(6, ($c['total'] / $maxChart) * 160) }}px;"></div>
                            <small class="text-muted d-block mt-1">{{ \Carbon\Carbon::parse($c['tanggal'])->translatedFormat('D') }}</small>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
</div>
@endsection