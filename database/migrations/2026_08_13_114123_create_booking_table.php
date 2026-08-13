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
        Schema::create('booking', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('users');
            $table->foreignId('lapangan_id')->constrained('lapangan');
            $table->foreignId('kasir_id')->nullable()->constrained('users');
            $table->unsignedBigInteger('sesi_langganan_id')->nullable(); // FK ditambah nanti (Fase 4)
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('status')->default('Akan Datang'); // Akan Datang / Selesai / Menunggu Approval Reschedule / Dibatalkan
            $table->string('sumber')->default('Customer'); // Customer / Kasir
            $table->unsignedInteger('harga');
            $table->unsignedBigInteger('voucher_customer_id')->nullable(); // FK ditambah nanti (Fase 6)
            $table->unsignedInteger('potongan_voucher')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking');
    }
};
