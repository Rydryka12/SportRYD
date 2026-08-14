<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LanggananCustomer extends Model
{
    protected $table = 'langganan_customer';
    protected $fillable = [
        'customer_id', 'paket_id', 'lapangan_id',
        'hari_dalam_minggu', 'jam_mulai', 'jam_selesai',
        'sisa_sesi', 'tanggal_mulai', 'tanggal_berakhir', 'status',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function paketLangganan()
    {
        return $this->belongsTo(PaketLangganan::class, 'paket_id');
    }

    public function lapangan()
    {
        return $this->belongsTo(Lapangan::class, 'lapangan_id');
    }

    public function sesiLangganan()
    {
        return $this->hasMany(SesiLangganan::class, 'langganan_customer_id');
    }
}
