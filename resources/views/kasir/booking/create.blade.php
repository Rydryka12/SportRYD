@extends('layouts.kasir')

@section('content')
<style>
    .text-navy { color: #12244a; }
    .text-orange { color: #ef7d2d; }

    .form-card { border-radius: 1.25rem; border: none; transition: box-shadow 0.3s ease; }
    .form-card:hover { box-shadow: 0 .75rem 1.5rem rgba(0,0,0,.08) !important; }

    .section-label {
        font-weight: 700; color: #12244a; font-size: 0.95rem;
        display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;
    }
    .section-label i { color: #ef7d2d; }

    .form-control, .form-select {
        border-radius: 0.65rem; border: 1px solid #dee2e6; padding: 0.6rem 0.9rem; transition: all 0.2s ease;
    }
    .form-control:focus, .form-select:focus {
        border-color: #ef7d2d; box-shadow: 0 0 0 3px rgba(239,125,45,0.15);
    }
    .form-label { font-weight: 600; color: #495057; font-size: 0.88rem; }

    .info-hp {
        background-color: #f8f9fa; border-radius: 0.6rem; padding: 0.5rem 0.75rem;
        font-size: 0.8rem; color: #6c757d; margin-top: 0.4rem;
    }

    .date-nav-btn {
        width: 44px; border: 1px solid #dee2e6; border-radius: 0.75rem;
        background: white; transition: all 0.2s ease;
    }
    .date-nav-btn:hover:not(.disabled) {
        background-color: #12244a; border-color: #12244a; color: white; transform: scale(1.05);
    }
    .date-display {
        background-color: #f1f3f5; border-radius: 0.75rem; padding: 0.75rem 1rem;
        font-weight: 600; color: #12244a;
    }

    .legend-dot {
        display: inline-block; width: 12px; height: 12px; border-radius: 3px;
        margin-right: 6px; vertical-align: middle;
    }

    .slot-btn {
        border-radius: 0.75rem; padding: 0.6rem 0.5rem; font-weight: 500; font-size: 0.88rem;
        min-height: 54px; transition: all 0.15s ease;
    }
    .slot-kosong { background: white; border: 1px solid #dee2e6; color: #212529; }
    .slot-kosong:hover { border-color: #ef7d2d; background-color: #fff6f0; transform: translateY(-2px); }
    .slot-dipilih {
        background-color: #12244a; border: 1px solid #12244a; color: white;
        transform: translateY(-2px) scale(1.02); box-shadow: 0 6px 14px rgba(18,36,74,0.3);
    }
    .slot-terisi {
        background-color: #fdecea; border: 1px solid #f5c6cb; color: #c94b4b;
        cursor: not-allowed; opacity: 0.85;
    }

    .ringkasan-box {
        background-color: #eaf1fb; border: 1px solid #cfe0f7; border-radius: 1rem; padding: 1.25rem;
    }
    .ringkasan-row { display: flex; justify-content: space-between; padding: 0.4rem 0; font-size: 0.92rem; }

    @keyframes slideFadeIn {
        from { opacity: 0; transform: translateY(8px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .ringkasan-box, .info-banner { animation: slideFadeIn 0.3s ease; }

    .info-banner {
        background-color: #eaf1fb; border: 1px solid #cfe0f7; color: #12244a;
        border-radius: 0.75rem; padding: 0.8rem 1rem; font-size: 0.9rem;
    }

    .btn-navy-submit {
        background-color: #12244a; color: white; border: none; font-weight: 600;
        border-radius: 0.75rem; padding: 0.65rem 1.5rem; transition: all 0.2s ease;
    }
    .btn-navy-submit:hover:not(:disabled) {
        background-color: #0d1a35; transform: translateY(-2px); box-shadow: 0 6px 14px rgba(18,36,74,0.3);
    }
    .btn-navy-submit:disabled { opacity: 0.5; cursor: not-allowed; }

    .btn-outline-batal { border-radius: 0.75rem; padding: 0.65rem 1.5rem; font-weight: 600; transition: all 0.2s ease; }
    .btn-outline-batal:hover { transform: translateY(-2px); }

    [x-cloak] { display: none !important; }
</style>

<div class="page-heading">
    <h3 class="mb-4 fw-bold text-navy">Booking Manual</h3>

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <section class="section">
        <div class="card form-card shadow-sm">
            <div class="card-body p-4">

                <!-- STEP 1: Pilih Lapangan & Tanggal (reload utk ambil data bentrok akurat) -->
                <form method="GET" action="{{ route('kasir.booking.create') }}">
                    <p class="section-label"><i class="bi bi-geo-alt-fill"></i>Pilih Lapangan &amp; Tanggal</p>
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Lapangan</label>
                            <select name="lapangan_id" onchange="this.form.submit()" class="form-select">
                                <option value="">-- Pilih Lapangan --</option>
                                @foreach ($lapanganList as $lap)
                                    <option value="{{ $lap->id }}" @selected($lapanganId == $lap->id)>
                                        {{ $lap->nama_lapang }} ({{ $lap->kategoriOlahraga->nama_kategori }}) — Rp{{ number_format($lap->tarif_per_jam, 0, ',', '.') }}/jam
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal</label>
                            <div class="d-flex align-items-center gap-2">
                                @php
                                    $tanggalCarbon = \Carbon\Carbon::parse($tanggal);
                                    $tanggalPrev = $tanggalCarbon->copy()->subDay()->toDateString();
                                    $tanggalNext = $tanggalCarbon->copy()->addDay()->toDateString();
                                    $bisaMundur = $tanggalCarbon->copy()->subDay()->gte(now()->startOfDay());
                                @endphp

                                <a href="{{ $bisaMundur ? route('kasir.booking.create', ['lapangan_id' => $lapanganId, 'tanggal' => $tanggalPrev]) : '#' }}"
                                   class="date-nav-btn d-flex align-items-center justify-content-center py-2 {{ !$bisaMundur ? 'disabled text-muted' : '' }}">
                                    <i class="bi bi-chevron-left"></i>
                                </a>

                                <div class="date-display flex-grow-1 text-center">
                                    <i class="bi bi-calendar3 me-1"></i>
                                    {{ $tanggalCarbon->translatedFormat('l, d F Y') }}
                                </div>

                                <a href="{{ route('kasir.booking.create', ['lapangan_id' => $lapanganId, 'tanggal' => $tanggalNext]) }}"
                                   class="date-nav-btn d-flex align-items-center justify-content-center py-2">
                                    <i class="bi bi-chevron-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </form>

                @if ($lapanganTerpilih)
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

                    <div x-data="bookingKasir()">
                        <form method="POST" action="{{ route('kasir.booking.store') }}">
                            @csrf
                            <input type="hidden" name="lapangan_id" value="{{ $lapanganTerpilih->id }}">
                            <input type="hidden" name="tanggal" value="{{ $tanggal }}">

                            <!-- Data Pelanggan -->
                            <p class="section-label"><i class="bi bi-person-fill"></i>Data Pelanggan</p>
                            <div class="row mb-4">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nama Pelanggan</label>
                                    <input type="text" name="nama_pelanggan" value="{{ old('nama_pelanggan') }}"
                                           class="form-control @error('nama_pelanggan') is-invalid @enderror">
                                    @error('nama_pelanggan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">No. HP</label>
                                    <input type="text" name="no_hp" value="{{ old('no_hp') }}"
                                           class="form-control @error('no_hp') is-invalid @enderror" placeholder="08xxxxxxxxxx">
                                    @error('no_hp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    <div class="info-hp">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Kalau no HP sudah pernah dipakai, booking otomatis nempel ke akun yang sama.
                                    </div>
                                </div>
                            </div>

                            <!-- Pilih Jam -->
                            <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                                <p class="section-label mb-0"><i class="bi bi-clock-fill"></i>Pilih Jam</p>
                                <div class="small text-muted d-flex align-items-center gap-3">
                                    <span><span class="legend-dot" style="background:white; border:1px solid #dee2e6;"></span>Kosong</span>
                                    <span><span class="legend-dot" style="background:#12244a;"></span>Dipilih</span>
                                    <span><span class="legend-dot" style="background:#fdecea; border:1px solid #f5c6cb;"></span>Terisi</span>
                                </div>
                            </div>

                            <div class="row g-2 mb-3">
                                @for ($jam = 7; $jam <= 22; $jam++)
                                    <div class="col-6 col-sm-3 col-md-2">
                                        <button type="button"
                                                @click="toggle({{ $jam }})"
                                                :disabled="terisi.includes({{ $jam }})"
                                                :class="{
                                                    'slot-terisi': terisi.includes({{ $jam }}),
                                                    'slot-dipilih': selected.includes({{ $jam }}),
                                                    'slot-kosong': !terisi.includes({{ $jam }}) && !selected.includes({{ $jam }})
                                                }"
                                                class="slot-btn w-100 border-0 d-flex flex-column align-items-center justify-content-center">
                                            <span x-show="!selected.includes({{ $jam }})" x-cloak>
                                                <i class="bi bi-clock me-1"></i>{{ sprintf('%02d:00', $jam) }}
                                            </span>
                                            <span x-show="selected.includes({{ $jam }})" x-cloak>
                                                <i class="bi bi-check-lg"></i> {{ sprintf('%02d:00', $jam) }}
                                            </span>
                                        </button>
                                    </div>
                                @endfor
                            </div>

                            <div class="info-banner mb-3" x-show="selected.length > 0" x-cloak x-transition>
                                <span x-text="selected.length"></span> jam dipilih:
                                <span x-text="selected.length ? jamFormat(Math.min(...selected)) + ' - ' + jamFormat(Math.max(...selected)+1) : ''"></span>
                            </div>

                            <!-- Metode Bayar -->
                            <div class="row mb-4">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Metode Bayar</label>
                                    <select name="metode_bayar" class="form-select @error('metode_bayar') is-invalid @enderror">
                                        <option value="Tunai" @selected(old('metode_bayar') === 'Tunai')>Tunai</option>
                                        <option value="QRIS" @selected(old('metode_bayar') === 'QRIS')>QRIS</option>
                                    </select>
                                    @error('metode_bayar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <!-- Ringkasan -->
                            <div class="ringkasan-box mb-4" x-show="selected.length > 0" x-cloak x-transition>
                                <h6 class="fw-bold text-navy mb-3"><i class="bi bi-receipt me-1"></i>Ringkasan Biaya</h6>
                                <div class="ringkasan-row text-muted">
                                    <span>Lapangan</span>
                                    <span class="fw-semibold text-navy">{{ $lapanganTerpilih->nama_lapang }}</span>
                                </div>
                                <div class="ringkasan-row text-muted">
                                    <span>Durasi</span>
                                    <span class="fw-semibold text-navy" x-text="selected.length + ' jam'"></span>
                                </div>
                                <div class="ringkasan-row text-muted">
                                    <span>Tarif</span>
                                    <span class="fw-semibold text-navy">Rp {{ number_format($lapanganTerpilih->tarif_per_jam, 0, ',', '.') }}/jam</span>
                                </div>
                                <hr>
                                <div class="ringkasan-row">
                                    <span class="fw-bold text-navy fs-6">Total Bayar</span>
                                    <span class="fw-bold text-orange fs-5" x-text="'Rp ' + (selected.length * {{ $lapanganTerpilih->tarif_per_jam }}).toLocaleString('id-ID')"></span>
                                </div>
                            </div>

                            <input type="hidden" name="jam_mulai" :value="selected.length ? jamFormat(Math.min(...selected)) : ''">
                            <input type="hidden" name="jam_selesai" :value="selected.length ? jamFormat(Math.max(...selected)+1) : ''">

                            <div class="d-flex gap-2">
                                <a href="{{ route('kasir.booking.index') }}" class="btn btn-outline-secondary btn-outline-batal">Batal</a>
                                <button type="submit" class="btn btn-navy-submit" :disabled="selected.length === 0">
                                    <i class="bi bi-check-circle me-1"></i>Simpan Booking
                                </button>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-arrow-up-circle fs-1 d-block mb-2"></i>
                        Pilih lapangan dan tanggal dulu untuk melihat jam yang tersedia.
                    </div>
                @endif

            </div>
        </div>
    </section>
</div>

<script>
    function bookingKasir() {
        return {
            selected: [],
            terisi: {{ json_encode($slotTerisi ?? []) }},
            toggle(jam) {
                if (this.terisi.includes(jam)) return;
                if (this.selected.includes(jam)) {
                    this.selected = this.selected.filter(j => j !== jam);
                    return;
                }
                if (this.selected.length === 0 || jam === Math.min(...this.selected) - 1 || jam === Math.max(...this.selected) + 1) {
                    this.selected.push(jam);
                } else {
                    this.selected = [jam];
                }
            },
            jamFormat(jam) {
                return String(jam).padStart(2, '0') + ':00';
            }
        }
    }
</script>
@endsection