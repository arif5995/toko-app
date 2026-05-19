<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {

        // Buat Kategori Dummy
        $elektronik = Category::create(['id' => 1, 'name' => 'Elektronik', 'slug' => 'elektronik']);
        $pakaian = Category::create(['id' => 2, 'name' => 'Pakaian', 'slug' => 'pakaian']);

        // Buat Produk Dummy
        Product::create([
            'name' => 'Laptop ASUS ROG',
            'category_id' => $elektronik->id,
            'price' => 18500000,
            'stock' => 10,
            'is_active' => 1,
        ]);

        Product::create([
            'name' => 'Kemeja Flanel',
            'category_id' => $pakaian->id,
            'price' => 150000,
            'stock' => 3, // sengaja diset < 5 untuk testing low stock count
            'is_active' => 1,
        ]);
    }
}