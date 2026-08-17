<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PoinCustomer extends Model
{
    protected $table = 'poin_customer';
    protected $fillable = [
        'customer_id', 'booking_id', 'langganan_customer_id',
        'jumlah_poin', 'jenis', 'keterangan', 'tanggal',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function langgananCustomer()
    {
        return $this->belongsTo(LanggananCustomer::class, 'langganan_customer_id');
    }
}
