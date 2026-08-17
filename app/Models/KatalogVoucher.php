<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KatalogVoucher extends Model
{
    protected $table = 'katalog_voucher';
    protected $fillable = ['kategori_id', 'nama_voucher', 'biaya_poin', 'nilai_potongan', 'masa_berlaku_hari', 'status_aktif'];

    public function kategoriOlahraga()
    {
        return $this->belongsTo(KategoriOlahraga::class, 'kategori_id');
    }

    public function voucherCustomer()
    {
        return $this->hasMany(VoucherCustomer::class, 'voucher_id');
    }
}
