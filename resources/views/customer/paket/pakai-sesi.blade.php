@extends('layouts.customer')

@section('content')
<style>
    .slot-btn { border-radius: 0.75rem; padding: 0.65rem 0.5rem; font-weight: 500; font-size: 0.9rem; min-height: 58px; transition: all 0.2s ease; }
    .slot-kosong { background: white; border: 1px solid #dee2e6; color: #212529; }
    .slot-kosong:hover { border-color: #ef7d2d; background-color: #fff6f0; transform: translateY(-3px); }
    .slot-dipilih { background-color: #12244a; border: 1px solid #12244a; color: white; transform: translateY(-2px) scale(1.03); }
    .slot-terisi { background-color: #fdecea; border: 1px solid #f5c6cb; color: #c94b4b; cursor: not-allowed; opacity: 0.85; }
    .slot-nonaktif { background-color: #f8f9fa; border: 1px solid #eef0f2; color: #ced4da; cursor: not-allowed; opacity: 0.6; }
    .btn-navy { background-color: #12244a; color: white; border: none; font-weight: 600; border-radius: 0.75rem; padding: 0.65rem 1.5rem; }
    .btn-navy:hover { background-color: #0d1a35; color: white; }
    [x-cloak] { display: none !important; }
</style>

<a href="{{ route('customer.riwayat') }}" class="text-muted small mb-3 d-inline-block text-decoration-none">
    <i class="bi bi-arrow-left"></i> Kembali
</a>

<div class="card border-0 shadow-sm p-4">
    <h5 class="fw-bold text-navy mb-1">Pakai Sesi — {{ $langganan->paketLangganan->kategoriOlahraga->nama_kategori }}</h5>
    <p class="text-muted mb-4">Sisa {{ $langganan->sisa_sesi }} sesi &middot; {{ $langganan->paketLangganan->nama_paket }}</p>

    <form method="GET" action="{{ route('customer.paket.pakai-sesi.create', $langganan) }}" class="row g-3 mb-4">
        <div class="col-md-6">
            <label class="form-label">Lapangan</label>
            <select name="lapangan_id" class="form-select" onchange="this.form.submit()">
                <option value="">-- Pilih Lapangan --</option>
                @foreach ($lapanganList as $lap)
                    <option value="{{ $lap->id }}" @selected($lapanganId == $lap->id)>{{ $lap->nama_lapang }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">Tanggal</label>
            <input type="date" name="tanggal" value="{{ $tanggal }}" min="{{ now()->toDateString() }}" class="form-control" onchange="this.form.submit()">
        </div>
    </form>

    @if ($lapanganId)
        @php
            $slotTerisi = [];
            foreach ($bookingHariItu as $b) {
                $mulai = \Carbon\Carbon::parse($b->jam_mulai)->hour;
                $selesai = \Carbon\Carbon::parse($b->jam_selesai)->hour;
                for ($j = $mulai; $j < $selesai; $j++) { $slotTerisi[] = $j; }
            }
        @endphp

        <div x-data="{
                durasi: {{ $durasi }},
                jamTutup: 22,
                start: null,
                terisi: {{ json_encode($slotTerisi) }},
                bisaJadiStart(jam) {
                    if (jam + this.durasi > this.jamTutup) return false;
                    for (let i = 0; i < this.durasi; i++) {
                        if (this.terisi.includes(jam + i)) return false;
                    }
                    return true;
                },
                pilih(jam) {
                    if (!this.bisaJadiStart(jam)) return;
                    this.start = (this.start === jam) ? null : jam;
                },
                dalamBlok(jam) {
                    if (this.start === null) return false;
                    return jam >= this.start && jam < this.start + this.durasi;
                }
             }">
            <p class="fw-semibold text-navy mb-1">Pilih Jam</p>
            <p class="text-muted small mb-3">
                Setiap klik memilih blok <strong>{{ $durasi }} jam</strong> sekaligus
                (1 klik = {{ $durasi }} sesi dikurangi).
            </p>
            <div class="row g-2 mb-3">
                @for ($jam = 6; $jam <= 22; $jam++)
                    <div class="col-6 col-sm-3 col-md-2">
                        <button type="button"
                                @click="pilih({{ $jam }})"
                                :disabled="terisi.includes({{ $jam }}) || (!dalamBlok({{ $jam }}) && !bisaJadiStart({{ $jam }}))"
                                :class="{
                                    'slot-terisi':  terisi.includes({{ $jam }}),
                                    'slot-dipilih': dalamBlok({{ $jam }}),
                                    'slot-kosong':  !terisi.includes({{ $jam }}) && !dalamBlok({{ $jam }}) && bisaJadiStart({{ $jam }}),
                                    'slot-nonaktif':!terisi.includes({{ $jam }}) && !dalamBlok({{ $jam }}) && !bisaJadiStart({{ $jam }})
                                }"
                                class="slot-btn w-100 border-0">
                            {{ sprintf('%02d:00 - %02d:00', $jam, $jam + 1) }}
                        </button>
                    </div>
                @endfor
            </div>

            <div x-show="start !== null" style="display:none;" class="card bg-light p-3">
                <p class="mb-1">
                    Jam:
                    <strong x-text="String(start).padStart(2,'0') + ':00 - ' + String(start + durasi).padStart(2,'0') + ':00'"></strong>
                    ({{ $durasi }} jam)
                </p>
                <p class="text-muted small mb-3">
                    Dibayar dari kuota — Rp0. Sisa sesi setelah ini:
                    <strong>{{ $langganan->sisa_sesi - $durasi }}</strong>
                </p>

                <form method="POST" action="{{ route('customer.paket.pakai-sesi.store', $langganan) }}">
                    @csrf
                    <input type="hidden" name="lapangan_id" value="{{ $lapanganId }}">
                    <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                    <input type="hidden" name="jam_mulai" :value="start !== null ? String(start).padStart(2,'0') + ':00' : ''">
                    <input type="hidden" name="jam_selesai" :value="start !== null ? String(start + durasi).padStart(2,'0') + ':00' : ''">
                    <button type="submit" class="btn btn-navy w-100">Pakai Sesi Ini</button>
                </form>
            </div>
        </div>
    @else
        <p class="text-muted">Pilih lapangan dulu buat lihat jadwal jamnya.</p>
    @endif
</div>
@endsection