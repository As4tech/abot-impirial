<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['name' => 'Bottled Water', 'price' => 25.00],
            ['name' => 'Coffee', 'price' => 80.00],
            ['name' => 'Sandwich', 'price' => 120.00],
            ['name' => 'Fried Rice', 'price' => 90.00],
            ['name' => 'Chicken Meal', 'price' => 180.00],
        ];

        foreach ($products as $p) {
            Product::updateOrCreate(['name' => $p['name']], ['price' => $p['price']]);
        }
    }
}
