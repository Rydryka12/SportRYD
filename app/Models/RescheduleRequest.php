<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RescheduleRequest extends Model
{
    protected $table = 'reschedule_request';
    protected $fillable = ['booking_id', 'diajukan_oleh', 'diproses_oleh', 'status', 'jadwal_baru'];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function diajukanOleh()
    {
        return $this->belongsTo(User::class, 'diajukan_oleh');
    }

    public function diprosesOleh()
    {
        return $this->belongsTo(User::class, 'diproses_oleh');
    }

    public function getJadwalBaruArrayAttribute(): array
    {
        return json_decode($this->jadwal_baru, true) ?? [];
    }
}
