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
        Schema::create('katalog_voucher', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->constrained('kategori_olahraga');
            $table->string('nama_voucher');
            $table->unsignedInteger('biaya_poin');
            $table->unsignedInteger('nilai_potongan');
            $table->unsignedInteger('masa_berlaku_hari');
            $table->string('status_aktif')->default('Aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('katalog_voucher');
    }
};
