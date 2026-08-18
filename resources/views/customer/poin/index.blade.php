@extends('layouts.customer')

@section('content')
<h1 class="h3 fw-bold text-navy mb-1">Tukar Poin</h1>
<p class="text-muted mb-4">Saldo poin kamu: <span class="fw-bold text-navy">{{ $saldoPoin }}</span></p>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<ul class="nav nav-tabs mb-4" role="tablist">
    <li class="nav-item">
        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-voucher" type="button">Tukar ke Voucher</button>
    </li>
    <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-kuota" type="button">Tukar ke Kuota Sesi</button>
    </li>
</ul>

<div class="tab-content">
    {{-- TAB VOUCHER --}}
    <div class="tab-pane fade show active" id="tab-voucher">
        <div class="row g-3 mb-4">
            @forelse ($voucherKatalog as $voucher)
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <h6 class="fw-bold text-navy">{{ $voucher->nama_voucher }}</h6>
                            <p class="text-muted small mb-2">{{ $voucher->kategoriOlahraga->nama_kategori }} &middot; Potongan Rp{{ number_format($voucher->nilai_potongan, 0, ',', '.') }} &middot; berlaku {{ $voucher->masa_berlaku_hari }} hari</p>
                            <p class="fw-semibold mb-3">{{ $voucher->biaya_poin }} poin</p>
                            <form action="{{ route('customer.poin.tukar-voucher', $voucher) }}" method="POST" onsubmit="return confirm('Tukar poin ke voucher ini?')">
                                @csrf
                                <button type="submit" class="btn btn-orange-submit w-100" {{ $saldoPoin < $voucher->biaya_poin ? 'disabled' : '' }}>
                                    {{ $saldoPoin < $voucher->biaya_poin ? 'Poin belum cukup' : 'Tukar Sekarang' }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-muted">Belum ada voucher tersedia.</p>
            @endforelse
        </div>

        <h6 class="fw-bold text-navy mb-3">Voucher Aktif Saya</h6>
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                @forelse ($voucherAktifSaya as $v)
                    <div class="d-flex justify-content-between align-items-center p-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div>
                            <p class="fw-semibold mb-0">{{ $v->katalogVoucher->nama_voucher }}</p>
                            <small class="text-muted">Kode: {{ $v->kode_voucher }} &middot; berlaku s.d. {{ \Carbon\Carbon::parse($v->tanggal_expired)->translatedFormat('d M Y') }}</small>
                        </div>
                        <span class="badge bg-success">Aktif</span>
                    </div>
                @empty
                    <p class="text-muted p-3 mb-0">Belum ada voucher aktif.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- TAB KUOTA --}}
    <div class="tab-pane fade" id="tab-kuota">
        <div class="row g-3">
            @forelse ($tukarKuotaKatalog as $tk)
                @php
                    $punyaKuotaAktif = $kategoriKuotaAktif->contains($tk->kategori_id);
                    $poinCukup = $saldoPoin >= $tk->biaya_poin;
                @endphp
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <h6 class="fw-bold text-navy">{{ $tk->kategoriOlahraga->nama_kategori }}</h6>
                            <p class="text-muted small mb-2">+{{ $tk->jumlah_sesi_didapat }} sesi kuota</p>
                            <p class="fw-semibold mb-3">{{ $tk->biaya_poin }} poin</p>

                            <form action="{{ route('customer.poin.tukar-kuota', $tk) }}" method="POST" onsubmit="return confirm('Tukar poin ke kuota sesi ini?')">
                                @csrf
                                <button type="submit" class="btn btn-orange-submit w-100" {{ (!$punyaKuotaAktif || !$poinCukup) ? 'disabled' : '' }}>
                                    Tukar Sekarang
                                </button>
                            </form>

                            @if (!$punyaKuotaAktif)
                                <small class="text-danger d-block mt-2">Kamu belum punya Paket Kuota aktif di kategori ini.</small>
                            @elseif (!$poinCukup)
                                <small class="text-danger d-block mt-2">Poin belum cukup.</small>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-muted">Belum ada rasio tukar kuota tersedia.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection