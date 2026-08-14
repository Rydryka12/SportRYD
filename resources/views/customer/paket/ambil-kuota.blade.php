@extends('layouts.customer')

@section('content')
<style>
    .text-navy { color: #12244a; }
    .text-orange { color: #ef7d2d; }

    .konfirmasi-card {
        border-radius: 1.25rem;
        max-width: 520px;
        margin: 0 auto;
        transition: box-shadow 0.3s ease;
    }
    .konfirmasi-card:hover {
        box-shadow: 0 .75rem 1.5rem rgba(0,0,0,.1) !important;
    }

    .badge-kuota {
        background-color: #e8f0fe;
        color: #2563eb;
        font-size: 0.78rem;
        font-weight: 600;
        border-radius: 50px;
        padding: 0.35rem 0.85rem;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        font-size: 0.92rem;
        padding: 0.4rem 0;
    }
    .detail-row dt { color: #6c757d; margin: 0; }
    .detail-row dd { font-weight: 600; color: #212529; margin: 0; }

    .info-note {
        background-color: #f8f9fa;
        border-radius: 0.75rem;
        padding: 0.85rem 1rem;
        font-size: 0.88rem;
        color: #6c757d;
    }

    .total-row {
        border-top: 1px dashed #dee2e6;
        padding-top: 1rem;
        margin-top: 0.5rem;
    }

    .btn-navy-submit {
        background-color: #12244a;
        color: white;
        border: none;
        font-weight: 600;
        border-radius: 0.75rem;
        transition: all 0.2s ease;
    }
    .btn-navy-submit:hover {
        background-color: #0d1a35;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 6px 14px rgba(18,36,74,0.3);
    }
    .btn-navy-submit:active { transform: translateY(0); }
</style>

<div style="max-width: 520px; margin: 0 auto;">
    <a href="javascript:history.back()" class="text-muted small mb-3 d-inline-block text-decoration-none">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<h4 class="fw-bold text-navy mb-4 text-center">Konfirmasi Paket</h4>

<div class="card konfirmasi-card border-0 shadow-sm p-4">

    <div class="d-flex justify-content-between align-items-start mb-3">
        <h5 class="fw-bold text-navy mb-0">{{ $paketLangganan->nama_paket }}</h5>
        <span class="badge-kuota">Kuota</span>
    </div>

    <dl class="mb-4">
        <div class="detail-row">
            <dt>Kategori</dt>
            <dd>{{ $paketLangganan->kategoriOlahraga->nama_kategori }}</dd>
        </div>
        <div class="detail-row">
            <dt>Jumlah Sesi</dt>
            <dd>{{ $paketLangganan->jumlah_sesi }}x</dd>
        </div>
        <div class="detail-row">
            <dt>Durasi per Sesi</dt>
            <dd>{{ $paketLangganan->durasi_jam_per_sesi }} jam</dd>
        </div>
        <div class="detail-row">
            <dt>Masa Berlaku</dt>
            <dd>{{ $paketLangganan->masa_berlaku_hari }} hari (s.d. {{ now()->addDays($paketLangganan->masa_berlaku_hari)->translatedFormat('d M Y') }})</dd>
        </div>
    </dl>

    <div class="info-note mb-4">
        <i class="bi bi-info-circle me-1"></i>
        Lapangan dipilih bebas tiap kali kamu mau main, selama masih ada sisa sesi dan lapangan tersedia.
    </div>

    <div class="d-flex justify-content-between align-items-center total-row mb-4">
        <span class="text-muted">Total Bayar</span>
        <span class="fs-4 fw-bold text-navy">Rp {{ number_format($paketLangganan->harga, 0, ',', '.') }}</span>
    </div>

    <form method="POST" action="{{ route('customer.paket.kuota.store', $paketLangganan) }}">
        @csrf
        <button type="submit" class="btn btn-navy-submit w-100 py-2">
            Ambil Paket &amp; Bayar
        </button>
    </form>
</div>
@endsection