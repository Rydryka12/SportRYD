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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('booking');
            $table->unsignedInteger('jumlah');
            $table->string('metode'); // Tunai / QRIS / dll
            $table->string('status')->default('Menunggu Konfirmasi'); // Menunggu Konfirmasi / Terkonfirmasi
            $table->foreignId('dikonfirmasi_oleh')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
