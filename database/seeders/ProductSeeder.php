<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for( $i = 1; $i <= 20; $i++ ) {
            Product::create([
                'name' => "Product $i",
                'slug' => "product-$i",
                'description' => "Description for Product $i",
                'discount_percent' => rand(10, 50),
                'price' => rand(100, 1000) * 1000,
                'category_id' => rand(1, 5),
                'brand_id' => rand(1, 5),
            ]);
        }
    }
}
