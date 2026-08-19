@extends('layouts.kasir')

@section('content')
<style>
    html[data-bs-theme="dark"] .btn-outline-primary {
        color: #6ea8fe !important;
        border-color: #6ea8fe !important;
    }
    html[data-bs-theme="dark"] .btn-outline-primary:hover {
        background-color: #6ea8fe !important;
        color: #12244a !important;
    }
</style>

<div class="page-heading">
    <h3 class="mb-4 fw-bold">Reschedule Booking</h3>

    <section class="section">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <div class="card-header">
                <form method="GET" action="{{ route('kasir.reschedule.index') }}"
                      class="d-flex align-items-center gap-2">
                    <label class="form-label mb-0">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ $tanggal }}"
                           class="form-control" style="width:200px;"
                           onchange="this.form.submit()">
                </form>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Jam</th>
                                <th>Pelanggan</th>
                                <th>Lapangan</th>
                                <th width="140">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($bookingList as $booking)
                                <tr>
                                    <td>
                                        {{ \Carbon\Carbon::parse($booking->jam_mulai)->format('H:i') }}–{{ \Carbon\Carbon::parse($booking->jam_selesai)->format('H:i') }}
                                    </td>
                                    <td>{{ $booking->customer->name }}</td>
                                    <td>{{ $booking->lapangan->nama_lapang }}</td>
                                    <td>
                                        <a href="{{ route('kasir.reschedule.create', $booking) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-calendar2-week me-1"></i>Reschedule
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        Tidak ada booking di tanggal ini.
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
