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
        Schema::create('katalog_tukar_kuota', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->constrained('kategori_olahraga');
            $table->unsignedInteger('biaya_poin'); // poin dibutuhkan per 1 sesi
            $table->unsignedInteger('jumlah_sesi_didapat')->default(1);
            $table->string('status_aktif')->default('Aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('katalog_tukar_kuota');
    }
};
