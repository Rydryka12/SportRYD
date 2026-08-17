<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoucherCustomer extends Model
{
    protected $table = 'voucher_customer';
    protected $fillable = ['customer_id', 'voucher_id', 'kode_voucher', 'tanggal_tukar', 'tanggal_expired', 'status'];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function katalogVoucher()
    {
        return $this->belongsTo(KatalogVoucher::class, 'voucher_id');
    }
}
