@extends('layouts.app')

@section('sbpelanggan', 'active')
@section('title', 'Data Pelanggan')
@section('content')
<div class="page-heading">
    <h3 class="mb-4">Data Pelanggan</h3>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <form method="GET" action="{{ route('admin.pelanggan.index') }}" class="d-flex gap-2">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama atau no. HP..." class="form-control" style="max-width: 300px;">
                    <button type="submit" class="btn btn-primary btn-sm">Cari</button>
                    @if ($search)
                        <a href="{{ route('admin.pelanggan.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
                    @endif
                </form>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>No. HP</th>
                                <th>Saldo Poin</th>
                                <th>Status Paket Langganan</th>
                                <th width="140">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pelangganList as $pelanggan)
                                <tr>
                                    <td>{{ $pelanggan->name }}</td>
                                    <td>{{ $pelanggan->no_hp ?? '-' }}</td>
                                    <td>{{ $pelanggan->saldo_poin }}</td>
                                    <td>
                                        @if ($pelanggan->paket_aktif_count > 0)
                                            <span class="badge bg-success">{{ $pelanggan->paket_aktif_count }} Paket Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Tidak Ada</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.pelanggan.show', $pelanggan) }}" class="btn btn-sm btn-outline-primary">Lihat Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted">Belum ada pelanggan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection