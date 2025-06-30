<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            [
                'name' => 'PT Samsung Indonesia',
                'phone' => '021-12345678',
                'address' => 'Jl. Jend. Sudirman No. 123, Jakarta Selatan',
            ],
            [
                'name' => 'Uniqlo Japan',
                'phone' => '+81-3-1234-5678',
                'address' => '1-1-1 Minami, Tokyo, Japan',
            ],
            [
                'name' => 'PT Apple Indonesia',
                'phone' => '021-87654321',
                'address' => 'Jl. Thamrin No. 45, Jakarta Pusat',
            ],
            [
                'name' => 'H&M Group',
                'phone' => '+46-8-123-4567',
                'address' => 'Master Samuelsgatan 46A, Stockholm, Sweden',
            ],
            [
                'name' => 'PT Xiaomi Indonesia',
                'phone' => '021-5556677',
                'address' => 'Jl. Gatot Subroto No. 89, Jakarta Selatan',
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}
