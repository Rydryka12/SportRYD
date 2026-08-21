@extends('layouts.app')
@section('title', 'Edit Lapangan')
@section('sblapang', 'active')
@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Edit Lapangan Olahraga</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.lapangan.index') }}">Data Lapangan</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Lapangan</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Form Edit Lapangan</h5>
                </div>
                
                <div class="card-body">
                    <form action="{{ route('admin.lapangan.update', $lapangan->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <!-- 1. Nama Lapangan -->
                            <div class="col-md-6 col-12">
                                <div class="form-group mb-3">
                                    <label for="nama_lapang" class="form-label">Nama Lapangan <span class="text-danger">*</span></label>
                                    <input type="text" id="nama_lapang" name="nama_lapang"
                                           class="form-control @error('nama_lapang') is-invalid @enderror"
                                           value="{{ old('nama_lapang', $lapangan->nama_lapang) }}"
                                           placeholder="Masukkan Nama Lapangan" required>
                                    @error('nama_lapang')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <!-- 2. Kategori Olahraga -->
                            <div class="col-md-6 col-12">
                                <div class="form-group mb-3">
                                    <label for="kategori_id" class="form-label">Kategori <span class="text-danger">*</span></label>
                                    <select class="form-select @error('kategori_id') is-invalid @enderror" id="kategori_id" name="kategori_id" required>
                                        <option value="" disabled>-- Pilih Kategori --</option>
                                        @foreach($kategoriList as $kategori)
                                            <option value="{{ $kategori->id }}" {{ old('kategori_id', $lapangan->kategori_id) == $kategori->id ? 'selected' : '' }}>
                                                {{ $kategori->nama_kategori }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('kategori_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <!-- 3. Deskripsi -->
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label for="deskripsi" class="form-label">Deskripsi</label>
                                    <textarea name="deskripsi" id="deskripsi"
                                              class="form-control @error('deskripsi') is-invalid @enderror"
                                              rows="3" placeholder="Masukkan Deskripsi Lapangan">{{ old('deskripsi', $lapangan->deskripsi) }}</textarea>
                                    @error('deskripsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <!-- 4. Tarif / Jam -->
                            <div class="col-md-6 col-12">
                                <div class="form-group mb-3">
                                    <label for="tarif_per_jam" class="form-label">Tarif/Jam (Rp) <span class="text-danger">*</span></label>
                                    <input type="number" id="tarif_per_jam" name="tarif_per_jam"
                                           class="form-control @error('tarif_per_jam') is-invalid @enderror"
                                           value="{{ old('tarif_per_jam', $lapangan->tarif_per_jam) }}"
                                           placeholder="Contoh: 60000" min="0" required>
                                    @error('tarif_per_jam')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <!-- 5. Status -->
                            <div class="col-md-6 col-12">
                                <div class="form-group mb-3">
                                    <label for="status_aktif" class="form-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-select @error('status_aktif') is-invalid @enderror" id="status_aktif" name="status_aktif" required>
                                        <option value="Aktif"     {{ old('status_aktif', $lapangan->status_aktif) == 'Aktif'     ? 'selected' : '' }}>Aktif</option>
                                        <option value="Nonaktif" {{ old('status_aktif', $lapangan->status_aktif) == 'Nonaktif' ? 'selected' : '' }}>Non Aktif</option>
                                    </select>
                                    @error('status_aktif')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <!-- 6. Foto Lapangan -->
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label for="foto" class="form-label">Foto Lapangan</label>

                                    {{-- Foto saat ini --}}
                                    @if($lapangan->foto)
                                        <div class="mb-2">
                                            <p class="text-muted small mb-1">Foto saat ini:</p>
                                            <img src="{{ asset('storage/' . $lapangan->foto) }}" alt="Foto lapangan"
                                                 style="width:100%;max-width:320px;height:180px;object-fit:cover;border-radius:0.75rem;border:1px solid #dee2e6;">
                                        </div>
                                    @endif

                                    <input type="file" id="foto" name="foto" accept="image/*"
                                           class="form-control @error('foto') is-invalid @enderror"
                                           onchange="previewFoto(this)">
                                    <div class="form-text">Kosongkan jika tidak ingin mengganti foto. Format: JPG, PNG, WEBP. Maks 2MB.</div>
                                    @error('foto')<div class="invalid-feedback">{{ $message }}</div>@enderror

                                    <!-- Preview foto baru -->
                                    <div id="foto-preview" class="mt-3" style="display:none;">
                                        <p class="text-muted small mb-1">Preview foto baru:</p>
                                        <img id="preview-img" src="#" alt="Preview"
                                             style="width:100%;max-width:320px;height:180px;object-fit:cover;border-radius:0.75rem;border:1px solid #dee2e6;">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-2">
                            <button type="submit" class="btn btn-primary" style="background-color:#ef7d2d;border-color:#ef7d2d;">
                                <i class="bi bi-save me-1"></i>Update Lapangan
                            </button>
                            <a href="{{ route('admin.lapangan.index') }}" class="btn btn-light-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
@endsection 

@push('scripts')
<script>
function previewFoto(input) {
    const preview = document.getElementById('foto-preview');
    const img = document.getElementById('preview-img');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => { img.src = e.target.result; preview.style.display = 'block'; };
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.style.display = 'none';
    }
}
</script>
@endpush
