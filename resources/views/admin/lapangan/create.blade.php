@extends('layouts.app')
@section('title', 'Buat Lapangan')
@section('sblapang', 'active')
@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Tambah Lapangan Olahraga</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.lapangan.index') }}">Data Lapangan</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Tambah Lapangan</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Form Tambah Lapangan</h5>
                </div>
                
                <div class="card-body">
                    <form action="{{ route('admin.lapangan.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <!-- 1. Nama Lapangan -->
                            <div class="col-md-6 col-12">
                                <div class="form-group mb-3">
                                    <label for="nama_lapang" class="form-label">Nama Lapangan <span class="text-danger">*</span></label>
                                    <input type="text"
                                           id="nama_lapang"
                                           name="nama_lapang"
                                           class="form-control @error('nama_lapang') is-invalid @enderror"
                                           value="{{ old('nama_lapang') }}"
                                           placeholder="Masukkan Nama Lapangan"
                                           required>
                                    
                                    @error('nama_lapang')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <!-- 2. Kategori Olahraga -->
                            <div class="col-md-6 col-12">
                                <div class="form-group mb-3">
                                    <label for="kategori_id" class="form-label">Kategori <span class="text-danger">*</span></label>
                                    <select class="form-select @error('kategori_id') is-invalid @enderror" id="kategori_id" name="kategori_id" required>
                                        <option value="" selected disabled>-- Pilih Kategori --</option>
                                        <!-- Looping data kategori dari Controller -->
                                        @foreach($kategoriList as $kategori)
                                            <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                                {{ $kategori->nama_kategori }}
                                            </option>
                                        @endforeach
                                    </select>
                                    
                                    @error('kategori_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <!-- 3. Deskripsi -->
                            <div class="col-md-12 col-12">
                                <div class="form-group mb-3">
                                    <label for="deskripsi" class="form-label">Deskripsi</label>
                                    <textarea name="deskripsi"
                                              id="deskripsi"
                                              class="form-control @error('deskripsi') is-invalid @enderror"
                                              rows="3"
                                              placeholder="Masukkan Deskripsi Lapangan">{{ old('deskripsi') }}</textarea>

                                    @error('deskripsi')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <!-- 4. Tarif / Jam -->
                            <div class="col-md-6 col-12">
                                <div class="form-group mb-3">
                                    <label for="tarif_per_jam" class="form-label">Tarif/Jam (Rp) <span class="text-danger">*</span></label>
                                    <input type="number"
                                           id="tarif_per_jam"
                                           name="tarif_per_jam"
                                           class="form-control @error('tarif_per_jam') is-invalid @enderror"
                                           value="{{ old('tarif_per_jam') }}"
                                           placeholder="Contoh: 50000"
                                           min="0"
                                           required>
                                    
                                    @error('tarif_per_jam')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <!-- 5. Status -->
                            <div class="col-md-6 col-12">
                                <div class="form-group mb-3">
                                    <label for="status_aktif" class="form-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-select @error('status_aktif') is-invalid @enderror" id="status_aktif" name="status_aktif" required>
                                        <option value="Aktif" {{ old('status_aktif') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                        <option value="Nonaktif" {{ old('status_aktif') == 'Nonaktif' ? 'selected' : '' }}>Non Aktif</option>
                                    </select>
                                    
                                    @error('status_aktif')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Submit & Batal -->
                        <div class="row mt-3">
                            <div class="col-12 d-flex justify-content-start">
                                <button type="submit" class="btn btn-primary me-2" style="background-color: #ef7d2d; border-color: #ef7d2d;">
                                    <i class="bi bi-save"></i> Simpan Lapangan
                                </button>
                                <a href="{{ route('admin.lapangan.index') }}" class="btn btn-light-secondary">
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