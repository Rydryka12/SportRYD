@extends('layouts.app')
@section('title', 'Tambah Paket Langganan')
@section('sbpaket', 'active')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Tambah Paket Langganan</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.paket-langganan.index') }}">Paket Langganan</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Tambah Paket</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Form Tambah Paket</h5>
            </div>
            
            <div class="card-body">
                <form action="{{ route('admin.paket-langganan.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <!-- 1. Kategori Olahraga -->
                        <div class="col-md-6 col-12">
                            <div class="form-group mb-3">
                                <label for="kategori_id" class="form-label">Kategori <span class="text-danger">*</span></label>
                                <select class="form-select @error('kategori_id') is-invalid @enderror" id="kategori_id" name="kategori_id" required>
                                    <option value="" selected disabled>-- Pilih Kategori --</option>
                                    @foreach($kategoriList as $kategori)
                                        <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                            {{ $kategori->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kategori_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- 2. Nama Paket -->
                        <div class="col-md-6 col-12">
                            <div class="form-group mb-3">
                                <label for="nama_paket" class="form-label">Nama Paket <span class="text-danger">*</span></label>
                                <input type="text" id="nama_paket" name="nama_paket" class="form-control @error('nama_paket') is-invalid @enderror" value="{{ old('nama_paket') }}" placeholder="Contoh: Member Futsal Bulanan" required>
                                @error('nama_paket')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- 3. Tipe Paket -->
                        <div class="col-md-6 col-12">
                            <div class="form-group mb-3">
                                <label for="tipe_paket" class="form-label">Tipe Paket <span class="text-danger">*</span></label>
                                <select class="form-select @error('tipe_paket') is-invalid @enderror" id="tipe_paket" name="tipe_paket" required>
                                    <option value="" selected disabled>-- Pilih Tipe --</option>
                                    <option value="Kuota" {{ old('tipe_paket') == 'Kuota' ? 'selected' : '' }}>Kuota</option>
                                    <option value="Jadwal Tetap" {{ old('tipe_paket') == 'Jadwal Tetap' ? 'selected' : '' }}>Jadwal Tetap</option>
                                </select>
                                @error('tipe_paket')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- 4. Jumlah Sesi -->
                        <div class="col-md-6 col-12">
                            <div class="form-group mb-3">
                                <label for="jumlah_sesi" class="form-label">Jumlah Sesi <span class="text-danger">*</span></label>
                                <input type="number" id="jumlah_sesi" name="jumlah_sesi" class="form-control @error('jumlah_sesi') is-invalid @enderror" value="{{ old('jumlah_sesi') }}" placeholder="Contoh: 4" min="1" required>
                                @error('jumlah_sesi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- 5. Durasi / Sesi (Diubah menjadi integer min 1 sesuai controller) -->
                        <div class="col-md-6 col-12">
                            <div class="form-group mb-3">
                                <label for="durasi_jam_per_sesi" class="form-label">Durasi per Sesi (Jam) <span class="text-danger">*</span></label>
                                <input type="number" id="durasi_jam_per_sesi" name="durasi_jam_per_sesi" class="form-control @error('durasi_jam_per_sesi') is-invalid @enderror" value="{{ old('durasi_jam_per_sesi') }}" placeholder="Contoh: 2" min="1" required>
                                @error('durasi_jam_per_sesi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- 6. Masa Berlaku -->
                        <div class="col-md-6 col-12">
                            <div class="form-group mb-3">
                                <label for="masa_berlaku_hari" class="form-label">Masa Berlaku (Hari) <span class="text-danger">*</span></label>
                                <input type="number" id="masa_berlaku_hari" name="masa_berlaku_hari" class="form-control @error('masa_berlaku_hari') is-invalid @enderror" value="{{ old('masa_berlaku_hari', 30) }}" placeholder="Contoh: 30" min="1" required>
                                @error('masa_berlaku_hari')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- 7. Harga -->
                        <div class="col-md-6 col-12">
                            <div class="form-group mb-3">
                                <label for="harga" class="form-label">Harga Paket (Rp) <span class="text-danger">*</span></label>
                                <input type="number" id="harga" name="harga" class="form-control @error('harga') is-invalid @enderror" value="{{ old('harga') }}" placeholder="Contoh: 250000" min="0" required>
                                @error('harga')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- 8. Status -->
                        <div class="col-md-6 col-12">
                            <div class="form-group mb-3">
                                <label for="status_aktif" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status_aktif') is-invalid @enderror" id="status_aktif" name="status_aktif" required>
                                    <option value="Aktif" {{ old('status_aktif') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="Nonaktif" {{ old('status_aktif') == 'Nonaktif' ? 'selected' : '' }}>Non Aktif</option>
                                </select>
                                @error('status_aktif')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Submit & Batal -->
                    <div class="row mt-3">
                        <div class="col-12 d-flex justify-content-start">
                            <button type="submit" class="btn btn-primary me-2" style="background-color: #ef7d2d; border-color: #ef7d2d;">
                                <i class="bi bi-save"></i> Simpan Paket
                            </button>
                            <a href="{{ route('admin.paket-langganan.index') }}" class="btn btn-light-secondary">
                                Batal
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection