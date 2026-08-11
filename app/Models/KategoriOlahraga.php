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
}
