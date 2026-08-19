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

    .info-hp {
        background-color: #f8f9fa; border-radius: 0.6rem; padding: 0.5rem 0.75rem;
        font-size: 0.8rem; color: #6c757d; margin-top: 0.4rem;
    }

    /* ── Date nav ── */
    .date-nav-btn {
        width: 44px; border: 1px solid #dee2e6; border-radius: 0.75rem;
        background: white; transition: all 0.2s ease; color: inherit;
    }
    .date-nav-btn:hover:not(.disabled) {
        background-color: #12244a; border-color: #12244a; color: white; transform: scale(1.05);
    }
    .date-display {
        background-color: #f1f3f5; border-radius: 0.75rem; padding: 0.75rem 1rem;
        font-weight: 600; color: #12244a;
    }

    /* ── Legend ── */
    .legend-dot {
        display: inline-block; width: 12px; height: 12px; border-radius: 3px;
        margin-right: 4px; vertical-align: middle;
    }

    /* ── Slot buttons ── */
    .slot-btn {
        border-radius: 0.75rem; padding: 0.6rem 0.5rem; font-weight: 500; font-size: 0.85rem;
        min-height: 54px; transition: all 0.15s ease; line-height: 1.4;
        border: 1px solid #dee2e6 !important;
    }
    .slot-kosong {
        background: #ffffff; color: #212529;
    }
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

    /* ── Info banner ── */
    .info-banner {
        background-color: #eaf1fb; border: 1px solid #cfe0f7; color: #12244a;
        border-radius: 0.75rem; padding: 0.8rem 1rem; font-size: 0.9rem; font-weight: 500;
    }

    /* ── Ringkasan ── */
    .ringkasan-box {
        background-color: #eaf1fb; border: 1px solid #cfe0f7; border-radius: 1rem; padding: 1.25rem;
    }
    .ringkasan-row        { display: flex; justify-content: space-between; padding: 0.4rem 0; font-size: 0.92rem; }
    .ringkasan-row .lbl   { color: #6b7a99; }
    .ringkasan-row .val   { color: #12244a; font-weight: 600; }
    .total-lbl            { color: #12244a; font-weight: 700; font-size: 1rem; }
    .total-val            { color: #ef7d2d; font-weight: 700; font-size: 1.15rem; }

    /* ── Submit buttons ── */
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

    html[data-bs-theme="dark"] .info-hp {
        background-color: rgba(255,255,255,0.06); color: #9aa3b5;
    }

    html[data-bs-theme="dark"] .date-nav-btn {
        background: rgba(255,255,255,0.07); border-color: rgba(255,255,255,0.18) !important; color: #d9deea;
    }
    html[data-bs-theme="dark"] .date-nav-btn:hover:not(.disabled) {
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

    /* Info banner dark */
    html[data-bs-theme="dark"] .info-banner {
        background-color: rgba(255,255,255,0.07);
        border-color: rgba(255,255,255,0.14);
        color: #d9deea;
    }

    /* Ringkasan dark */
    html[data-bs-theme="dark"] .ringkasan-box {
        background-color: rgba(255,255,255,0.07);
        border-color: rgba(255,255,255,0.14);
    }
    html[data-bs-theme="dark"] .ringkasan-box h6   { color: #d9deea !important; }
    html[data-bs-theme="dark"] .ringkasan-row .lbl { color: #9aa3b5; }
    html[data-bs-theme="dark"] .ringkasan-row .val { color: #d9deea !important; }
    html[data-bs-theme="dark"] .ringkasan-box hr   { border-color: rgba(255,255,255,0.14); }
    html[data-bs-theme="dark"] .total-lbl          { color: #d9deea !important; }
    html[data-bs-theme="dark"] .total-val          { color: #ef7d2d !important; }

    html[data-bs-theme="dark"] .text-muted { color: #9aa3b5 !important; }

    @keyframes slideFadeIn {
        from { opacity: 0; transform: translateY(8px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .ringkasan-box, .info-banner { animation: slideFadeIn 0.3s ease; }

    [x-cloak] { display: none !important; }
</style>

<div class="page-heading"
     x-data="bookingKasir()"
     x-init="init()">

    <h3 class="mb-4 fw-bold text-navy">Booking Manual</h3>

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <section class="section">
        <div class="card form-card shadow-sm">
            <div class="card-body p-4">

                <form method="POST" action="{{ route('kasir.booking.store') }}">
                    @csrf

                    {{-- ─── Lapangan & Tanggal ─── --}}
                    <p class="section-label"><i class="bi bi-geo-alt-fill"></i>Pilih Lapangan &amp; Tanggal</p>
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Lapangan</label>
                            <select x-model="lapanganId"
                                    @change="onLapanganChange($event)"
                                    class="form-select">
                                <option value="">-- Pilih Lapangan --</option>
                                @foreach ($lapanganList as $lap)
                                    <option value="{{ $lap->id }}"
                                            data-tarif="{{ $lap->tarif_per_jam }}"
                                            data-nama="{{ $lap->nama_lapang }}"
                                            data-kategori="{{ $lap->kategoriOlahraga->nama_kategori ?? '' }}"
                                            {{ ($lapanganId ?? '') == $lap->id ? 'selected' : '' }}>
                                        {{ $lap->nama_lapang }}
                                        ({{ $lap->kategoriOlahraga->nama_kategori }})
                                        — Rp{{ number_format($lap->tarif_per_jam, 0, ',', '.') }}/jam
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal</label>
                            <div class="d-flex align-items-center gap-2 position-relative">
                                {{-- Tombol kiri --}}
                                <button type="button" class="date-nav-btn d-flex align-items-center justify-content-center py-2"
                                        @click="prevDay"
                                        :disabled="!bisaMundur">
                                    <i class="bi bi-chevron-left"></i>
                                </button>

                                {{-- Tengah: klik buka date picker --}}
                                <div class="date-display flex-grow-1 text-center position-relative"
                                     style="cursor:pointer;" @click="$refs.tglPicker.showPicker ? $refs.tglPicker.showPicker() : $refs.tglPicker.click()">
                                    <i class="bi bi-calendar3 me-1"></i>
                                    <span x-text="formatTanggal(tanggal)"></span>
                                    {{-- Input tersembunyi --}}
                                    <input type="date" x-ref="tglPicker"
                                           x-model="tanggal"
                                           @change="fetchSlots()"
                                           min="{{ now()->toDateString() }}"
                                           style="position:absolute; opacity:0; width:1px; height:1px; top:0; left:0; pointer-events:none;">
                                </div>

                                {{-- Tombol kanan --}}
                                <button type="button" class="date-nav-btn d-flex align-items-center justify-content-center py-2"
                                        @click="nextDay">
                                    <i class="bi bi-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- ─── Data Pelanggan ─── --}}
                    <p class="section-label"><i class="bi bi-person-fill"></i>Data Pelanggan</p>
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Pelanggan</label>
                            <input type="text" name="nama_pelanggan" value="{{ old('nama_pelanggan') }}"
                                   class="form-control @error('nama_pelanggan') is-invalid @enderror">
                            @error('nama_pelanggan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">No. HP</label>
                            <input type="text" name="no_hp" value="{{ old('no_hp') }}"
                                   class="form-control @error('no_hp') is-invalid @enderror"
                                   placeholder="08xxxxxxxxxx">
                            @error('no_hp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="info-hp">
                                <i class="bi bi-info-circle me-1"></i>
                                Kalau no HP sudah pernah dipakai, booking otomatis nempel ke akun yang sama.
                            </div>
                        </div>
                    </div>

                    {{-- ─── Pilih Jam ─── --}}
                    <div x-show="lapanganId">
                        <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                            <p class="section-label mb-0"><i class="bi bi-clock-fill"></i>Pilih Jam</p>
                            <div class="small d-flex align-items-center gap-3 text-muted">
                                <span>
                                    <span class="legend-dot" style="background:rgba(200,210,220,0.4); border:2px solid rgba(200,210,220,0.6);"></span>
                                    Kosong
                                </span>
                                <span>
                                    <span class="legend-dot" style="background:#ef7d2d;"></span>
                                    Dipilih
                                </span>
                                <span>
                                    <span class="legend-dot" style="background:#f08080;"></span>
                                    Terisi
                                </span>
                            </div>
                        </div>

                        {{-- Spinner --}}
                        <div x-show="loading" class="text-center py-4 text-muted">
                            <div class="spinner-border spinner-border-sm me-2"></div>Memuat jadwal...
                        </div>

                        {{-- Grid slot --}}
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
                                            class="slot-btn w-100 d-flex align-items-center justify-content-center gap-1">
                                        <i :class="selected.includes(jam) ? 'bi bi-check-lg' : 'bi bi-clock'"></i>
                                        <span x-text="pad(jam) + ':00 - ' + pad(jam+1) + ':00'"></span>
                                    </button>
                                </div>
                            </template>
                        </div>

                        {{-- Info banner --}}
                        <div class="info-banner mb-3" x-show="selected.length > 0" x-cloak x-transition>
                            <span x-text="selected.length"></span> jam dipilih:
                            <strong x-text="jamFmt(Math.min(...selected)) + ' - ' + jamFmt(Math.max(...selected) + 1)"></strong>
                        </div>
                    </div>

                    <div x-show="!lapanganId" class="text-center py-5 text-muted">
                        <i class="bi bi-arrow-up-circle fs-1 d-block mb-2"></i>
                        Pilih lapangan dulu untuk melihat jam yang tersedia.
                    </div>

                    {{-- ─── Metode Bayar ─── --}}
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Metode Bayar</label>
                            <select name="metode_bayar"
                                    class="form-select @error('metode_bayar') is-invalid @enderror">
                                <option value="Tunai" @selected(old('metode_bayar') === 'Tunai')>Tunai</option>
                                <option value="QRIS"  @selected(old('metode_bayar') === 'QRIS')>QRIS</option>
                            </select>
                            @error('metode_bayar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- ─── Ringkasan ─── --}}
                    <div class="ringkasan-box mb-4"
                         x-show="selected.length > 0 && lapangan"
                         x-cloak x-transition>
                        <h6 class="fw-bold mb-3">
                            <i class="bi bi-receipt me-1" style="color:#ef7d2d;"></i>Ringkasan Biaya
                        </h6>
                        <div class="ringkasan-row">
                            <span class="lbl">Lapangan</span>
                            <span class="val" x-text="lapangan ? lapangan.nama : ''"></span>
                        </div>
                        <div class="ringkasan-row">
                            <span class="lbl">Durasi</span>
                            <span class="val" x-text="selected.length + ' jam'"></span>
                        </div>
                        <div class="ringkasan-row">
                            <span class="lbl">Tarif</span>
                            <span class="val"
                                  x-text="lapangan ? 'Rp ' + Number(lapangan.tarif).toLocaleString('id-ID') + '/jam' : ''">
                            </span>
                        </div>
                        <hr>
                        <div class="ringkasan-row">
                            <span class="total-lbl">Total Bayar</span>
                            <span class="total-val"
                                  x-text="lapangan ? 'Rp ' + (selected.length * lapangan.tarif).toLocaleString('id-ID') : ''">
                            </span>
                        </div>
                    </div>

                    {{-- Hidden fields --}}
                    <input type="hidden" name="lapangan_id" :value="lapanganId">
                    <input type="hidden" name="tanggal"     :value="tanggal">
                    <input type="hidden" name="jam_mulai"
                           :value="selected.length ? jamFmt(Math.min(...selected)) : ''">
                    <input type="hidden" name="jam_selesai"
                           :value="selected.length ? jamFmt(Math.max(...selected) + 1) : ''">

                    <div class="d-flex gap-2 mt-2">
                        <a href="{{ route('kasir.booking.index') }}"
                           class="btn btn-outline-secondary btn-outline-batal">Batal</a>
                        <button type="submit" class="btn btn-navy-submit"
                                :disabled="selected.length === 0 || !lapanganId">
                            <i class="bi bi-check-circle me-1"></i>Simpan Booking
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </section>
</div>

<script>
function bookingKasir() {
    return {
        lapanganId : '{{ $lapanganId ?? '' }}',
        tanggal    : '{{ $tanggal }}',
        lapangan   : null,   // { nama, tarif }
        terisi     : [],
        selected   : [],
        loading    : false,
        jamRange   : Array.from({ length: 16 }, (_, i) => i + 7), // 07–22

        init() {
            if (this.lapanganId) {
                this.syncLapangan();
                this.fetchSlots();
            }
        },

        // Ambil info lapangan dari <option> yang terpilih — tanpa request
        syncLapangan() {
            const sel = this.$el.querySelector('select[x-model="lapanganId"]');
            if (!sel) return;
            const opt = sel.options[sel.selectedIndex];
            if (opt && opt.value) {
                this.lapangan = {
                    nama : opt.dataset.nama,
                    tarif: parseInt(opt.dataset.tarif),
                };
            } else {
                this.lapangan = null;
            }
        },

        onLapanganChange(e) {
            this.selected = [];
            this.terisi   = [];
            this.syncLapangan();
            if (this.lapanganId) this.fetchSlots();
        },

        // Fetch slot terisi via JSON — tanpa reload halaman
        async fetchSlots() {
            if (!this.lapanganId) return;
            this.loading  = true;
            this.selected = [];
            try {
                const url  = `{{ route('kasir.booking.slots') }}?lapangan_id=${this.lapanganId}&tanggal=${this.tanggal}`;
                const res  = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                const data = await res.json();
                this.terisi = data.slot_terisi ?? [];
            } catch (e) {
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

        pad(n)    { return String(n).padStart(2, '0'); },
        jamFmt(n) { return this.pad(n) + ':00'; },

        // ── Navigasi tanggal ──
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
    };
}
</script>
@endsection
