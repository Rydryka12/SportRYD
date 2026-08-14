@extends('layouts.customer')

@section('content')
<style>
    /* Menyembunyikan elemen sebelum Alpine.js siap */
    [x-cloak] { display: none !important; }

    /* --- WARNA UTAMA --- */
    .text-navy { color: #12244a; }
    .text-orange { color: #ef7d2d; }

    /* --- LAYOUT & TYPOGRAPHY --- */
    .paket-header-card { border-radius: 1.25rem; max-width: 850px; margin: 0 auto; }
    .section-title { font-weight: 700; color: #12244a; margin-bottom: 1rem; font-size: 1rem; }

    /* --- ANIMASI POP --- */
    @keyframes pop {
        0% { transform: scale(0.9); }
        60% { transform: scale(1.08); }
        100% { transform: scale(1.03) translateY(-2px); }
    }
    @keyframes slideFadeIn {
        from { opacity: 0; transform: translateY(8px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* --- PILIHAN LAPANGAN (Cards) --- */
    .lapangan-option {
        border: 1px solid #dee2e6;
        background-color: white;
        border-radius: 0.75rem;
        padding: 1rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .lapangan-option:hover {
        border-color: #ef7d2d;
        background-color: #fff6f0;
        transform: translateY(-3px);
        box-shadow: 0 4px 10px rgba(239,125,45,0.15);
    }
    .lapangan-option.active {
        background-color: #12244a;
        border-color: #12244a;
        color: white;
        transform: translateY(-2px) scale(1.03);
        box-shadow: 0 6px 14px rgba(18,36,74,0.35);
        animation: pop 0.25s ease;
    }
    .lapangan-option.active .text-navy { color: white !important; }
    .lapangan-option.active .text-muted { color: #e2e8f0 !important; }

    /* --- PILIHAN HARI (Pills) --- */
    .hari-pill {
        border-radius: 50px;
        padding: 0.5rem 1.25rem;
        font-weight: 600;
        font-size: 0.9rem;
        border: 1px solid #dee2e6;
        background: white;
        color: #212529;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .hari-pill:hover {
        border-color: #ef7d2d;
        background-color: #fff6f0;
        transform: translateY(-3px);
        box-shadow: 0 4px 10px rgba(239,125,45,0.15);
    }
    .hari-pill.active {
        background-color: #12244a;
        border-color: #12244a;
        color: white;
        transform: translateY(-2px) scale(1.03);
        box-shadow: 0 6px 14px rgba(18,36,74,0.35);
        animation: pop 0.25s ease;
    }

    /* --- PILIHAN JAM (Slots) --- */
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
    .slot-kosong:hover:not(:disabled) {
        border-color: #ef7d2d;
        background-color: #fff6f0;
        transform: translateY(-3px);
        box-shadow: 0 4px 10px rgba(239,125,45,0.15);
    }
    .slot-dipilih {
        background-color: #12244a;
        border: 1px solid #12244a;
        color: white;
        transform: translateY(-2px) scale(1.03);
        box-shadow: 0 6px 14px rgba(18,36,74,0.35);
        animation: pop 0.25s ease;
    }
    .slot-disabled {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        color: #ced4da;
        cursor: not-allowed;
    }

    /* --- BANNERS & BOXES --- */
    .info-banner, .ketersediaan-box {
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
    .ketersediaan-box {
        background-color: #eaf1fb;
        border: 1px solid #cfe0f7;
        border-radius: 1rem;
        padding: 1.5rem;
    }
    .slot-tanggal-row { display: flex; justify-content: space-between; padding: 0.4rem 0; font-size: 0.9rem; color: #12244a; border-bottom: 1px solid rgba(18,36,74,0.1);}
    .slot-tanggal-row:last-child { border-bottom: none; }
    .status-tersedia { color: #16a34a; font-weight: 700; }
    .status-bentrok { color: #dc3545; font-weight: 700; }
    .ringkasan-row { display: flex; justify-content: space-between; padding: 0.35rem 0; font-size: 0.95rem; color: #12244a;}

    /* --- BUTTON SUBMIT --- */
    .btn-orange-submit {
        background-color: #ef7d2d;
        border: none;
        color: white;
        font-weight: 600;
        border-radius: 0.75rem;
        padding: 0.8rem 1.5rem;
        font-size: 1rem;
        transition: all 0.2s ease;
    }
    .btn-orange-submit:hover:not(:disabled) {
        background-color: #d96b22;
        transform: translateY(-2px);
        box-shadow: 0 6px 14px rgba(239,125,45,0.35);
    }
    .btn-orange-submit:disabled { background-color: #f1c9a9; cursor: not-allowed; }

    .alert-bentrok {
        background-color: #fdecea;
        border: 1px solid #f5c6cb;
        color: #c94b4b;
        border-radius: 0.75rem;
        padding: 0.8rem 1rem;
        font-size: 0.88rem;
    }
</style>

<!-- Tombol Kembali -->
<a href="javascript:history.back()" class="text-muted small mb-4 d-inline-block text-decoration-none fw-semibold">
    <i class="bi bi-arrow-left me-1"></i> Kembali
</a>

<div x-data="jadwalTetap()" class="paket-header-card mb-5">
    <div class="card border-0 shadow-sm p-4 p-md-5" style="border-radius: 1.25rem;">
        
        <!-- Header Paket -->
        <div class="mb-4 pb-3 border-bottom">
            <h3 class="fw-bold text-navy mb-2">{{ $paketLangganan->nama_paket }}</h3>
            <p class="text-muted mb-0">
                <span class="fw-semibold text-orange">{{ $paketLangganan->jumlah_sesi }}x sesi</span> &middot; 
                <span class="fw-bold text-navy">{{ $paketLangganan->durasi_jam_per_sesi }} jam/sesi</span> &middot; Jadwal Tetap
            </p>
        </div>

        <!-- 1. Pilih Lapangan -->
        <p class="section-title"><i class="bi bi-1-circle-fill text-orange me-2"></i> Pilih Lapangan</p>
        <div class="row g-3 mb-5">
            @foreach ($lapanganList as $lap)
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="lapangan-option h-100 d-flex flex-column justify-content-center shadow-sm"
                         :class="{ 'active': selectedLapangan === {{ $lap->id }} }"
                         @click="pilihLapangan({{ $lap->id }})">
                        <span class="fw-bold text-navy mb-1" style="font-size: 0.95rem;">{{ $lap->nama_lapang }}</span>
                        <span class="text-muted small">Rp{{ number_format($lap->tarif_per_jam, 0, ',', '.') }}/jam</span>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- 2. Pilih Hari Rutin -->
        <p class="section-title">
            <i class="bi bi-2-circle-fill text-orange me-2"></i> Pilih Hari Rutin
            <span class="text-muted fw-normal ms-1" style="font-size: 0.85rem;">(bisa lebih dari satu)</span>
        </p>
        <div class="d-flex flex-wrap gap-2 mb-5">
            <template x-for="hari in hariOptions" :key="hari.value">
                <button type="button"
                        class="hari-pill shadow-sm"
                        :class="{ 'active': selectedHari.includes(hari.label) }"
                        @click="toggleHari(hari.label)">
                    <span x-text="hari.label"></span>
                </button>
            </template>
        </div>

        <!-- 3. Pilih Jam -->
        <p class="section-title">
            <i class="bi bi-3-circle-fill text-orange me-2"></i> Pilih Jam 
            <span class="text-orange fw-bold ms-1" style="font-size: 0.85rem;" x-text="'(Wajib pilih ' + durasiSesi + ' jam berdekatan, maks. 23:00)'"></span>
        </p>
        <div class="row g-2 mb-3">
            <template x-for="jam in jamOptions" :key="jam">
                <div class="col-4 col-sm-3 col-md-2">
                    <button type="button"
                            class="slot-btn w-100 d-flex flex-column align-items-center justify-content-center shadow-sm"
                            :class="{
                                'slot-dipilih': selectedJamList.includes(jam),
                                'slot-disabled': isDisabledSlot(jam),
                                'slot-kosong': !selectedJamList.includes(jam) && !isDisabledSlot(jam)
                            }"
                            :disabled="isDisabledSlot(jam)"
                            @click="pilihJam(jam)">
                        <span x-show="!selectedJamList.includes(jam)" class="fw-semibold"><i class="bi bi-clock me-1 mb-1 d-block"></i><span x-text="jamFormat(jam)"></span></span>
                        <span x-show="selectedJamList.includes(jam)" x-cloak class="fw-bold"><i class="bi bi-check-lg me-1 mb-1 d-block"></i><span x-text="jamFormat(jam)"></span></span>
                        <small x-show="selectedJamList.includes(jam)" x-cloak class="fw-normal mt-1" style="font-size: 0.7rem;">DIPILIH</small>
                    </button>
                </div>
            </template>
        </div>

        <!-- Info jam yang dipilih -->
        <div class="info-banner mb-5 shadow-sm" x-show="selectedJamList.length > 0" x-cloak x-transition>
            <i class="bi bi-info-circle-fill me-2" style="color: #ef7d2d;"></i> 
            Waktu main: <span class="fw-bold" x-text="formatRentangWaktu()"></span>
            <span class="text-navy ms-1 opacity-75" x-text="'(Durasi ' + selectedJamList.length + ' dari ' + durasiSesi + ' jam yang diminta)'"></span>
        </div>

        <!-- Preview Ketersediaan -->
        <div class="ketersediaan-box mb-4 shadow-sm" x-show="siapPreview()" x-cloak x-transition>
            <p class="fw-bold text-navy mb-3" style="font-size: 1.1rem;">
                <i class="bi bi-calendar-check me-2 text-orange"></i>Ketersediaan {{ $paketLangganan->jumlah_sesi }} Sesi ke Depan
            </p>

            <div class="mb-4">
                <template x-for="(item, idx) in previewSesi()" :key="idx">
                    <div class="slot-tanggal-row">
                        <span class="fw-medium" x-text="item.label"></span>
                        <span :class="item.bentrok ? 'status-bentrok' : 'status-tersedia'">
                            <i :class="item.bentrok ? 'bi bi-x-circle-fill' : 'bi bi-check-circle-fill'"></i>
                            <span x-text="item.bentrok ? 'Bentrok' : 'Tersedia'"></span>
                        </span>
                    </div>
                </template>
            </div>

            <div class="pt-3 border-top" style="border-color: rgba(18,36,74,0.1) !important;">
                <div class="ringkasan-row">
                    <span class="opacity-75">Lapangan</span>
                    <span class="fw-bold" x-text="namaLapanganTerpilih()"></span>
                </div>
                <div class="ringkasan-row">
                    <span class="opacity-75">Jadwal</span>
                    <span class="fw-bold text-end" x-text="selectedHari.join(' & ') + ', ' + formatRentangWaktu()"></span>
                </div>
                <div class="ringkasan-row">
                    <span class="opacity-75">Total Sesi</span>
                    <span class="fw-bold">{{ $paketLangganan->jumlah_sesi }} sesi</span>
                </div>
            </div>

            <div class="mt-3 pt-3 border-top" style="border-color: rgba(18,36,74,0.1) !important;">
                <div class="ringkasan-row align-items-center">
                    <span class="fw-bold text-navy fs-6">Total Pembayaran</span>
                    <span class="fw-bold text-orange fs-4">Rp {{ number_format($paketLangganan->harga, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Peringatan kalau ada bentrok -->
        <div class="alert-bentrok mb-4 shadow-sm" x-show="siapPreview() && adaBentrok()" x-cloak>
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Peringatan!</strong> Ada tanggal yang bentrok. Silakan ganti lapangan, hari, atau jam sebelum melanjutkan pembayaran.
        </div>

        <!-- Submit Button -->
        <form method="POST" action="{{ route('customer.paket.jadwal-tetap.store', $paketLangganan) }}">
            @csrf
            <input type="hidden" name="lapangan_id" :value="selectedLapangan">
            <template x-for="h in selectedHari" :key="h">
                <input type="hidden" name="hari[]" :value="h">
            </template>
            <input type="hidden" name="jam_mulai" :value="selectedJamList.length > 0 ? jamFormat(Math.min(...selectedJamList)) : ''">

            <button type="submit"
                    class="btn btn-orange-submit w-100 shadow-sm"
                    :disabled="!bisaSubmit()">
                Ambil Paket &amp; Lanjut Bayar <i class="bi bi-arrow-right ms-1"></i>
            </button>
        </form>
    </div>
</div>

<script>
    function jadwalTetap() {
        return {
            selectedLapangan: null,
            selectedHari: [],
            selectedJamList: [], 
            
            // Batas jam dari 07:00 (7) hingga maksimal slot agar selesainya mentok di 23:00
            jamOptions: Array.from(
                { length: 23 - {{ $paketLangganan->durasi_jam_per_sesi }} - 7 + 1 },
                (_, i) => i + 7
            ),
            jumlahSesi: {{ $paketLangganan->jumlah_sesi }},
            durasiSesi: {{ $paketLangganan->durasi_jam_per_sesi }},

            hariOptions: [
                { value: 1, label: 'Senin' },
                { value: 2, label: 'Selasa' },
                { value: 3, label: 'Rabu' },
                { value: 4, label: 'Kamis' },
                { value: 5, label: 'Jumat' },
                { value: 6, label: 'Sabtu' },
                { value: 7, label: 'Minggu' },
            ],

            lapanganData: @json($lapanganList->map(fn($lp) => [
                'id' => $lp->id, 
                'nama' => $lp->nama_lapang
            ])->values()),

            bookingTerpakai: {{ Illuminate\Support\Js::from($bookingTerpakai) }},

            pilihLapangan(id) {
                this.selectedLapangan = id;
            },

            toggleHari(label) {
                if (this.selectedHari.includes(label)) {
                    this.selectedHari = this.selectedHari.filter(h => h !== label);
                } else {
                    this.selectedHari.push(label);
                }
            },

            pilihJam(jam) {
                if (this.durasiSesi === 1) {
                    this.selectedJamList = this.selectedJamList.includes(jam) ? [] : [jam];
                    return;
                }

                if (this.selectedJamList.length === this.durasiSesi && this.selectedJamList.includes(jam)) {
                    this.selectedJamList = [];
                    return;
                }

                let blokBaru = [];
                for (let i = 0; i < this.durasiSesi; i++) {
                    blokBaru.push(jam + i);
                }

                // Pastikan blok tidak melewati batas jam 23:00 (max slot index 22 karena 22:00 - 23:00)
                if (Math.max(...blokBaru) <= 22) {
                    this.selectedJamList = blokBaru;
                } else {
                    let blokMundur = [];
                    for (let i = this.durasiSesi - 1; i >= 0; i--) {
                        blokMundur.push(jam - i);
                    }
                    if (Math.min(...blokMundur) >= 7) {
                        this.selectedJamList = blokMundur;
                    }
                }
            },

            isDisabledSlot(jam) {
                return false; 
            },

            jamFormat(jam) {
                return String(jam).padStart(2, '0') + ':00';
            },

            formatRentangWaktu() {
                if (this.selectedJamList.length === 0) return '';
                const minJam = Math.min(...this.selectedJamList);
                const maxJam = Math.max(...this.selectedJamList) + 1;
                return this.jamFormat(minJam) + ' - ' + this.jamFormat(maxJam);
            },

            namaLapanganTerpilih() {
                const lp = this.lapanganData.find(l => l.id === this.selectedLapangan);
                return lp ? lp.nama : '';
            },

            siapPreview() {
                return this.selectedLapangan && this.selectedHari.length > 0 && this.selectedJamList.length === this.durasiSesi;
            },

            previewSesi() {
                if (!this.siapPreview()) return [];

                const hariIsoMap = {
                    'Senin': 1, 'Selasa': 2, 'Rabu': 3, 'Kamis': 4,
                    'Jumat': 5, 'Sabtu': 6, 'Minggu': 7,
                };
                const targetIsoList = this.selectedHari.map(h => hariIsoMap[h]);

                const hasil = [];
                let current = new Date();
                const minJam = Math.min(...this.selectedJamList);
                const maxJam = Math.max(...this.selectedJamList) + 1;
                
                const jamMulaiStr = this.jamFormat(minJam);
                const jamSelesaiStr = this.jamFormat(maxJam);
                const bookingLapangan = this.bookingTerpakai[this.selectedLapangan] || [];

                let pengaman = 0;
                while (hasil.length < this.jumlahSesi && pengaman < 400) {
                    current.setDate(current.getDate() + 1);
                    pengaman++;

                    const dayIso = current.getDay() === 0 ? 7 : current.getDay();
                    if (!targetIsoList.includes(dayIso)) continue;

                    const tahun = current.getFullYear();
                    const bulan = String(current.getMonth() + 1).padStart(2, '0');
                    const tanggal = String(current.getDate()).padStart(2, '0');
                    const tglStr = `${tahun}-${bulan}-${tanggal}`;
                    
                    const namaHari = Object.keys(hariIsoMap).find(k => hariIsoMap[k] === dayIso);
                    const label = `${namaHari}, ${current.getDate()} ${current.toLocaleDateString('id-ID', { month: 'long' })} ${current.getFullYear()}`;

                    const bentrok = bookingLapangan.some(b =>
                        b.tanggal === tglStr &&
                        jamMulaiStr < b.jam_selesai &&
                        jamSelesaiStr > b.jam_mulai
                    );

                    hasil.push({ label, bentrok });
                }

                return hasil;
            },

            adaBentrok() {
                return this.previewSesi().some(item => item.bentrok);
            },

            bisaSubmit() {
                return this.siapPreview() && !this.adaBentrok() && this.selectedJamList.length === this.durasiSesi;
            }
        }
    }
</script>
@endsection