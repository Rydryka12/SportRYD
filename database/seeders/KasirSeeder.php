<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class KasirSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Kasir Demo',
            'email' => 'kasir@sport.id',
            'password' => Hash::make('12345678'),
            'role' => 'Kasir',
            'status_akun' => 'Aktif',
        ]);
    }
}
