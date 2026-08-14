<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SesiLangganan extends Model
{
    protected $table = 'sesi_langganan';
    protected $fillable = ['langganan_customer_id', 'booking_id', 'tanggal', 'status'];

    public function langgananCustomer()
    {
        return $this->belongsTo(LanggananCustomer::class, 'langganan_customer_id');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }
}
