@extends('layouts.customer')

@section('content')

   <!-- \App\Models\PoinCustomer::create([
       'customer_id' => 4, // ganti sesuai ID akun customer kamu
       'jumlah_poin' => 100,
       'jenis' => 'Masuk',
       'keterangan' => 'Test manual',
       'tanggal' => now()->toDateString(),
   ]); -->
<style>
    .text-navy { color: #12244a; }
    .btn-navy {
        background-color: #12244a;
        color: white;
        border: 1px solid #12244a;
        transition: all 0.2s ease;
    }
    .btn-navy:hover {
        background-color: #0d1a35;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 6px 14px rgba(18,36,74,0.3);
    }
    .btn-navy:active { transform: translateY(0); }

    .booking-card {
        border-radius: 1.25rem;
        transition: box-shadow 0.3s ease;
    }
    .booking-card:hover {
        box-shadow: 0 .75rem 1.5rem rgba(0,0,0,.1)!important;
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
    .slot-kosong {
        background: white;
        border: 1px solid #dee2e6;
        color: #212529;
    }
    .slot-kosong:hover {
        border-color: #ef7d2d;
        background-color: #fff6f0;
        transform: translateY(-3px);
        box-shadow: 0 4px 10px rgba(239,125,45,0.15);
    }
    .slot-kosong:active {
        transform: translateY(-1px) scale(0.97);
    }
    .slot-dipilih {
        background-color: #12244a;
        border: 1px solid #12244a;
        color: white;
        transform: translateY(-2px) scale(1.03);
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
    .slot-lewat {
        background-color: #f1f3f5;
        border: 1px solid #dee2e6;
        color: #adb5bd;
        cursor: not-allowed;
        opacity: 0.7;
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
    .summary-box, .info-banner {
        animation: slideFadeIn 0.3s ease;
    }

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
    .ringkasan-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
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

        <a href="{{ route('customer.beranda') }}" class="text-muted small mb-3 d-inline-block text-decoration-none">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>

        <div class="card booking-card border-0 shadow-sm p-4">

            <!-- Header Lapangan -->
            <h4 class="fw-bold text-navy mb-1">{{ $lapangan->nama_lapang }}</h4>
            <p class="text-muted mb-4">
                {{ $lapangan->kategoriOlahraga->nama_kategori ?? 'Umum' }}
                &middot; Rp{{ number_format($lapangan->tarif_per_jam, 0, ',', '.') }}/jam
            </p>

            <!-- Pilih Tanggal -->
            <p class="fw-semibold text-navy mb-2">Pilih Tanggal</p>
            <div class="d-flex align-items-center gap-2 mb-4">
                @php
                    $tanggalCarbon = \Carbon\Carbon::parse($tanggal);
                    $tanggalPrev = $tanggalCarbon->copy()->subDay()->toDateString();
                    $tanggalNext = $tanggalCarbon->copy()->addDay()->toDateString();
                    $bisaMundur = $tanggalCarbon->copy()->subDay()->gte(now()->startOfDay());
                @endphp

                <a href="{{ $bisaMundur ? route('customer.booking.create', ['lapangan' => $lapangan->id, 'tanggal' => $tanggalPrev]) : '#' }}"
                   class="date-nav-btn d-flex align-items-center justify-content-center py-2 {{ !$bisaMundur ? 'disabled text-muted' : '' }}">
                    <i class="bi bi-chevron-left"></i>
                </a>

                <div class="date-display flex-grow-1 text-center">
                    <i class="bi bi-calendar3 me-1"></i>
                    {{ $tanggalCarbon->translatedFormat('l, d F Y') }}
                </div>

                <a href="{{ route('customer.booking.create', ['lapangan' => $lapangan->id, 'tanggal' => $tanggalNext]) }}"
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

                // Blokir slot yang sudah lewat jika booking di hari ini.
                // Jam X dianggap "lewat" jika jam sekarang sudah >= X+1:00
                // (misal jam 09.10 → jam 09:00-10:00 sudah tidak bisa dipilih)
                $slotLewat = [];
                if (\Carbon\Carbon::parse($tanggal)->isToday()) {
                    $jamSekarang = (int) now()->format('G'); // 0-23, tanpa leading zero
                    $menitSekarang = (int) now()->format('i');
                    // Jam X tidak bisa dipilih jika now >= X:00
                    // Artinya slot 07 tidak bisa jika jam >= 07:00
                    for ($j = 7; $j <= 22; $j++) {
                        if ($jamSekarang > $j || ($jamSekarang === $j && $menitSekarang >= 0)) {
                            $slotLewat[] = $j;
                        }
                    }
                }
            @endphp

            <div x-data="{
                    selected: [],
                    terisi: {{ json_encode($slotTerisi) }},
                    lewat: {{ json_encode($slotLewat) }},
                    toggle(jam) {
                        if (this.terisi.includes(jam) || this.lewat.includes(jam)) return;
                        if (this.selected.includes(jam)) {
                            this.selected = this.selected.filter(j => j !== jam);
                            return;
                        }
                        if (this.selected.length === 0 || jam === Math.min(...this.selected) - 1 || jam === Math.max(...this.selected) + 1) {
                            this.selected.push(jam);
                        } else {
                            this.selected = [jam];
                        }
                    }
                 }">

                <!-- Pilih Jam + Legend -->
                <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                    <p class="fw-semibold text-navy mb-0">Pilih Jam</p>
                    <div class="small text-muted d-flex align-items-center gap-3">
                        <span><span class="legend-dot" style="background:white; border:1px solid #dee2e6;"></span>Kosong</span>
                        <span><span class="legend-dot" style="background:#12244a;"></span>Dipilih</span>
                        <span><span class="legend-dot" style="background:#fdecea; border:1px solid #f5c6cb;"></span>Terisi</span>
                        <span><span class="legend-dot" style="background:#f1f3f5; border:1px solid #dee2e6;"></span>Lewat</span>
                    </div>
                </div>

                <div class="row g-2 mb-3">
                    @for ($jam = 7; $jam <= 22; $jam++)
                        <div class="col-6 col-sm-3">
                            <button type="button"
                                    @click="toggle({{ $jam }})"
                                    :disabled="terisi.includes({{ $jam }}) || lewat.includes({{ $jam }})"
                                    :class="{
                                        'slot-terisi':  terisi.includes({{ $jam }}),
                                        'slot-lewat':   !terisi.includes({{ $jam }}) && lewat.includes({{ $jam }}),
                                        'slot-dipilih': selected.includes({{ $jam }}),
                                        'slot-kosong':  !terisi.includes({{ $jam }}) && !lewat.includes({{ $jam }}) && !selected.includes({{ $jam }})
                                    }"
                                    class="slot-btn w-100 border-0 d-flex flex-column align-items-center justify-content-center">
                                <template x-if="lewat.includes({{ $jam }}) && !terisi.includes({{ $jam }})">
                                    <span style="font-size:0.8rem;">
                                        <i class="bi bi-clock-history me-1"></i>{{ sprintf('%02d:00-%02d:00', $jam, $jam+1) }}
                                    </span>
                                </template>
                                <template x-if="!lewat.includes({{ $jam }}) && !selected.includes({{ $jam }}) && !terisi.includes({{ $jam }})">
                                    <span><i class="bi bi-clock me-1"></i>{{ sprintf('%02d:00 - %02d:00', $jam, $jam + 1) }}</span>
                                </template>
                                <template x-if="selected.includes({{ $jam }})">
                                    <span><i class="bi bi-check-lg"></i> {{ sprintf('%02d:00 - %02d:00', $jam, $jam + 1) }}</span>
                                </template>
                                <template x-if="terisi.includes({{ $jam }})">
                                    <span style="font-size:0.8rem;"><i class="bi bi-lock me-1"></i>{{ sprintf('%02d:00-%02d:00', $jam, $jam+1) }}</span>
                                </template>
                                <small x-show="selected.includes({{ $jam }})" x-cloak class="fw-normal" style="font-size: 0.7rem;">Dipilih</small>
                                <small x-show="lewat.includes({{ $jam }}) && !terisi.includes({{ $jam }})" x-cloak class="fw-normal" style="font-size:0.7rem;">Lewat</small>
                            </button>
                        </div>
                    @endfor
                </div>

                <!-- Banner info jam terpilih -->
                <div class="info-banner mb-3" x-show="selected.length > 0" style="display: none;" x-transition>
                    <span x-text="selected.length"></span> jam dipilih:
                    <span x-text="selected.length ? String(Math.min(...selected)).padStart(2,'0') + ':00 - ' + String(Math.max(...selected)+1).padStart(2,'0') + ':00' : ''"></span>
                </div>

                <!-- Ringkasan & Submit -->
                <div class="ringkasan-box summary-box" x-show="selected.length > 0" style="display: none;" x-transition>
                    <h6 class="fw-bold text-navy mb-3">Ringkasan Booking</h6>

                    <div class="ringkasan-row text-muted">
                        <span>Durasi</span>
                        <span class="text-dark" x-text="selected.length + ' jam'"></span>
                    </div>
                    <div class="ringkasan-row text-muted">
                        <span>Harga (Rp{{ number_format($lapangan->tarif_per_jam, 0, ',', '.') }} &times; <span x-text="selected.length"></span>)</span>
                        <span class="text-dark" x-text="'Rp ' + (selected.length * {{ $lapangan->tarif_per_jam }}).toLocaleString('id-ID')"></span>
                    </div>

                    <hr>

                    <div class="ringkasan-row">
                        <span class="fw-bold text-navy">Total</span>
                        <span class="fw-bold text-navy" x-text="'Rp ' + (selected.length * {{ $lapangan->tarif_per_jam }}).toLocaleString('id-ID')"></span>
                    </div>

                    <div class="mt-2 mb-3 p-3 rounded-3" style="background:#fff8f0;border:1px solid #fde8d0;">
                        <div class="d-flex align-items-start gap-2">
                            <i class="bi bi-info-circle text-orange mt-1" style="font-size:0.9rem;"></i>
                            <p class="small mb-0" style="color:#7c4a0a;">
                                Pembayaran dilakukan setelah kasir menyetujui booking kamu.
                            </p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('customer.booking.store', $lapangan) }}" class="mt-3">
                        @csrf
                        <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                        <input type="hidden" name="jam_mulai" :value="selected.length ? String(Math.min(...selected)).padStart(2,'0') + ':00' : ''">
                        <input type="hidden" name="jam_selesai" :value="selected.length ? String(Math.max(...selected)+1).padStart(2,'0') + ':00' : ''">
                        <button type="submit" class="btn btn-orange-submit w-100 py-2" style="border-radius: 0.75rem;">
                            <i class="bi bi-send me-2"></i>Kirim Permintaan Booking
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection