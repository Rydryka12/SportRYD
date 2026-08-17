<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KatalogTukarKuota extends Model
{
    protected $table = 'katalog_tukar_kuota';
    protected $fillable = ['kategori_id', 'biaya_poin', 'jumlah_sesi_didapat', 'status_aktif'];

    public function kategoriOlahraga()
    {
        return $this->belongsTo(KategoriOlahraga::class, 'kategori_id');
    }
}
