@extends('layouts.app')

@section('title', 'Voucher')
@section('sbvoucher', 'active')
@section('content')
<div class="page-heading">
    <h3 class="mb-4">Poin & Katalog Voucher</h3>

    <section class="section">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0">Rasio Poin</h5></div>
            <div class="card-body">
                <form action="{{ route('admin.poin-voucher.rasio.update') }}" method="POST" class="d-flex align-items-end gap-3">
                    @csrf
                    <div>
                        <label class="form-label">Poin per Sesi Selesai</label>
                        <input type="number" name="poin_per_sesi" value="{{ $rasioPoin }}" min="0" class="form-control" style="width: 150px;">
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Katalog Voucher</h5>
                <a href="{{ route('admin.poin-voucher.voucher.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-lg"></i> Tambah Voucher
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Nama Voucher</th>
                                <th>Kategori</th>
                                <th>Biaya Poin</th>
                                <th>Nilai Potongan</th>
                                <th>Masa Berlaku</th>
                                <th>Status</th>
                                <th width="140">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($voucherList as $voucher)
                                <tr>
                                    <td>{{ $voucher->nama_voucher }}</td>
                                    <td>{{ $voucher->kategoriOlahraga->nama_kategori }}</td>
                                    <td>{{ $voucher->biaya_poin }} poin</td>
                                    <td>Rp{{ number_format($voucher->nilai_potongan, 0, ',', '.') }}</td>
                                    <td>{{ $voucher->masa_berlaku_hari }} hari</td>
                                    <td>
                                        <span class="badge {{ $voucher->status_aktif === 'Aktif' ? 'bg-success' : 'bg-secondary' }}">{{ $voucher->status_aktif }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.poin-voucher.voucher.edit', $voucher) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                        <form action="{{ route('admin.poin-voucher.voucher.destroy', $voucher) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus voucher ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center text-muted">Belum ada voucher.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Katalog Tukar Kuota</h5>
                <a href="{{ route('admin.poin-voucher.tukar-kuota.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-lg"></i> Tambah Rasio
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Kategori</th>
                                <th>Biaya Poin / Sesi</th>
                                <th>Sesi Didapat</th>
                                <th>Status</th>
                                <th width="140">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tukarKuotaList as $tk)
                                <tr>
                                    <td>{{ $tk->kategoriOlahraga->nama_kategori }}</td>
                                    <td>{{ $tk->biaya_poin }} poin</td>
                                    <td>{{ $tk->jumlah_sesi_didapat }} sesi</td>
                                    <td>
                                        <span class="badge {{ $tk->status_aktif === 'Aktif' ? 'bg-success' : 'bg-secondary' }}">{{ $tk->status_aktif }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.poin-voucher.tukar-kuota.edit', $tk) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                        <form action="{{ route('admin.poin-voucher.tukar-kuota.destroy', $tk) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus rasio ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted">Belum ada rasio tukar kuota.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection