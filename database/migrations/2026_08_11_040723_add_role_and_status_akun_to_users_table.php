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
        Schema::table('users', function (Blueprint $table) {
            // Menambahkan kolom role dengan pilihan ENUM dan nilai bawaan 'Customer'
            $table->enum('role', ['Customer', 'Kasir', 'Admin'])->default('Customer')->after('id');
            
            // Menambahkan kolom status_akun (bisa pakai string atau boolean)
            $table->string('status_akun')->default('Aktif')->after('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Menghapus kolom jika sewaktu-waktu kita melakukan rollback
            $table->dropColumn(['role', 'status_akun']);
        });
    }
};
