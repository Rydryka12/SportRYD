<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lapangan extends Model
{
    protected $table = 'lapangan';
    protected $fillable = ['kategori_id', 'nama_lapangan', 'deskripsi', 'tarif_per_jam', 'status_aktif'];

    public function kategoriOlahraga(){
        return $this->belongsTo(kategoriOlahraga::class, 'kategori_id');
    }
}
