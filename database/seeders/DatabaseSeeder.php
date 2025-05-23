<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Menjalankan seeder dalam urutan yang benar
        $this->call([
            SupplierSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
        ]);
    }
}
