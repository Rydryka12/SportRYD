@extends('layouts.kasir')

@section('content')
<div class="page-heading">
    <h3 class="mb-4">Booking & Konfirmasi Pembayaran</h3>
    <section class="section">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Menunggu Konfirmasi Pembayaran</h5>
                <a href="{{ route('kasir.booking.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-lg"></i> Booking Manual
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Pelanggan</th>
                                <th>Lapangan</th>
                                <th>Tanggal & Jam</th>
                                <th>Metode</th>
                                <th>Jumlah</th>
                                <th width="140">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pembayaranPending as $pembayaran)
                                <tr>
                                    <td>{{ $pembayaran->booking->customer->name }}</td>
                                    <td>{{ $pembayaran->booking->lapangan->nama_lapang }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($pembayaran->booking->tanggal)->translatedFormat('d M Y') }},
                                        {{ \Carbon\Carbon::parse($pembayaran->booking->jam_mulai)->format('H:i') }}-{{ \Carbon\Carbon::parse($pembayaran->booking->jam_selesai)->format('H:i') }}
                                    </td>
                                    <td>{{ $pembayaran->metode }}</td>
                                    <td>Rp{{ number_format($pembayaran->jumlah, 0, ',', '.') }}</td>
                                    <td>
                                        <form action="{{ route('kasir.pembayaran.konfirmasi', $pembayaran) }}" method="POST" onsubmit="return confirm('Konfirmasi pembayaran ini?')">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">Konfirmasi</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Tidak ada pembayaran yang menunggu konfirmasi.</td>
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