@extends('layouts.app')

@section('sbpelanggan','active')
@section('title', 'Data Pelanggan')

@section('content')
<div class="page-heading">
    <a href="{{ route('admin.pelanggan.index') }}" class="text-muted small mb-3 d-inline-block text-decoration-none">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>

    <div class="card mb-4">
        <div class="card-body">
            <h4 class="fw-bold mb-1">{{ $pelanggan->name }}</h4>
            <p class="text-muted mb-2">{{ $pelanggan->no_hp ?? '-' }} &middot; {{ $pelanggan->email }}</p>
            <span class="badge bg-primary">Saldo Poin: {{ $saldoPoin }}</span>
        </div>
    </div>

    <ul class="nav nav-tabs mb-3" role="tablist">
        <li class="nav-item">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-booking" type="button">Riwayat Booking</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-poin" type="button">Histori Poin</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-voucher-paket" type="button">Voucher & Paket Langganan</button>
        </li>
    </ul>

    <div class="tab-content">
        {{-- TAB RIWAYAT BOOKING --}}
        <div class="tab-pane fade show active" id="tab-booking">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr><th>Lapangan</th><th>Tanggal</th><th>Jam</th><th>Status</th><th>Sumber</th></tr>
                            </thead>
                            <tbody>
                                @forelse ($bookingList as $b)
                                    <tr>
                                        <td>{{ $b->lapangan->nama_lapang }}</td>
                                        <td>{{ \Carbon\Carbon::parse($b->tanggal)->translatedFormat('d M Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($b->jam_mulai)->format('H:i') }}-{{ \Carbon\Carbon::parse($b->jam_selesai)->format('H:i') }}</td>
                                        <td>
                                            <span class="badge {{ match($b->status) {
                                                'Akan Datang' => 'bg-primary',
                                                'Selesai' => 'bg-secondary',
                                                'Menunggu Approval Reschedule' => 'bg-warning text-dark',
                                                'Dibatalkan' => 'bg-danger',
                                                default => 'bg-light text-dark',
                                            } }}">{{ $b->status }}</span>
                                        </td>
                                        <td>{{ $b->sumber }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center text-muted py-4">Belum ada riwayat booking.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- TAB HISTORI POIN --}}
        <div class="tab-pane fade" id="tab-poin">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr><th>Tanggal</th><th>Jenis</th><th>Jumlah</th><th>Keterangan</th></tr>
                            </thead>
                            <tbody>
                                @forelse ($poinHistori as $p)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($p->tanggal)->translatedFormat('d M Y') }}</td>
                                        <td>
                                            <span class="badge {{ $p->jenis === 'Masuk' ? 'bg-success' : 'bg-danger' }}">{{ $p->jenis }}</span>
                                        </td>
                                        <td>{{ $p->jenis === 'Masuk' ? '+' : '-' }}{{ $p->jumlah_poin }}</td>
                                        <td>{{ $p->keterangan }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center text-muted py-4">Belum ada histori poin.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- TAB VOUCHER & PAKET LANGGANAN --}}
        <div class="tab-pane fade" id="tab-voucher-paket">
            <p class="fw-semibold mb-2">Voucher</p>
            <div class="card mb-4">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr><th>Nama Voucher</th><th>Kode</th><th>Tukar</th><th>Kedaluwarsa</th><th>Status</th></tr>
                            </thead>
                            <tbody>
                                @forelse ($voucherList as $v)
                                    <tr>
                                        <td>{{ $v->katalogVoucher->nama_voucher }}</td>
                                        <td>{{ $v->kode_voucher }}</td>
                                        <td>{{ \Carbon\Carbon::parse($v->tanggal_tukar)->translatedFormat('d M Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($v->tanggal_expired)->translatedFormat('d M Y') }}</td>
                                        <td>
                                            <span class="badge {{ match($v->status) {
                                                'Aktif' => 'bg-success',
                                                'Terpakai' => 'bg-secondary',
                                                'Kedaluwarsa' => 'bg-danger',
                                                default => 'bg-light text-dark',
                                            } }}">{{ $v->status }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center text-muted py-4">Belum ada voucher.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <p class="fw-semibold mb-2">Paket Langganan</p>
            <div class="row g-3">
                @forelse ($langgananList as $l)
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="fw-bold mb-0">{{ $l->paketLangganan->kategoriOlahraga->nama_kategori }}</h6>
                                    <span class="badge {{ $l->paketLangganan->tipe_paket === 'Kuota' ? 'bg-primary' : 'bg-purple' }}" style="{{ $l->paketLangganan->tipe_paket !== 'Kuota' ? 'background-color:#6f42c1;' : '' }}">
                                        {{ $l->paketLangganan->tipe_paket }}
                                    </span>
                                </div>
                                <p class="text-muted small mb-1">{{ $l->paketLangganan->nama_paket }}</p>
                                @if ($l->paketLangganan->tipe_paket === 'Kuota')
                                    <p class="small mb-1">Sisa {{ $l->sisa_sesi }} sesi</p>
                                @else
                                    <p class="small mb-1">
                                        {{ $l->lapangan->nama_lapang ?? '-' }} &middot; {{ $l->hari_dalam_minggu }}
                                        {{ $l->jam_mulai ? \Carbon\Carbon::parse($l->jam_mulai)->format('H:i') : '' }}-{{ $l->jam_selesai ? \Carbon\Carbon::parse($l->jam_selesai)->format('H:i') : '' }}
                                    </p>
                                @endif
                                <p class="small text-muted mb-2">
                                    Berlaku {{ \Carbon\Carbon::parse($l->tanggal_mulai)->translatedFormat('d M Y') }} - {{ \Carbon\Carbon::parse($l->tanggal_berakhir)->translatedFormat('d M Y') }}
                                </p>
                                <span class="badge {{ $l->status === 'Aktif' ? 'bg-success' : 'bg-secondary' }}">{{ $l->status }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted">Belum pernah ambil Paket Langganan.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection