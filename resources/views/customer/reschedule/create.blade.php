@extends('layouts.customer')

@section('content')
<style>
    .text-navy { color: #12244a; }

    .booking-card { border-radius: 1.25rem; transition: box-shadow 0.3s ease; }
    .booking-card:hover { box-shadow: 0 .75rem 1.5rem rgba(0,0,0,.1)!important; }

    .jadwal-lama-box {
        background-color: #fdecea;
        border: 1px solid #f5c6cb;
        border-radius: 0.85rem;
        padding: 1rem 1.25rem;
    }

    .date-nav-btn {
        width: 44px;
        border: 1px solid #dee2e6;
        border-radius: 0.75rem;
        background: white;
        transition: all 0.2s ease;
    }
    .date-nav-btn:hover:not(.disabled) {
        background-color: #12244a;
        border-color: #12244a;
        color: white;
        transform: scale(1.05);
    }
    .date-nav-btn:active:not(.disabled) { transform: scale(0.95); }

    .date-display {
        background-color: #f1f3f5;
        border-radius: 0.75rem;
        padding: 0.75rem 1rem;
        font-weight: 600;
        color: #12244a;
    }

    .legend-dot {
        display: inline-block;
        width: 12px;
        height: 12px;
        border-radius: 3px;
        margin-right: 6px;
        vertical-align: middle;
    }

    .slot-btn {
        border-radius: 0.75rem;
        padding: 0.65rem 0.5rem;
        font-weight: 500;
        font-size: 0.9rem;
        min-height: 58px;
        transition: transform 0.15s ease, box-shadow 0.2s ease, background-color 0.2s ease, border-color 0.2s ease, color 0.2s ease, opacity 0.2s ease;
    }
    .slot-kosong { background: white; border: 1px solid #dee2e6; color: #212529; }
    .slot-kosong:hover {
        border-color: #ef7d2d;
        background-color: #fff6f0;
        transform: translateY(-3px);
        box-shadow: 0 4px 10px rgba(239,125,45,0.15);
    }
    .slot-kosong:active { transform: translateY(-1px) scale(0.97); }
    .slot-dipilih {
        background-color: #12244a;
        border: 1px solid #12244a;
        color: white;
        box-shadow: 0 6px 14px rgba(18,36,74,0.35);
        animation: pop 0.25s ease;
    }
    .slot-terisi {
        background-color: #fdecea;
        border: 1px solid #f5c6cb;
        color: #c94b4b;
        cursor: not-allowed;
        opacity: 0.85;
    }
    .slot-nonaktif {
        background-color: #f8f9fa;
        border: 1px solid #eef0f2;
        color: #ced4da;
        cursor: not-allowed;
        opacity: 0.6;
    }

    @keyframes pop {
        0% { transform: scale(0.9); }
        60% { transform: scale(1.08); }
        100% { transform: scale(1.03) translateY(-2px); }
    }

    @keyframes slideFadeIn {
        from { opacity: 0; transform: translateY(8px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .summary-box, .info-banner { animation: slideFadeIn 0.3s ease; }

    .info-banner {
        background-color: #eaf1fb;
        border: 1px solid #cfe0f7;
        color: #12244a;
        border-radius: 0.75rem;
        padding: 0.85rem 1rem;
        font-weight: 500;
    }

    .ringkasan-box {
        background-color: #eaf1fb;
        border: 1px solid #cfe0f7;
        border-radius: 1rem;
        padding: 1.25rem;
    }

    .form-control-alasan {
        border-radius: 0.65rem;
        border: 1px solid #dee2e6;
        padding: 0.65rem 0.9rem;
        transition: all 0.2s ease;
    }
    .form-control-alasan:focus {
        border-color: #ef7d2d;
        box-shadow: 0 0 0 3px rgba(239,125,45,0.15);
        outline: none;
    }

    .btn-orange-submit {
        background-color: #ef7d2d;
        border: none;
        color: white;
        font-weight: 600;
        transition: all 0.2s ease;
    }
    .btn-orange-submit:hover {
        background-color: #d96b22;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 6px 14px rgba(239,125,45,0.35);
    }
    .btn-orange-submit:active { transform: translateY(0); }
    [x-cloak] { display: none !important; }
</style>

<div class="d-flex justify-content-center">
    <div style="max-width: 800px; width: 100%;">

        <a href="{{ route('customer.riwayat') }}" class="text-muted small mb-3 d-inline-block text-decoration-none">
            <i class="bi bi-arrow-left"></i> Kembali ke Riwayat
        </a>

        <div class="card booking-card border-0 shadow-sm p-4">

            <h4 class="fw-bold text-navy mb-1">Ajukan Reschedule</h4>
            <p class="text-muted mb-3">
                {{ $lapangan->nama_lapang }} &middot; {{ $lapangan->kategoriOlahraga->nama_kategori ?? 'Umum' }}
                &middot; Rp{{ number_format($lapangan->tarif_per_jam, 0, ',', '.') }}/jam
            </p>

            <!-- Jadwal lama -->
            <div class="jadwal-lama-box mb-4">
                <p class="text-muted small mb-1">Jadwal saat ini</p>
                <p class="fw-semibold mb-0" style="color:#c94b4b;">
                    <i class="bi bi-calendar3 me-1"></i>{{ \Carbon\Carbon::parse($booking->tanggal)->translatedFormat('d M Y') }}
                    &nbsp;
                    <i class="bi bi-clock me-1"></i>{{ \Carbon\Carbon::parse($booking->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->jam_selesai)->format('H:i') }}
                    &nbsp;
                    <span class="text-muted small fw-normal">({{ $durasiAsli }} jam)</span>
                </p>
            </div>

            <!-- Pilih Tanggal Baru -->
            <p class="fw-semibold text-navy mb-2">Pilih Tanggal Baru</p>
            <div class="d-flex align-items-center gap-2 mb-4">
                @php
                    $tanggalCarbon = \Carbon\Carbon::parse($tanggal);
                    $tanggalPrev = $tanggalCarbon->copy()->subDay()->toDateString();
                    $tanggalNext = $tanggalCarbon->copy()->addDay()->toDateString();
                    $bisaMundur = $tanggalCarbon->copy()->subDay()->gte(now()->startOfDay());
                @endphp

                <a href="{{ $bisaMundur ? route('customer.reschedule.create', ['booking' => $booking->id, 'tanggal' => $tanggalPrev]) : '#' }}"
                   class="date-nav-btn d-flex align-items-center justify-content-center py-2 {{ !$bisaMundur ? 'disabled text-muted' : '' }}">
                    <i class="bi bi-chevron-left"></i>
                </a>

                <div class="date-display flex-grow-1 text-center">
                    <i class="bi bi-calendar3 me-1"></i>
                    {{ $tanggalCarbon->translatedFormat('l, d F Y') }}
                </div>

                <a href="{{ route('customer.reschedule.create', ['booking' => $booking->id, 'tanggal' => $tanggalNext]) }}"
                   class="date-nav-btn d-flex align-items-center justify-content-center py-2">
                    <i class="bi bi-chevron-right"></i>
                </a>
            </div>

            @php
                $slotTerisi = [];
                foreach ($bookingHariItu as $b) {
                    $mulai = \Carbon\Carbon::parse($b->jam_mulai)->hour;
                    $selesai = \Carbon\Carbon::parse($b->jam_selesai)->hour;
                    for ($j = $mulai; $j < $selesai; $j++) {
                        $slotTerisi[] = $j;
                    }
                }
            @endphp

            <div x-data="{
                    durasi: {{ $durasiAsli }},
                    jamTutup: 22,
                    start: null,
                    terisi: {{ json_encode($slotTerisi) }},

                    // cek apakah blok [jam .. jam+durasi-1] semuanya kosong & masih dalam jam operasional
                    bisaJadiStart(jam) {
                        if (jam + this.durasi > this.jamTutup) return false;
                        for (let i = 0; i < this.durasi; i++) {
                            if (this.terisi.includes(jam + i)) return false;
                        }
                        return true;
                    },
                    pilihMulai(jam) {
                        if (!this.bisaJadiStart(jam)) return;
                        this.start = jam;
                    },
                    dalamRangeTerpilih(jam) {
                        if (this.start === null) return false;
                        return jam >= this.start && jam < this.start + this.durasi;
                    }
                 }">

                <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                    <p class="fw-semibold text-navy mb-0">Pilih Jam Mulai Baru <span class="text-muted fw-normal">(durasi tetap {{ $durasiAsli }} jam)</span></p>
                    <div class="small text-muted d-flex align-items-center gap-3">
                        <span><span class="legend-dot" style="background:white; border:1px solid #dee2e6;"></span>Kosong</span>
                        <span><span class="legend-dot" style="background:#12244a;"></span>Dipilih</span>
                        <span><span class="legend-dot" style="background:#fdecea; border:1px solid #f5c6cb;"></span>Terisi</span>
                    </div>
                </div>

                <div class="row g-2 mb-3">
                    @for ($jam = 7; $jam <= 22; $jam++)
                        <div class="col-6 col-sm-3">
                            <button type="button"
                                    @click="pilihMulai({{ $jam }})"
                                    :disabled="terisi.includes({{ $jam }}) || !bisaJadiStart({{ $jam }})"
                                    :class="{
                                        'slot-terisi': terisi.includes({{ $jam }}),
                                        'slot-dipilih': dalamRangeTerpilih({{ $jam }}),
                                        'slot-nonaktif': !terisi.includes({{ $jam }}) && !dalamRangeTerpilih({{ $jam }}) && !bisaJadiStart({{ $jam }}),
                                        'slot-kosong': !terisi.includes({{ $jam }}) && !dalamRangeTerpilih({{ $jam }}) && bisaJadiStart({{ $jam }})
                                    }"
                                    class="slot-btn w-100 border-0 d-flex flex-column align-items-center justify-content-center">
                                <span x-show="!dalamRangeTerpilih({{ $jam }})" x-cloak>
                                    <i class="bi bi-clock me-1"></i>{{ sprintf('%02d:00', $jam) }}
                                </span>
                                <span x-show="dalamRangeTerpilih({{ $jam }})" x-cloak>
                                    <i class="bi bi-check-lg"></i> {{ sprintf('%02d:00', $jam) }}
                                </span>
                            </button>
                        </div>
                    @endfor
                </div>

                <!-- Info jam terpilih -->
                <div class="info-banner mb-3" x-show="start !== null" x-cloak x-transition>
                    Jadwal baru: <span x-text="String(start).padStart(2,'0') + ':00 - ' + String(start + durasi).padStart(2,'0') + ':00'"></span>
                    <span class="text-muted">({{ $durasiAsli }} jam, sama seperti booking awal)</span>
                </div>

                <!-- Ringkasan & Submit -->
                <div class="ringkasan-box summary-box" x-show="start !== null" x-cloak x-transition>
                    <h6 class="fw-bold text-navy mb-3">Konfirmasi Jadwal Baru</h6>

                    <form method="POST" action="{{ route('customer.reschedule.store', $booking) }}">
                        @csrf
                        <input type="hidden" name="tanggal_baru" value="{{ $tanggal }}">
                        <input type="hidden" name="jam_mulai_baru" :value="start !== null ? String(start).padStart(2,'0') + ':00' : ''">
                        <input type="hidden" name="jam_selesai_baru" :value="start !== null ? String(start + durasi).padStart(2,'0') + ':00' : ''">

                        <div class="mb-3">
                            <label class="form-label text-navy fw-semibold small">Alasan (opsional)</label>
                            <textarea name="alasan" rows="2" class="form-control form-control-alasan @error('alasan') is-invalid @enderror"
                                      placeholder="Misal: ada acara mendadak, hujan deras, dll.">{{ old('alasan') }}</textarea>
                            @error('alasan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <button type="submit" class="btn btn-orange-submit w-100 py-2" style="border-radius: 0.75rem;">
                            Ajukan Reschedule
                        </button>
                        <p class="text-muted small text-center mt-2 mb-0">
                            <i class="bi bi-info-circle me-1"></i>Pengajuan akan diproses oleh Admin sebelum jadwal berubah.
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection