<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CompanyProfileSeeder::class,
            CategorySeeder::class,
            UnitSeeder::class,
            PaymentMethodSeeder::class,
            CustomerSeeder::class,
            ProductSeeder::class,
            TransactionSeeder::class,
        ]);
    }
}
