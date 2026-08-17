@extends('layouts.app')

@section('content')
<div class="page-heading">
    <h3 class="mb-4">Edit Rasio Tukar Kuota</h3>
    <section class="section">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.poin-voucher.tukar-kuota.update', $tukarKuota) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Kategori Olahraga</label>
                        <select name="kategori_id" class="form-select">
                            @foreach ($kategoriList as $kategori)
                                <option value="{{ $kategori->id }}" @selected(old('kategori_id', $tukarKuota->kategori_id) == $kategori->id)>{{ $kategori->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Biaya Poin per Sesi</label>
                            <input type="number" name="biaya_poin" value="{{ old('biaya_poin', $tukarKuota->biaya_poin) }}" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jumlah Sesi Didapat</label>
                            <input type="number" name="jumlah_sesi_didapat" value="{{ old('jumlah_sesi_didapat', $tukarKuota->jumlah_sesi_didapat) }}" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status_aktif" class="form-select">
                            <option value="Aktif" @selected(old('status_aktif', $tukarKuota->status_aktif) === 'Aktif')>Aktif</option>
                            <option value="Nonaktif" @selected(old('status_aktif', $tukarKuota->status_aktif) === 'Nonaktif')>Nonaktif</option>
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