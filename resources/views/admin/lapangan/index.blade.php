@extends('layouts.app')
@section('title','Lapangan')
@section('sblapang','active')

@section('content')
<style>
    /* ── Toolbar card ── */
    .toolbar-card {
        background: var(--bs-body-bg, #fff);
        border-radius: 12px !important;
        border: 1px solid #e9ecef;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,.04);
        margin-bottom: 1rem;
        padding: 1rem;
    }

    /* ── Lapangan card ── */
    .lapangan-card {
        border-radius: 12px !important;
        border: 1px solid #eef0f2 !important;
        overflow: hidden !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .lapangan-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,.08) !important;
    }
    .lapangan-img {
        width: 100%;
        height: 150px;
        object-fit: cover;
        display: block;
        border-radius: 0 !important; /* foto tidak perlu radius, card yang punya overflow:hidden */
    }
    .lapangan-img-placeholder {
        width: 100%;
        height: 150px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.25rem;
        font-weight: 700;
        color: white;
        border-radius: 0 !important;
    }

    /* ── Search ── */
    .search-box { position: relative; }
    .search-box i {
        position: absolute;
        left: 0.85rem;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        pointer-events: none;
        font-size: 0.875rem;
        z-index: 2;
    }
    .search-box input {
        padding-left: 2.3rem !important;
        border-radius: 8px !important;
        font-size: 0.875rem;
    }
    html[data-bs-theme="dark"] .search-box input {
        background: rgba(255,255,255,0.07) !important;
        border-color: rgba(255,255,255,0.15) !important;
        color: #d9deea !important;
    }
    html[data-bs-theme="dark"] .search-box input::placeholder {
        color: rgba(217,222,234,0.4) !important;
    }
    html[data-bs-theme="dark"] .search-box i {
        color: rgba(217,222,234,0.5) !important;
    }

    /* ── Filter chips ── */
    .filter-chip {
        border-radius: 8px !important;
        padding: 0.35rem 0.875rem;
        font-size: 0.8125rem;
        font-weight: 600;
        border: 1.5px solid #dee2e6;
        background: #fff;
        color: #4b5563;
        cursor: pointer;
        transition: all 0.15s ease;
        white-space: nowrap;
        line-height: 1.4;
    }
    .filter-chip:hover  { border-color: #12244a; color: #12244a; background: #f0f3fa; }
    .filter-chip.active { background: #12244a !important; color: #fff !important; border-color: #12244a !important; }

    /* ── Tombol aksi ── */
    .btn-aksi {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        width: 100%;
        height: 36px;
        border-radius: 8px !important;
        border: 1.5px solid;
        font-size: 0.9rem;
        line-height: 1;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.15s ease;
        font-weight: 600;
        background: transparent;
        padding: 0 !important;
    }
    .btn-aksi i {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        line-height: 1 !important;
        font-size: 0.9rem !important;
    }
    .btn-edit  { border-color: #4b73b8; color: #4b73b8; }
    .btn-edit:hover  { background: #4b73b8; color: #fff; }
    .btn-hapus { border-color: #dc3545; color: #dc3545; }
    .btn-hapus:hover { background: #dc3545; color: #fff; }

    /* ═══════════════════════════════
       DARK MODE
    ═══════════════════════════════ */
    html[data-bs-theme="dark"] .toolbar-card {
        background: #152040 !important;
        border-color: rgba(255,255,255,0.1) !important;
        box-shadow: none !important;
    }
    html[data-bs-theme="dark"] .lapangan-card {
        background: #152040 !important;
        border-color: rgba(255,255,255,0.1) !important;
    }
    html[data-bs-theme="dark"] .lapangan-card h6 { color: #e2e7f0 !important; }

    html[data-bs-theme="dark"] .filter-chip {
        background: #1c2f5a !important;
        border-color: rgba(255,255,255,0.15) !important;
        color: #c5cde0 !important;
    }
    html[data-bs-theme="dark"] .filter-chip:hover {
        background: #253770 !important;
        border-color: rgba(255,255,255,0.3) !important;
        color: #fff !important;
    }
    html[data-bs-theme="dark"] .filter-chip.active {
        background: #ef7d2d !important;
        color: #fff !important;
        border-color: #ef7d2d !important;
    }
    html[data-bs-theme="dark"] .btn-edit  { border-color: #7fa4d8 !important; color: #7fa4d8 !important; }
    html[data-bs-theme="dark"] .btn-edit:hover  { background: #7fa4d8 !important; color: #0f1b35 !important; }
    html[data-bs-theme="dark"] .btn-hapus { border-color: #f27282 !important; color: #f27282 !important; }
    html[data-bs-theme="dark"] .btn-hapus:hover { background: #f27282 !important; color: #0f1b35 !important; }

    html[data-bs-theme="dark"] .modal-hapus-container {
        background: #1a2f5e !important;
        color: #d9deea !important;
    }
    html[data-bs-theme="dark"] .modal-hapus-container h5,
    html[data-bs-theme="dark"] .modal-hapus-container .fw-semibold { color: #d9deea !important; }
    html[data-bs-theme="dark"] .modal-hapus-container .btn-batal {
        background: rgba(255,255,255,0.08) !important;
        border-color: rgba(255,255,255,0.2) !important;
        color: #c5cde0 !important;
    }

    [x-cloak] { display: none !important; }
</style>

@php
    $lapanganData = $lapanganList->map(function ($l) {
        return [
            'id'          => $l->id,
            'nama'        => $l->nama_lapang,
            'deskripsi'   => $l->deskripsi ?? '',
            'kategori'    => $l->kategoriOlahraga->nama_kategori ?? 'Umum',
            'kategori_id' => $l->kategori_id,
            'tarif'       => number_format($l->tarif_per_jam, 0, ',', '.'),
            'status'      => $l->status_aktif,
            'foto'        => $l->foto ? asset('storage/' . $l->foto) : null,
            'url_edit'    => route('admin.lapangan.edit', $l->id),
            'url_delete'  => route('admin.lapangan.destroy', $l->id),
        ];
    })->values();
@endphp

<div class="page-heading">
    <div class="page-title mb-3">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Data Lapangan</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Data Lapangan</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div x-data="lapanganAdmin()">

        {{-- ── Toolbar: search + filter + tambah ── --}}
        <div class="toolbar-card">
            <div class="row g-2 align-items-center">
                {{-- Searchbar live --}}
                <div class="col-12 col-lg-4">
                    <div class="search-box">
                        <i class="bi bi-search"></i>
                        <input type="text"
                               x-model="search"
                               class="form-control"
                               placeholder="Cari nama lapangan...">
                    </div>
                </div>

                {{-- Filter kategori --}}
                <div class="col-12 col-lg-5">
                    <div class="d-flex flex-wrap gap-2">
                        <button type="button"
                                @click="aktifKategori = null"
                                class="filter-chip"
                                :class="{ 'active': aktifKategori === null }">
                            Semua
                        </button>
                        @foreach ($kategoriList as $kat)
                            <button type="button"
                                    @click="aktifKategori = {{ $kat->id }}"
                                    class="filter-chip"
                                    :class="{ 'active': aktifKategori === {{ $kat->id }} }">
                                {{ $kat->nama_kategori }}
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Tombol tambah --}}
                <div class="col-12 col-lg-3 text-lg-end">
                    <a href="{{ route('admin.lapangan.create') }}"
                       class="btn btn-sm w-100"
                       style="background:#ef7d2d;color:white;border-radius:8px !important;font-weight:600;">
                        <i class="bi bi-plus-lg me-1"></i> Tambah
                    </a>
                </div>
            </div>

            {{-- Counter --}}
            <div class="mt-2 small text-muted">
                Menampilkan <span class="fw-semibold" x-text="filtered().length"></span> lapangan
            </div>
        </div>

        {{-- ── Card Grid ── --}}
        <div class="row g-3" x-show="filtered().length > 0">
            <template x-for="lp in filtered()" :key="lp.id">
                <div class="col-12 col-sm-6 col-xl-4">
                    <div class="card lapangan-card shadow-sm h-100">

                        {{-- Foto / Placeholder --}}
                        <template x-if="lp.foto">
                            <img :src="lp.foto" :alt="lp.nama" class="lapangan-img">
                        </template>
                        <template x-if="!lp.foto">
                            <div class="lapangan-img-placeholder"
                                 :style="`background: ${placeholderColor(lp.kategori_id)};`"
                                 x-text="lp.nama.charAt(0).toUpperCase()">
                            </div>
                        </template>

                        <div class="card-body d-flex flex-column p-3">
                            {{-- Badges atas --}}
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge"
                                      style="background:rgba(239,125,45,0.15);color:#ef7d2d;font-weight:600;font-size:0.75rem;border-radius:6px;"
                                      x-text="lp.kategori"></span>
                                <span class="badge"
                                      :class="lp.status === 'Aktif' ? 'bg-success' : 'bg-secondary'"
                                      style="font-size:0.75rem;border-radius:6px;"
                                      x-text="lp.status"></span>
                            </div>

                            {{-- Nama + deskripsi --}}
                            <h6 class="fw-bold mb-1" style="font-size:0.9375rem;" x-text="lp.nama"></h6>
                            <p class="text-muted small mb-2 flex-grow-1"
                               style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;font-size:0.8125rem;"
                               x-text="lp.deskripsi || '—'"></p>

                            {{-- Tarif --}}
                            <div class="mb-3">
                                <span class="text-muted" style="font-size:0.75rem;">Tarif/jam</span>
                                <div class="fw-bold" style="color:#ef7d2d;font-size:0.9375rem;" x-text="'Rp ' + lp.tarif"></div>
                            </div>

                            {{-- Aksi --}}
                            <div class="d-flex gap-2 mt-auto">
                                <a :href="lp.url_edit" class="btn-aksi btn-edit" style="flex:1;">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" @click="konfirmasiHapus(lp)"
                                        class="btn-aksi btn-hapus" style="flex:1;">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        {{-- Empty state --}}
        <div class="text-center py-5" x-show="filtered().length === 0" x-cloak>
            <div class="card border-0 shadow-sm p-4" style="border-radius:12px;">
                <i class="bi bi-inbox fs-1 text-secondary d-block mb-3"></i>
                <h5 class="fw-bold mb-2">Lapangan Tidak Ditemukan</h5>
                <p class="text-muted mb-0 small">Coba ganti kata kunci atau filter kategori.</p>
            </div>
        </div>

        {{-- ── Modal konfirmasi hapus ── --}}
        <template x-teleport="body">
            <div x-show="hapusOpen"
                 x-cloak
                 @keydown.escape.window="hapusOpen = false"
                 @click.self="hapusOpen = false"
                 style="position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,0.5);display:flex;align-items:center;justify-content:center;padding:1rem;">
                <div x-show="hapusOpen"
                     x-transition
                     class="modal-hapus-container"
                     style="background:white;border-radius:12px;width:100%;max-width:420px;box-shadow:0 20px 25px -5px rgba(0,0,0,.2);overflow:hidden;">
                    <div style="padding:1.5rem;">
                        <div style="width:48px;height:48px;background:#fdecea;border-radius:50%;display:flex;align-items:center;justify-content:center;margin-bottom:1rem;">
                            <i class="bi bi-trash" style="color:#dc3545;font-size:1.25rem;"></i>
                        </div>
                        <h5 class="fw-bold mb-2" style="font-size:1.125rem;">Hapus Lapangan?</h5>
                        <p class="text-muted mb-1" style="font-size:0.875rem;">Lapangan berikut akan dihapus permanen:</p>
                        <p class="fw-semibold mb-4" style="font-size:0.9375rem;" x-text="hapusTarget?.nama"></p>
                        <div class="d-flex gap-2 justify-content-end">
                            <button type="button" @click="hapusOpen = false"
                                    class="btn btn-sm btn-batal"
                                    style="border:1px solid #dee2e6;background:#f8f9fa;color:#6b7280;font-weight:600;border-radius:8px !important;padding:0.5rem 1.1rem;">
                                Batal
                            </button>
                            <button type="button" @click="submitHapus()"
                                    class="btn btn-sm"
                                    style="background:#dc3545;border:none;color:white;font-weight:600;border-radius:8px !important;padding:0.5rem 1.1rem;">
                                Ya, Hapus
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        {{-- Form hapus tersembunyi --}}
        <form id="form-hapus" method="POST" style="display:none;">
            @csrf
            @method('DELETE')
        </form>

    </div>{{-- end x-data --}}
</div>

<script>
function lapanganAdmin() {
    const gradients = [
        'linear-gradient(135deg,#12244a,#1b3060)',
        'linear-gradient(135deg,#ef7d2d,#d8691b)',
        'linear-gradient(135deg,#2563eb,#1d4ed8)',
        'linear-gradient(135deg,#16a34a,#15803d)',
        'linear-gradient(135deg,#7c3aed,#6d28d9)',
        'linear-gradient(135deg,#dc2626,#b91c1c)',
    ];

    return {
        search: '',
        aktifKategori: null,
        hapusOpen: false,
        hapusTarget: null,
        data: @json($lapanganData),

        filtered() {
            return this.data.filter(lp => {
                const matchKat  = this.aktifKategori === null || lp.kategori_id === this.aktifKategori;
                const matchCari = lp.nama.toLowerCase().includes(this.search.toLowerCase());
                return matchKat && matchCari;
            });
        },

        placeholderColor(kategoriId) {
            return gradients[(kategoriId - 1) % gradients.length];
        },

        konfirmasiHapus(lp) {
            this.hapusTarget = lp;
            this.hapusOpen   = true;
        },

        submitHapus() {
            if (!this.hapusTarget) return;
            const form   = document.getElementById('form-hapus');
            form.action  = this.hapusTarget.url_delete;
            form.submit();
        },
    };
}
</script>
@endsection
