@extends('layouts.app')
@section('title','Lapangan')
@section('sblapang','active')

@section('content')
    <div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Data Lapangan</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Data Lapangan</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Lapangan</h5>
                <a href="{{ route('admin.lapangan.create') }}" style="background-color: #ef7d2d; border-radius: 10px;" class="btn btn-primary btn-sm border-0">
                    <i class="bi bi-plus-lg"></i> Tambah Lapangan
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Nama Lapangan</th>
                                <th>Kategori</th>
                                <th>Deskripsi</th>
                                <th>Tarif/Jam</th>
                                <th>Status</th>
                                <th width="140">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($lapanganList as $lapangan)
                                <tr>
                                    <td>{{ $lapangan->nama_lapang }}</td>
                                    <td>{{ $lapangan->kategoriOlahraga->nama_kategori }}</td>
                                    <td>{{ $lapangan->deskripsi }}</td>
                                    <td>Rp{{ number_format($lapangan->tarif_per_jam, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="badge {{ $lapangan->status_aktif === 'Aktif' ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $lapangan->status_aktif }}
                                        </span>
                                    </td>
                                    <td> 
                                        <a href="{{ route('admin.lapangan.edit', $lapangan) }}" style="border-radius: 10px;" class="btn btn-sm btn-outline-primary">Edit</a>
                                        <form action="{{ route('admin.lapangan.destroy', $lapangan) }}" style="border-radius: 10px;" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus lapangan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Belum ada lapangan.</td>
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