@extends('layouts.app')

@section('content')
<div class="page-heading">
    <h3 class="mb-4">Tambah Voucher</h3>
    <section class="section">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.poin-voucher.voucher.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Kategori Olahraga</label>
                        <select name="kategori_id" class="form-select @error('kategori_id') is-invalid @enderror">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($kategoriList as $kategori)
                                <option value="{{ $kategori->id }}" @selected(old('kategori_id') == $kategori->id)>{{ $kategori->nama_kategori }}</option>
                            @endforeach
                        </select>
                        @error('kategori_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Voucher</label>
                        <input type="text" name="nama_voucher" value="{{ old('nama_voucher') }}" class="form-control @error('nama_voucher') is-invalid @enderror">
                        @error('nama_voucher') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Biaya Poin</label>
                            <input type="number" name="biaya_poin" value="{{ old('biaya_poin') }}" class="form-control @error('biaya_poin') is-invalid @enderror">
                            @error('biaya_poin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Nilai Potongan (Rp)</label>
                            <input type="number" name="nilai_potongan" value="{{ old('nilai_potongan') }}" class="form-control @error('nilai_potongan') is-invalid @enderror">
                            @error('nilai_potongan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Masa Berlaku (hari)</label>
                            <input type="number" name="masa_berlaku_hari" value="{{ old('masa_berlaku_hari') }}" class="form-control @error('masa_berlaku_hari') is-invalid @enderror">
                            @error('masa_berlaku_hari') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status_aktif" class="form-select">
                            <option value="Aktif" @selected(old('status_aktif') === 'Aktif')>Aktif</option>
                            <option value="Nonaktif" @selected(old('status_aktif') === 'Nonaktif')>Nonaktif</option>
                        </select>
                    </div>
                    <a href="{{ route('admin.poin-voucher.index') }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection