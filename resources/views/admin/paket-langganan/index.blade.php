
@extends('layouts.app')

@section('title', 'Paket Langganan')
@section('sbpaket', 'active') <!-- Sesuaikan dengan ID menu sidebar paket kamu -->

@section('content')
<style>
    /* Badge styling bawaan kamu */
    .badge-tipe-kuota { background-color: #dbeafe; color: #1e40af; }
    .badge-tipe-jadwal-tetap { background-color: #ede9fe; color: #5b21b6; }
    
    /* Custom warna tab agar senada dengan warna orange tema */
    .nav-pills .nav-link.active {
        background-color: #ef7d2d !important;
        color: white !important;
    }
    .nav-pills .nav-link {
        color: #6c757d; /* Warna teks tab saat tidak aktif */
        font-weight: 500;
    }
    .nav-pills .nav-link:hover {
        color: #ef7d2d;
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
                        <li class="breadcrumb-item active" aria-current="page">Paket Langganan</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible show fade">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible show fade">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Filter Tab Kategori -->
        <ul class="nav nav-pills mb-4">
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
                <h5 class="mb-0">Daftar Paket</h5>
                <a style="background-color: #ef7d2d; border-color: #ef7d2d; color: white;" href="{{ route('admin.paket-langganan.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-lg"></i> Tambah Paket
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Nama Paket</th>
                                <th>Tipe</th>
                                <th>Jumlah Sesi</th>
                                <th>Durasi/Sesi</th>
                                <th>Masa Berlaku</th>
                                <th>Harga</th>
                                <th>Status</th>
                                <th width="140">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($paketList as $paket)
                                <tr>
                                    <td class="fw-bold">{{ $paket->nama_paket }}</td>
                                    <td>
                                        <span class=" badge {{ $paket->tipe_paket === 'Kuota' ? 'badge-tipe-kuota' : 'badge-tipe-jadwal-tetap' }}">
                                            {{ $paket->tipe_paket }}
                                        </span>
                                    </td>
                                    <td>{{ $paket->jumlah_sesi }}x</td>
                                    <td>{{ $paket->durasi_jam_per_sesi }} jam</td>
                                    <td>{{ $paket->masa_berlaku_hari }} hari</td>
                                    <td>Rp{{ number_format($paket->harga, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="badge {{ $paket->status_aktif === 'Aktif' ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $paket->status_aktif }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.paket-langganan.edit', $paket) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                        <form action="{{ route('admin.paket-langganan.destroy', $paket) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus paket ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">Belum ada paket untuk kategori ini.</td>
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