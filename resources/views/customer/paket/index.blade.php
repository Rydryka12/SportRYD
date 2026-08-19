@extends('layouts.customer')

@section('content')
<style>
    .text-navy { color: #12244a; }
    .text-orange { color: #ef7d2d; }

    .paket-card {
        border-radius: 1rem;
        border: 1px solid #eef0f2;
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
    }
    .paket-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 .5rem 1.25rem rgba(0,0,0,.08);
        border-color: #dfe6f5;
    }

    .badge-kuota { background-color: #e8f0fe; color: #2563eb; }
    .badge-jadwal-tetap { background-color: #f3e8fd; color: #7c3aed; }
    .badge-tipe {
        font-size: 0.78rem;
        font-weight: 600;
        border-radius: 50px;
        padding: 0.35rem 0.85rem;
    }

    .btn-pilih-paket {
        background-color: #ef7d2d;
        color: white;
        border: none;
        font-weight: 600;
        border-radius: 0.6rem;
        transition: all 0.2s ease;
    }
    .btn-pilih-paket:hover {
        background-color: #d96b22;
        color: white;
        transform: translateY(-1px);
    }

    .btn-segera-hadir {
        background-color: #f1f3f5;
        color: #adb5bd;
        border: none;
        font-weight: 600;
        border-radius: 0.6rem;
        cursor: not-allowed;
    }
</style>

<a href="{{ route('customer.beranda') }}" class="text-muted small mb-3 d-inline-block text-decoration-none">
    <i class="bi bi-arrow-left"></i> Kembali
</a>

<div class="card border-0 shadow-sm p-4" style="max-width: 800px; margin: 0 auto; border-radius: 1.25rem;">
    <h4 class="fw-bold text-navy mb-1">Paket Langganan {{ $lapangan->kategoriOlahraga->nama_kategori }}</h4>
    <p class="text-muted mb-4">Pilih paket langganan yang sesuai kebutuhan Anda</p>

    <p class="fw-semibold text-navy mb-3">Pilih Jenis Paket</p>

    <div class="d-flex flex-column gap-3">
        @forelse ($paketList as $paket)
            @php
                $isKuota = $paket->tipe_paket === 'Kuota';
                $targetUrl = $isKuota
                    ? route('customer.paket.kuota.create', $paket)
                    : route('customer.paket.jadwal-tetap.create', $paket);
            @endphp

            <a href="{{ $targetUrl }}" class="text-decoration-none text-dark">
                <div class="card paket-card p-3 p-md-4">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                <h6 class="fw-bold text-navy mb-0">{{ $paket->nama_paket }}</h6>
                                <span class="badge-tipe {{ $isKuota ? 'badge-kuota' : 'badge-jadwal-tetap' }}">
                                    {{ $paket->tipe_paket }}
                                </span>
                            </div>
                            <p class="text-muted small mb-1">
                                {{ $paket->jumlah_sesi }} sesi &middot; {{ $paket->durasi_jam_per_sesi }} jam/sesi &middot; {{ $paket->masa_berlaku_hari }} hari
                            </p>
                            <p class="small mb-0" style="color:#2563eb;">
                                {{ $isKuota ? 'Lapangan dipilih tiap kali main' : 'Lapangan & jadwal dikunci sejak awal' }}
                            </p>
                        </div>
                        <h5 class="fw-bold text-orange mb-0">Rp {{ number_format($paket->harga, 0, ',', '.') }}</h5>
                    </div>
                </div>
            </a>
        @empty
            <p class="text-muted text-center py-5 mb-0">Belum ada paket langganan untuk kategori ini.</p>
        @endforelse
    </div>
</div>
@endsection