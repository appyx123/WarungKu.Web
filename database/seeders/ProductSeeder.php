<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Mengisi data produk dunia nyata
        $products = [
            [
                'name' => 'Samsung Galaxy S23',
                'category_id' => 1, // Elektronik
                'supplier_id' => 1, // PT Samsung Indonesia
                'price' => 12999000,
                'stock' => 50,
            ],
            [
                'name' => 'Kaos Polos Uniqlo',
                'category_id' => 2, // Pakaian
                'supplier_id' => 2, // Uniqlo Japan
                'price' => 199000,
                'stock' => 100,
            ],
            [
                'name' => 'Apple AirPods Pro',
                'category_id' => 3, // Aksesoris
                'supplier_id' => 3, // PT Apple Indonesia
                'price' => 3999000,
                'stock' => 30,
            ],
            [
                'name' => 'H&M Denim Jacket',
                'category_id' => 2, // Pakaian
                'supplier_id' => 4, // H&M Group
                'price' => 599000,
                'stock' => 80,
            ],
            [
                'name' => 'Xiaomi Smart TV 43 Inch',
                'category_id' => 1, // Elektronik
                'supplier_id' => 5, // PT Xiaomi Indonesia
                'price' => 4999000,
                'stock' => 20,
            ],
            [
                'name' => 'Samsung Galaxy S23 Ultra',
                'category_id' => 1,
                'supplier_id' => 1, // PT Samsung Indonesia
                'price' => 19999000,
                'stock' => 40,
            ],
            [
                'name' => 'Xiaomi 13 Pro',
                'category_id' => 1,
                'supplier_id' => 5, // PT Xiaomi Indonesia
                'price' => 19999000,
                'stock' => 3, // Stok rendah
            ],
            [
                'name' => 'Apple MacBook Air M2',
                'category_id' => 1,
                'supplier_id' => 3, // PT Apple Indonesia
                'price' => 18999000,
                'stock' => 15,
            ],
            [
                'name' => 'Samsung QLED TV 55 Inch',
                'category_id' => 1,
                'supplier_id' => 1,
                'price' => 12999000,
                'stock' => 10,
            ],

            // Kategori: Pakaian (category_id: 2)
            [
                'name' => 'Uniqlo Kaos Supima Cotton',
                'category_id' => 2,
                'supplier_id' => 2, // Uniqlo Japan
                'price' => 199000,
                'stock' => 80,
            ],
            [
                'name' => 'H&M Slim Fit Jeans',
                'category_id' => 2,
                'supplier_id' => 4, // H&M Group
                'price' => 499000,
                'stock' => 60,
            ],
            [
                'name' => 'Uniqlo Jaket Parka',
                'category_id' => 2,
                'supplier_id' => 2,
                'price' => 799000,
                'stock' => 2, // Stok rendah
            ],
            [
                'name' => 'H&M Kemeja Oxford',
                'category_id' => 2,
                'supplier_id' => 4,
                'price' => 399000,
                'stock' => 50,
            ],

            // Kategori: Aksesoris (category_id: 3)
            [
                'name' => 'Apple AirPods Pro 2',
                'category_id' => 3,
                'supplier_id' => 3,
                'price' => 4299000,
                'stock' => 25,
            ],
            [
                'name' => 'Samsung Galaxy Watch 6',
                'category_id' => 3,
                'supplier_id' => 1,
                'price' => 3999000,
                'stock' => 1, // Stok rendah
            ],
            [
                'name' => 'Xiaomi Power Bank 10000mAh',
                'category_id' => 3,
                'supplier_id' => 5,
                'price' => 299000,
                'stock' => 100,
            ],
            [
                'name' => 'H&M Topi Baseball',
                'category_id' => 3,
                'supplier_id' => 4,
                'price' => 149000,
                'stock' => 70,
            ],

            // Kategori: Peralatan Rumah Tangga (category_id: 4)
            [
                'name' => 'Xiaomi Robot Vacuum Mop 2',
                'category_id' => 4,
                'supplier_id' => 5,
                'price' => 3999000,
                'stock' => 20,
            ],
            [
                'name' => 'Samsung Microwave Oven 23L',
                'category_id' => 4,
                'supplier_id' => 1,
                'price' => 1599000,
                'stock' => 30,
            ],
            [
                'name' => 'Apple HomePod Mini',
                'category_id' => 4,
                'supplier_id' => 3,
                'price' => 1999000,
                'stock' => 0, // Stok rendah
            ],
            [
                'name' => 'Uniqlo Selimut Flanel',
                'category_id' => 4,
                'supplier_id' => 2,
                'price' => 499000,
                'stock' => 40,
            ],

            // Kategori: Sepatu (category_id: 5)
            [
                'name' => 'H&M Sneakers Putih',
                'category_id' => 5,
                'supplier_id' => 4,
                'price' => 399000,
                'stock' => 90,
            ],
            [
                'name' => 'Apple AirMax 97 (Custom)',
                'category_id' => 5,
                'supplier_id' => 3,
                'price' => 2999000,
                'stock' => 4, // Stok rendah
            ],
            [
                'name' => 'Uniqlo Sandal Slip-On',
                'category_id' => 5,
                'supplier_id' => 2,
                'price' => 249000,
                'stock' => 60,
            ],
            [
                'name' => 'Samsung Sneakers Kolaborasi',
                'category_id' => 5,
                'supplier_id' => 1,
                'price' => 999000,
                'stock' => 35,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
