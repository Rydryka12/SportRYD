{{--
    Partial: tabel konfirmasi DP
    Variabel yang dibutuhkan: $pembayaranPending (collection Pembayaran with booking.customer, booking.lapangan)
--}}
<div class="table-responsive">
    <table class="table mb-0">
        <thead>
            <tr>
                <th class="ps-3">Pelanggan</th>
                <th>Lapangan</th>
                <th>Tanggal & Jam Main</th>
                <th>Metode</th>
                <th>Jumlah DP</th>
                <th>Batas Konfirmasi</th>
                <th width="210" class="pe-3">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pembayaranPending as $pembayaran)
                @php
                    $jamMulai   = \Carbon\Carbon::parse(
                        $pembayaran->booking->tanggal . ' ' . $pembayaran->booking->jam_mulai
                    );
                    $sisaKonfirmasi = now()->diffInMinutes($jamMulai, false); // negatif = sudah lewat
                    $urgent     = $sisaKonfirmasi >= 0 && $sisaKonfirmasi <= 30;
                    $kritis     = $sisaKonfirmasi >= 0 && $sisaKonfirmasi <= 10;
                @endphp
                <tr style="{{ $kritis ? 'background:rgba(220,38,38,0.06);' : ($urgent ? 'background:rgba(234,179,8,0.06);' : '') }}">
                    <td class="ps-3">
                        <div class="cell-name">{{ $pembayaran->booking->customer->name }}</div>
                        <div class="cell-sub">{{ $pembayaran->booking->customer->no_hp ?? '-' }}</div>
                    </td>
                    <td class="cell-bold">{{ $pembayaran->booking->lapangan->nama_lapang }}</td>
                    <td>
                        <div class="cell-bold">{{ \Carbon\Carbon::parse($pembayaran->booking->tanggal)->translatedFormat('d M Y') }}</div>
                        <div class="cell-sub">
                            {{ \Carbon\Carbon::parse($pembayaran->booking->jam_mulai)->format('H:i') }}
                            –
                            {{ \Carbon\Carbon::parse($pembayaran->booking->jam_selesai)->format('H:i') }}
                        </div>
                    </td>
                    <td>
                        <span style="background:var(--k-thead-bg);color:var(--k-text);border:1px solid var(--k-border);border-radius:6px;padding:0.2rem 0.65rem;font-size:0.75rem;font-weight:600;">
                            <i class="bi bi-cash me-1"></i>Cash
                        </span>
                    </td>
                    <td class="cell-bold">Rp{{ number_format($pembayaran->jumlah, 0, ',', '.') }}</td>
                    <td>
                        @if ($sisaKonfirmasi < 0)
                            {{-- Jam main sudah lewat tapi scheduler belum jalan --}}
                            <span style="background:#fdecea;color:#dc2626;border-radius:50px;padding:0.2rem 0.65rem;font-size:0.75rem;font-weight:700;">
                                <i class="bi bi-exclamation-circle me-1"></i>Melewati jam main
                            </span>
                        @elseif ($kritis)
                            <span style="background:#fdecea;color:#dc2626;border-radius:50px;padding:0.2rem 0.65rem;font-size:0.75rem;font-weight:700;">
                                <i class="bi bi-alarm me-1"></i>&lt; {{ $sisaKonfirmasi }} menit lagi!
                            </span>
                        @elseif ($urgent)
                            <span style="background:#fef3e2;color:#b45309;border-radius:50px;padding:0.2rem 0.65rem;font-size:0.75rem;font-weight:700;">
                                <i class="bi bi-clock me-1"></i>{{ $sisaKonfirmasi }} menit lagi
                            </span>
                        @else
                            <span class="cell-sub">
                                Sebelum {{ \Carbon\Carbon::parse($pembayaran->booking->jam_mulai)->format('H:i') }}
                                ({{ $sisaKonfirmasi }} menit)
                            </span>
                        @endif
                    </td>
                    <td class="pe-3">
                        <div class="d-flex gap-2">
                            <form action="{{ route('kasir.pembayaran.konfirmasi', $pembayaran) }}" method="POST"
                                  onsubmit="return confirm('Konfirmasi DP untuk {{ $pembayaran->booking->customer->name }}? Waktu main akan mulai berjalan otomatis.')">
                                @csrf
                                <button class="btn btn-sm btn-success fw-bold">
                                    <i class="bi bi-check-lg me-1"></i>Konfirmasi DP
                                </button>
                            </form>
                            <form action="{{ route('kasir.pembayaran.tolak', $pembayaran) }}" method="POST"
                                  onsubmit="return confirm('Tolak DP dan batalkan booking {{ $pembayaran->booking->customer->name }}?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger fw-bold">
                                    <i class="bi bi-x-lg me-1"></i>Tolak
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center empty-cell">
                        <i class="bi bi-inbox d-block mb-1" style="font-size:1.4rem;"></i>
                        Tidak ada pembayaran DP yang menunggu konfirmasi
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
