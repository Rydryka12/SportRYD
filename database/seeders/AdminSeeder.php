<?php

namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'AdminSportRYD',
            'email' => 'admin@sport.id',
            'password' => Hash::make('12345678'),
            'role' => 'Admin',
            'status_akun' => 'Aktif',
        ]);
    }
}
