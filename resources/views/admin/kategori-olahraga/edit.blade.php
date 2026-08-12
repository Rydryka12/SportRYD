@extends('layouts.app')
@section('title', 'Edit Kategori')
@section('sbdatalapang', 'active')
@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <!-- Ubah Judul -->
                <h3>Edit Kategori Olahraga</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.kategori-olahraga.index') }}">Kategori Olahraga</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Kategori</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Form Edit Kategori</h5>
            </div>
            <div class="card-body">
                <!-- 1. Ubah route menjadi update dan passing ID/Object kategori -->
                <form action="{{ route('admin.kategori-olahraga.update', $kategori->id) }}" method="POST">
                    @csrf
                    <!-- 2. Tambahkan method PUT untuk update data -->
                    @method('PUT')
                    
                    <div class="row">
                        <!-- Kolom Nama Kategori -->
                        <div class="col-md-6 col-12">
                            <div class="form-group mb-3">
                                <label for="nama_kategori" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                                <!-- 3. Tambahkan $kategori->nama_kategori sebagai parameter kedua di fungsi old() -->
                                <input type="text" 
                                       id="nama_kategori" 
                                       name="nama_kategori" 
                                       class="form-control @error('nama_kategori') is-invalid @enderror" 
                                       value="{{ old('nama_kategori', $kategori->nama_kategori) }}" 
                                       placeholder="Masukkan nama kategori (contoh: Futsal, Basket, dll)" 
                                       required>
                                
                                @error('nama_kategori')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Kolom Deskripsi -->
                        <div class="col-md-6 col-12">
                            <div class="form-group mb-3">
                                <label for="deskripsi" class="form-label">Deskripsi (Opsional)</label>
                                <!-- 3. Tambahkan $kategori->deskripsi sebagai parameter kedua di fungsi old() -->
                                <textarea id="deskripsi" 
                                          name="deskripsi" 
                                          class="form-control @error('deskripsi') is-invalid @enderror" 
                                          rows="3" 
                                          placeholder="Masukkan deskripsi singkat kategori olahraga">{{ old('deskripsi', $kategori->deskripsi) }}</textarea>
                                
                                @error('deskripsi')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12 d-flex justify-content-start">
                            <button type="submit" class="btn btn-primary me-2" style="background-color: #ef7d2d; border-color: #ef7d2d;">
                                <i class="bi bi-save"></i> Update
                            </button>
                            <a href="{{ route('admin.kategori-olahraga.index') }}" class="btn btn-light-secondary">
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