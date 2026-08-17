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
        Schema::create('poin_customer', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('users');
            $table->foreignId('booking_id')->nullable()->constrained('booking');
            $table->foreignId('langganan_customer_id')->nullable()->constrained('langganan_customer');
            $table->integer('jumlah_poin');
            $table->string('jenis'); // 'Masuk' / 'Keluar'
            $table->string('keterangan');
            $table->date('tanggal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('poin_customer');
    }
};
