<?php

namespace Database\Seeders;

use App\Models\StoreProduct;
use Illuminate\Database\Seeder;

class StoreProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Class of 1975 T-Shirt',
                'description' => 'Commemorative t-shirt featuring our class year and school logo. Made from 100% premium cotton.',
                'price' => 25.00,
                'stock' => 50,
                'is_active' => true,
                'image' => 'products/tshirt.jpg',
            ],
            [
                'name' => 'Reunion Coffee Mug',
                'description' => 'Ceramic mug with "Class of 1975" imprint. Perfect for your morning coffee.',
                'price' => 15.00,
                'stock' => 30,
                'is_active' => true,
                'image' => 'products/mug.jpg',
            ],
            [
                'name' => 'Alumni Yearbook',
                'description' => 'Hardcover yearbook with photos and memories from our school days.',
                'price' => 45.00,
                'stock' => 15,
                'is_active' => true,
                'image' => 'products/yearbook.jpg',
            ],
            [
                'name' => 'Class Ring Replica',
                'description' => 'Sterling silver replica of our class ring. Available in various sizes.',
                'price' => 125.00,
                'stock' => 10,
                'is_active' => true,
                'image' => 'products/ring.jpg',
            ],
            [
                'name' => 'Graduation Photo Frame',
                'description' => 'Elegant wooden frame designed for graduation photos. Engraving available.',
                'price' => 35.00,
                'stock' => 25,
                'is_active' => true,
                'image' => 'products/frame.jpg',
            ],
        ];

        foreach ($products as $productData) {
            StoreProduct::create($productData);
        }
    }
}
