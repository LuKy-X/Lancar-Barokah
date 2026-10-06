<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Administrator Lancar Barokah',
                'email' => 'admin@lancarbarokah.test',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        User::updateOrCreate(
            ['username' => 'petugas'],
            [
                'name' => 'Petugas Produksi',
                'email' => 'petugas@lancarbarokah.test',
                'password' => Hash::make('password'),
                'role' => 'petugas',
                'is_active' => true,
            ]
        );

        User::updateOrCreate(
            ['username' => 'budi'],
            [
                'name' => 'Budi Santoso (Staff)',
                'email' => 'budi@lancarbarokah.test',
                'password' => Hash::make('password'),
                'role' => 'petugas',
                'is_active' => true,
            ]
        );
    }
}
