<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            // true = kasir sudah konfirmasi DP, waktu main bisa berjalan
            // false (default) = belum bayar DP, booking otomatis batal saat jam_mulai tiba
            $table->boolean('dp_terkonfirmasi')->default(false)->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->dropColumn('dp_terkonfirmasi');
        });
    }
};
