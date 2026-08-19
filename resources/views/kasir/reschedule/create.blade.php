@extends('layouts.kasir')

@section('content')
<style>
    /* ── Base ── */
    .text-navy   { color: #12244a; }
    .text-orange { color: #ef7d2d; }

    .form-card { border-radius: 1.25rem; border: none; transition: box-shadow 0.3s ease; }
    .form-card:hover { box-shadow: 0 .75rem 1.5rem rgba(0,0,0,.08) !important; }

    .section-label {
        font-weight: 700; font-size: 0.95rem;
        display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;
        color: #12244a;
    }
    .section-label i { color: #ef7d2d; }

    .form-label { font-weight: 600; font-size: 0.88rem; }

    /* ── Info jadwal saat ini ── */
    .info-jadwal {
        background-color: #f8f9fa; border: 1px solid #e9ecef; border-radius: 1rem;
        padding: 1rem 1.25rem; margin-bottom: 1.5rem;
    }
    .info-jadwal .label { font-weight: 700; font-size: 0.88rem; color: #6b7a99; margin-bottom: 0.3rem; }
    .info-jadwal .value { font-weight: 600; color: #12244a; }

    /* ── Date nav ── */
    .date-nav-btn {
        width: 44px; border: 1px solid #dee2e6; border-radius: 0.75rem;
        background: white; transition: all 0.2s ease; color: inherit;
    }
    .date-nav-btn:hover:not([disabled]) {
        background-color: #12244a; border-color: #12244a; color: white; transform: scale(1.05);
    }
    .date-nav-btn[disabled] { opacity: 0.4; cursor: not-allowed; }
    .date-display {
        background-color: #f1f3f5; border-radius: 0.75rem; padding: 0.75rem 1rem;
        font-weight: 600; color: #12244a; cursor: pointer;
    }

    /* ── Legend ── */
    .legend-dot {
        display: inline-block; width: 12px; height: 12px; border-radius: 3px;
        margin-right: 4px; vertical-align: middle;
    }

    /* ── Slot buttons ── */
    .slot-btn {
        border-radius: 0.75rem !important; padding: 0.6rem 0.5rem; font-weight: 500; font-size: 0.82rem;
        min-height: 54px; transition: all 0.15s ease; line-height: 1.4;
        border: 1px solid #dee2e6 !important;
        display: flex; align-items: center; justify-content: center; gap: 4px;
    }
    .slot-kosong { background: #ffffff; color: #212529; }
    .slot-kosong:hover {
        border-color: #ef7d2d !important; background-color: #fff6f0 !important;
        color: #ef7d2d !important; transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(239,125,45,0.2) !important;
    }
    .slot-dipilih {
        background-color: #12244a !important; border-color: #12244a !important;
        color: #ffffff !important; transform: translateY(-2px) scale(1.02);
        box-shadow: 0 6px 14px rgba(18,36,74,0.3) !important;
    }
    .slot-terisi {
        background-color: #fdecea !important; border-color: #f5c6cb !important;
        color: #c94b4b !important; cursor: not-allowed !important; opacity: 0.85;
    }
    .slot-nonaktif {
        background-color: #f8f9fa !important; border-color: #eef0f2 !important;
        color: #ced4da !important; cursor: not-allowed !important; opacity: 0.6;
    }

    /* ── Info banner ── */
    .info-banner {
        background-color: #eaf1fb; border: 1px solid #cfe0f7; color: #12244a;
        border-radius: 0.75rem; padding: 0.8rem 1rem; font-size: 0.9rem; font-weight: 500;
    }

    /* ── Submit area ── */
    .btn-navy-submit {
        background-color: #12244a; color: white; border: none; font-weight: 600;
        border-radius: 0.75rem; padding: 0.65rem 1.5rem; transition: all 0.2s ease;
    }
    .btn-navy-submit:hover:not(:disabled) {
        background-color: #0d1a35; transform: translateY(-2px);
        box-shadow: 0 6px 14px rgba(18,36,74,0.3); color: white;
    }
    .btn-navy-submit:disabled { opacity: 0.5; cursor: not-allowed; }
    .btn-outline-batal {
        border-radius: 0.75rem; padding: 0.65rem 1.5rem; font-weight: 600; transition: all 0.2s ease;
    }
    .btn-outline-batal:hover { transform: translateY(-2px); }

    /* ════════════════════════════
       DARK MODE
    ════════════════════════════ */
    html[data-bs-theme="dark"] .section-label { color: #d9deea; }
    html[data-bs-theme="dark"] .form-label    { color: #c0c8db; }
    html[data-bs-theme="dark"] .text-navy     { color: #d9deea !important; }
    html[data-bs-theme="dark"] .text-orange   { color: #ef7d2d !important; }

    html[data-bs-theme="dark"] .info-jadwal {
        background-color: rgba(255,255,255,0.06); border-color: rgba(255,255,255,0.12);
    }
    html[data-bs-theme="dark"] .info-jadwal .label { color: #9aa3b5; }
    html[data-bs-theme="dark"] .info-jadwal .value { color: #d9deea; }

    html[data-bs-theme="dark"] .date-nav-btn {
        background: rgba(255,255,255,0.07); border-color: rgba(255,255,255,0.18) !important; color: #d9deea;
    }
    html[data-bs-theme="dark"] .date-nav-btn:hover:not([disabled]) {
        background-color: #ef7d2d; border-color: #ef7d2d !important; color: #fff;
    }
    html[data-bs-theme="dark"] .date-display {
        background-color: rgba(255,255,255,0.08); color: #d9deea;
    }

    /* Slot dark */
    html[data-bs-theme="dark"] .slot-btn {
        border-radius: 0.75rem !important;
    }
    html[data-bs-theme="dark"] .slot-kosong {
        background: rgba(255,255,255,0.06) !important;
        border-color: rgba(255,255,255,0.18) !important;
        color: #d9deea !important;
    }
    html[data-bs-theme="dark"] .slot-kosong:hover {
        border-color: #ef7d2d !important;
        background-color: rgba(239,125,45,0.15) !important;
        color: #ef7d2d !important;
        box-shadow: 0 4px 12px rgba(239,125,45,0.25) !important;
        transform: translateY(-2px);
    }
    html[data-bs-theme="dark"] .slot-dipilih {
        background-color: #ef7d2d !important;
        border-color: #ef7d2d !important;
        color: #ffffff !important;
        box-shadow: 0 6px 14px rgba(239,125,45,0.35) !important;
    }
    html[data-bs-theme="dark"] .slot-terisi {
        background-color: rgba(201,75,75,0.18) !important;
        border-color: rgba(201,75,75,0.4) !important;
        color: #f08080 !important;
    }

    html[data-bs-theme="dark"] .info-banner {
        background-color: rgba(255,255,255,0.07);
        border-color: rgba(255,255,255,0.14);
        color: #d9deea;
    }
    html[data-bs-theme="dark"] .text-muted { color: #9aa3b5 !important; }

    @keyframes slideFadeIn {
        from { opacity: 0; transform: translateY(8px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .info-banner { animation: slideFadeIn 0.3s ease; }

    [x-cloak] { display: none !important; }
</style>

<div class="page-heading" x-data="rescheduleKasir()" x-init="init()">
    <h3 class="mb-4 fw-bold text-navy">Reschedule Booking</h3>

    <section class="section">
        <div class="card form-card shadow-sm">
            <div class="card-body p-4">

                <a href="{{ route('kasir.reschedule.index') }}"
                   class="text-muted small mb-3 d-inline-block text-decoration-none">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>

                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                {{-- Info jadwal saat ini --}}
                <div class="info-jadwal">
                    <div class="label">Jadwal Saat Ini</div>
                    <div class="value">
                        {{ $booking->customer->name }}
                        &middot; {{ $booking->lapangan->nama_lapang }}
                        &middot; {{ \Carbon\Carbon::parse($booking->tanggal)->translatedFormat('d M Y') }},
                        {{ \Carbon\Carbon::parse($booking->jam_mulai)->format('H:i') }}–{{ \Carbon\Carbon::parse($booking->jam_selesai)->format('H:i') }}
                    </div>
                    @if ($booking->sesi_langganan_id)
                        <small class="text-muted">Bagian dari Paket Langganan — sesi lain tidak ikut berubah.</small>
                    @endif
                </div>

                {{-- Pilih Tanggal Baru --}}
                <p class="section-label"><i class="bi bi-calendar3"></i>Tanggal Baru</p>
                <div class="d-flex align-items-center gap-2 mb-4" style="max-width: 480px;">
                    <button type="button" class="date-nav-btn d-flex align-items-center justify-content-center py-2"
                            @click="prevDay" :disabled="!bisaMundur">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <div class="date-display flex-grow-1 text-center position-relative"
                         @click="$refs.tglPicker.showPicker ? $refs.tglPicker.showPicker() : $refs.tglPicker.click()">
                        <i class="bi bi-calendar3 me-1"></i>
                        <span x-text="formatTanggal(tanggal)"></span>
                        <input type="date" x-ref="tglPicker"
                               x-model="tanggal"
                               @change="fetchSlots()"
                               min="{{ now()->toDateString() }}"
                               style="position:absolute; opacity:0; width:1px; height:1px; top:0; left:0; pointer-events:none;">
                    </div>
                    <button type="button" class="date-nav-btn d-flex align-items-center justify-content-center py-2"
                            @click="nextDay">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>

                {{-- Pilih Jam Baru --}}
                <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                    <p class="section-label mb-0"><i class="bi bi-clock-fill"></i>Pilih Jam Baru</p>
                    <div class="small d-flex align-items-center gap-3 text-muted">
                        <span><span class="legend-dot" style="background:rgba(200,210,220,0.4); border:1px solid rgba(200,210,220,0.6);"></span>Kosong</span>
                        <span><span class="legend-dot" style="background:#ef7d2d;"></span>Dipilih</span>
                        <span><span class="legend-dot" style="background:#f08080;"></span>Terisi</span>
                    </div>
                </div>

                <div x-show="loading" class="text-center py-4 text-muted">
                    <div class="spinner-border spinner-border-sm me-2"></div>Memuat jadwal...
                </div>

                <div x-show="!loading" class="row g-2 mb-3">
                    <template x-for="jam in jamRange" :key="jam">
                        <div class="col-6 col-sm-3 col-md-2">
                            <button type="button"
                                    @click="toggle(jam)"
                                    :disabled="terisi.includes(jam)"
                                    :class="{
                                        'slot-terisi': terisi.includes(jam),
                                        'slot-dipilih': selected.includes(jam),
                                        'slot-kosong': !terisi.includes(jam) && !selected.includes(jam)
                                    }"
                                    class="slot-btn w-100">
                                <i :class="selected.includes(jam) ? 'bi bi-check-lg' : 'bi bi-clock'"></i>
                                <span x-text="pad(jam) + ':00 - ' + pad(jam+1) + ':00'"></span>
                            </button>
                        </div>
                    </template>
                </div>

                {{-- Info banner --}}
                <div class="info-banner mb-4" x-show="selected.length > 0" x-cloak x-transition>
                    <span x-text="selected.length"></span> jam dipilih:
                    <strong x-text="jamFmt(Math.min(...selected)) + ' - ' + jamFmt(Math.max(...selected) + 1)"></strong>
                </div>

                {{-- Submit --}}
                <form method="POST" action="{{ route('kasir.reschedule.store', $booking) }}"
                      x-show="selected.length > 0" x-cloak x-transition>
                    @csrf
                    <input type="hidden" name="tanggal"     :value="tanggal">
                    <input type="hidden" name="jam_mulai"   :value="selected.length ? jamFmt(Math.min(...selected)) : ''">
                    <input type="hidden" name="jam_selesai" :value="selected.length ? jamFmt(Math.max(...selected) + 1) : ''">
                    <div class="d-flex gap-2">
                        <a href="{{ route('kasir.reschedule.index') }}"
                           class="btn btn-outline-secondary btn-outline-batal">Batal</a>
                        <button type="submit" class="btn btn-navy-submit">
                            <i class="bi bi-check-circle me-1"></i>Simpan Jadwal Baru
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </section>
</div>

<script>
function rescheduleKasir() {
    return {
        tanggal  : '{{ $tanggalBaru }}',
        terisi   : @json($slotTerisi ?? []),
        selected : [],
        loading  : false,
        jamRange : Array.from({ length: 17 }, (_, i) => i + 6), // 06–22

        init() { /* slot sudah di-pass dari server, tidak perlu fetch awal */ },

        async fetchSlots() {
            this.loading  = true;
            this.selected = [];
            try {
                const url  = `/kasir/booking/slots?lapangan_id={{ $booking->lapangan_id }}&tanggal=${this.tanggal}&exclude={{ $booking->id }}`;
                const res  = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                const data = await res.json();
                this.terisi = data.slot_terisi ?? [];
            } catch(e) {
                console.error('fetch slots error', e);
            } finally {
                this.loading = false;
            }
        },

        toggle(jam) {
            if (this.terisi.includes(jam)) return;
            if (this.selected.includes(jam)) {
                this.selected = this.selected.filter(j => j !== jam);
                return;
            }
            const mn = this.selected.length ? Math.min(...this.selected) : null;
            const mx = this.selected.length ? Math.max(...this.selected) : null;
            if (this.selected.length === 0 || jam === mn - 1 || jam === mx + 1) {
                this.selected.push(jam);
            } else {
                this.selected = [jam];
            }
        },

        get bisaMundur() {
            const today = new Date(); today.setHours(0,0,0,0);
            const tgl   = new Date(this.tanggal); tgl.setHours(0,0,0,0);
            return tgl > today;
        },
        prevDay() {
            if (!this.bisaMundur) return;
            const d = new Date(this.tanggal);
            d.setDate(d.getDate() - 1);
            this.tanggal = d.toISOString().slice(0, 10);
            this.fetchSlots();
        },
        nextDay() {
            const d = new Date(this.tanggal);
            d.setDate(d.getDate() + 1);
            this.tanggal = d.toISOString().slice(0, 10);
            this.fetchSlots();
        },
        formatTanggal(str) {
            if (!str) return '';
            const d = new Date(str + 'T00:00:00');
            return d.toLocaleDateString('id-ID', { weekday:'long', day:'numeric', month:'long', year:'numeric' });
        },
        pad(n)    { return String(n).padStart(2, '0'); },
        jamFmt(n) { return this.pad(n) + ':00'; },
    };
}
</script>
@endsection
