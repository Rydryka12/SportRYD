@extends('layouts.app')

@section('sbreschedule', 'active')
@sestion('title', 'Reschedule')

@section('content')
<div class="page-heading">
    <h3 class="mb-4">Approval Reschedule</h3>

    <section class="section">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @forelse ($requests as $req)
            @php $jadwalBaru = $req->jadwal_baru_array; @endphp
            <div class="card mb-3 border-start border-warning border-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <p class="fw-bold mb-1">{{ $req->booking->customer->name }}</p>
                            <p class="text-muted small mb-2">{{ $req->booking->lapangan->nama_lapang }}</p>

                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="badge bg-light text-dark border">
                                    {{ \Carbon\Carbon::parse($req->booking->tanggal)->translatedFormat('d M Y') }},
                                    {{ \Carbon\Carbon::parse($req->booking->jam_mulai)->format('H:i') }}-{{ \Carbon\Carbon::parse($req->booking->jam_selesai)->format('H:i') }}
                                </span>
                                <i class="bi bi-arrow-right"></i>
                                <span class="badge bg-warning text-dark">
                                    {{ \Carbon\Carbon::parse($jadwalBaru['tanggal'])->translatedFormat('d M Y') }},
                                    {{ $jadwalBaru['jam_mulai'] }}-{{ $jadwalBaru['jam_selesai'] }}
                                </span>
                            </div>

                            @if (!empty($jadwalBaru['alasan']))
                                <p class="text-muted small mb-0">Alasan: {{ $jadwalBaru['alasan'] }}</p>
                            @endif
                        </div>
                        <div class="col-md-4 text-md-end mt-3 mt-md-0">
                            <form action="{{ route('admin.reschedule.approve', $req) }}" method="POST" class="d-inline" onsubmit="return confirm('Setujui reschedule ini?')">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">Setujui</button>
                            </form>
                            <form action="{{ route('admin.reschedule.reject', $req) }}" method="POST" class="d-inline" onsubmit="return confirm('Tolak reschedule ini?')">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger btn-sm">Tolak</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="card">
                <div class="card-body text-center text-muted py-5">
                    Tidak ada pengajuan reschedule yang menunggu.
                </div>
            </div>
        @endforelse
    </section>
</div>
@endsection