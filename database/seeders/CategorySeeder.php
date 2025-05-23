<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Mengisi data kategori dunia nyata
        $categories = [
            ['name' => 'Elektronik'],
            ['name' => 'Pakaian'],
            ['name' => 'Aksesoris'],
            ['name' => 'Peralatan Rumah Tangga'],
            ['name' => 'Sepatu'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
