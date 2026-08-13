<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'booking';
    protected $fillable = [
        'customer_id', 'lapangan_id', 'kasir_id', 'sesi_langganan_id',
        'tanggal', 'jam_mulai', 'jam_selesai', 'status', 'sumber',
        'harga', 'voucher_customer_id', 'potongan_voucher',
    ];
    
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function lapangan()
    {
        return $this->belongsTo(Lapangan::class, 'lapangan_id');
    }
}

