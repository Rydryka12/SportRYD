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
        Schema::create('paket_langganan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->constrained('kategori_olahraga')->restrictOnDelete();
            $table->string('nama_paket');
            $table->enum('tipe_paket', ['Kuota', 'Jadwal Tetap']);
            $table->unsignedInteger('jumlah_sesi');
            $table->unsignedInteger('durasi_jam_per_sesi');
            $table->unsignedInteger('masa_berlaku_hari');
            $table->unsignedInteger('harga');
            $table->string('status_aktif')->default('Aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paket_langganan');
    }
};
