<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';
    protected $fillable = ['booking_id', 'jumlah', 'metode', 'status', 'dikonfirmasi_oleh'];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function dikonfirmasiOleh()
    {
        return $this->belongsTo(User::class, 'dikonfirmasi_oleh');
    }
}
