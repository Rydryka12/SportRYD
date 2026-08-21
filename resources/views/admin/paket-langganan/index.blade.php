@extends('layouts.app')

@section('title', 'Paket Langganan')
@section('sbpaket', 'active')

@section('content')
<style>
    /* Badge tipe */
    .badge-kuota {
        background: rgba(37,99,235,0.15);
        color: #3b82f6;
        border: 1px solid rgba(37,99,235,0.25);
        border-radius: 6px !important;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.35rem 0.65rem;
    }
    .badge-jadwal {
        background: rgba(124,58,237,0.15);
        color: #8b5cf6;
        border: 1px solid rgba(124,58,237,0.25);
        border-radius: 6px !important;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.35rem 0.65rem;
    }

    /* Nav pills */
    .nav-pills .nav-link {
        color: #6c757d;
        font-weight: 500;
        border-radius: 8px !important;
        font-size: 0.875rem;
        padding: 0.4rem 1rem;
    }
    .nav-pills .nav-link:hover { color: #ef7d2d; background: rgba(239,125,45,0.08); }
    .nav-pills .nav-link.active {
        background-color: #ef7d2d !important;
        color: white !important;
    }

    /* Dark mode */
    html[data-bs-theme="dark"] .nav-pills .nav-link {
        color: #9ba8c0;
    }
    html[data-bs-theme="dark"] .nav-pills .nav-link:hover {
        color: #ef7d2d;
        background: rgba(239,125,45,0.12);
    }
    html[data-bs-theme="dark"] .badge-kuota {
        background: rgba(59,130,246,0.2);
        color: #93c5fd;
        border-color: rgba(59,130,246,0.3);
    }
    html[data-bs-theme="dark"] .badge-jadwal {
        background: rgba(139,92,246,0.2);
        color: #c4b5fd;
        border-color: rgba(139,92,246,0.3);
    }
</style>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Paket Langganan</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Paket Langganan</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible show fade">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible show fade">
                <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Filter Tab Kategori -->
        <ul class="nav nav-pills mb-3 gap-1">
            @foreach ($kategoriList as $kategori)
                <li class="nav-item">
                    <a class="nav-link {{ $kategoriId == $kategori->id ? 'active' : '' }}"
                       href="{{ route('admin.paket-langganan.index', ['kategori_id' => $kategori->id]) }}">
                        {{ $kategori->nama_kategori }}
                    </a>
                </li>
            @endforeach
        </ul>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Daftar Paket</h5>
                <a href="{{ route('admin.paket-langganan.create') }}"
                   class="btn btn-sm"
                   style="background:#ef7d2d;color:white;border-radius:8px;font-weight:600;">
                    <i class="bi bi-plus-lg me-1"></i>Tambah Paket
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-3">Nama Paket</th>
                                <th>Tipe</th>
                                <th>Jumlah Sesi</th>
                                <th>Durasi/Sesi</th>
                                <th>Masa Berlaku</th>
                                <th>Harga</th>
                                <th>Status</th>
                                <th class="pe-3" width="130">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($paketList as $paket)
                                <tr>
                                    <td class="fw-semibold ps-3">{{ $paket->nama_paket }}</td>
                                    <td>
                                        <span class="{{ $paket->tipe_paket === 'Kuota' ? 'badge-kuota' : 'badge-jadwal' }}">
                                            {{ $paket->tipe_paket }}
                                        </span>
                                    </td>
                                    <td>{{ $paket->jumlah_sesi }}x</td>
                                    <td>{{ $paket->durasi_jam_per_sesi }} jam</td>
                                    <td>{{ $paket->masa_berlaku_hari }} hari</td>
                                    <td class="fw-semibold" style="color:#ef7d2d;">
                                        Rp{{ number_format($paket->harga, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        <span class="badge {{ $paket->status_aktif === 'Aktif' ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $paket->status_aktif }}
                                        </span>
                                    </td>
                                    <td class="pe-3">
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('admin.paket-langganan.edit', $paket) }}"
                                               class="btn btn-sm"
                                               style="border:1.5px solid #4b73b8;color:#4b73b8;border-radius:8px;background:transparent;">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.paket-langganan.destroy', $paket) }}" method="POST"
                                                  class="d-inline" onsubmit="return confirm('Yakin hapus paket ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn btn-sm"
                                                        style="border:1.5px solid #dc3545;color:#dc3545;border-radius:8px;background:transparent;">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox d-block mb-2 fs-4"></i>
                                        Belum ada paket untuk kategori ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection