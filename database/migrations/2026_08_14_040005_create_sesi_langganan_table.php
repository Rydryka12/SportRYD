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
        Schema::create('sesi_langganan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('langganan_customer_id')->constrained('langganan_customer');
            $table->foreignId('booking_id')->constrained('booking');
            $table->date('tanggal');
            $table->string('status')->default('Akan Datang');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sesi_langganan');
    }
};
