<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriOlahraga extends Model
{
    protected $table = 'kategori_olahraga';
    protected $fillable = ['nama_kategori', 'deskripsi'];

    public function lapangan() {
        return $this->hasMany(Lapangan::class, 'kategori_id');
    }
    public function paketLangganan()
    {
        return $this->hasMany(PaketLangganan::class, 'kategori_id');
    }
    public function katalogVoucher()
    {
        return $this->hasMany(KatalogVoucher::class, 'kategori_id');
    }

    public function katalogTukarKuota()
    {
        return $this->hasMany(KatalogTukarKuota::class, 'kategori_id');
    }
}
