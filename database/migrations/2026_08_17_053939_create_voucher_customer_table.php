<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('voucher_customer', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('users');
            $table->foreignId('voucher_id')->constrained('katalog_voucher');
            $table->string('kode_voucher')->unique();
            $table->date('tanggal_tukar');
            $table->date('tanggal_expired');
            $table->string('status')->default('Aktif'); // Aktif / Terpakai / Kedaluwarsa
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voucher_customer');
    }
};
