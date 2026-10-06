<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = [
            [
                'no_customer' => 'CUST-0001',
                'nama' => 'Komunitas Gowes Solo Raya',
                'no_hp' => '081298765432',
                'alamat' => 'Jl. Slamet Riyadi No. 100, Surakarta',
                'email' => 'gowessolo@gmail.com',
            ],
            [
                'no_customer' => 'CUST-0002',
                'nama' => 'Karang Taruna Merdeka Makmur',
                'no_hp' => '085712345678',
                'alamat' => 'Desa Gonilan RT 02/03, Kartasura, Sukoharjo',
                'email' => 'kt.merdeka@gmail.com',
            ],
            [
                'no_customer' => 'CUST-0003',
                'nama' => 'PT Maju Bersama Logistik',
                'no_hp' => '081324567890',
                'alamat' => 'Kawasan Industri Palur Blok C-12, Karanganyar',
                'email' => 'procurement@majubersama.co.id',
            ],
            [
                'no_customer' => 'CUST-0004',
                'nama' => 'SMA Negeri 1 Sukoharjo (OSIS)',
                'no_hp' => '087834567123',
                'alamat' => 'Jl. Pemuda No. 15, Sukoharjo',
                'email' => 'osis.sman1skh@gmail.com',
            ],
            [
                'no_customer' => 'CUST-0005',
                'nama' => 'Kedai Kopi Senja Teduh',
                'no_hp' => '082133445566',
                'alamat' => 'Jl. Mangkubumi No. 8, Solo',
                'email' => 'senjateduhcoffee@gmail.com',
            ],
            [
                'no_customer' => 'CUST-0006',
                'nama' => 'Universitas Sebelas Maret (BEM FK)',
                'no_hp' => '081567890123',
                'alamat' => 'Kentingan, Jebres, Surakarta',
                'email' => 'bemfk@uns.ac.id',
            ],
            [
                'no_customer' => 'CUST-0007',
                'nama' => 'CV Berkah Abadi Transport',
                'no_hp' => '085299887766',
                'alamat' => 'Jl. Solo-Semarang Km. 14, Boyolali',
                'email' => 'berkahabadi@transport.id',
            ],
            [
                'no_customer' => 'CUST-0008',
                'nama' => 'Bengkel Motor Rejeki Lancar',
                'no_hp' => '089612345678',
                'alamat' => 'Jl. Rajawali No. 22, Kartasura',
                'email' => 'rejekilancarmotor@gmail.com',
            ],
        ];

        foreach ($customers as $cust) {
            Customer::updateOrCreate(['no_customer' => $cust['no_customer']], $cust);
        }
    }
}
