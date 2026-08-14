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
        Schema::create('langganan_customer', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('users');
            $table->foreignId('paket_id')->constrained('paket_langganan');
            $table->foreignId('lapangan_id')->nullable()->constrained('lapangan'); // hanya wajib utk tipe Jadwal Tetap
            $table->string('hari_dalam_minggu')->nullable();  // khusus Jadwal Tetap
            $table->time('jam_mulai')->nullable();             // khusus Jadwal Tetap
            $table->time('jam_selesai')->nullable();           // khusus Jadwal Tetap
            $table->unsignedInteger('sisa_sesi')->default(0);  // dipakai aktif utk tipe Kuota
            $table->date('tanggal_mulai');
            $table->date('tanggal_berakhir');
            $table->string('status')->default('Aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('langganan_customer');
    }
};
