<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaketLangganan extends Model
{
    protected $table = 'paket_langganan';
    protected $fillable = [
        'kategori_id', 'nama_paket', 'tipe_paket', 'jumlah_sesi', 'durasi_jam_per_sesi', 'masa_berlaku_hari',
        'harga', 'status_aktif',
    ];

    public function kategoriOlahraga(){
        return $this->belongsTo(KategoriOlahraga::class, 'kategori_id');
    }
}
