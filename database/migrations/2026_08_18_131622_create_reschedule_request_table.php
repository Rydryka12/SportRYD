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
        Schema::create('reschedule_request', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('booking');
            $table->foreignId('diajukan_oleh')->constrained('users');
            $table->foreignId('diproses_oleh')->nullable()->constrained('users');
            $table->string('status')->default('Menunggu'); // Menunggu / Disetujui / Ditolak
            $table->string('jadwal_baru'); // JSON: {tanggal, jam_mulai, jam_selesai, alasan}
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reschedule_request');
    }
};
