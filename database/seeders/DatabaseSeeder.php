<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => 'Admin12345',
                'role' => User::ROLE_ADMIN,
            ]
        );

        User::updateOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'Demo Customer',
                'password' => 'Customer12345',
                'role' => User::ROLE_USER,
            ]
        );

        $electronics = Category::firstOrCreate(
            ['name' => 'Electronics'],
            ['description' => 'Electronic devices and accessories.']
        );

        $books = Category::firstOrCreate(
            ['name' => 'Books'],
            ['description' => 'Educational and general books.']
        );

        $clothes = Category::firstOrCreate(
            ['name' => 'Clothes'],
            ['description' => 'Clothing and fashion products.']
        );

        $products = [
            ['category' => $electronics, 'name' => 'Laptop', 'description' => 'High performance laptop for work and study.', 'price' => 750, 'stock' => 10],
            ['category' => $electronics, 'name' => 'Smartphone', 'description' => 'Modern smartphone with excellent features.', 'price' => 500, 'stock' => 15],
            ['category' => $books, 'name' => 'Laravel Book', 'description' => 'A practical book for learning Laravel.', 'price' => 35, 'stock' => 20],
            ['category' => $clothes, 'name' => 'T-Shirt', 'description' => 'Comfortable cotton t-shirt.', 'price' => 20, 'stock' => 30],
        ];

        foreach ($products as $item) {
            $product = Product::withTrashed()->firstOrNew(['name' => $item['name']]);
            $product->fill([
                'category_id' => $item['category']->id,
                'description' => $item['description'],
                'price' => $item['price'],
                'stock' => $item['stock'],
            ]);
            $product->deleted_at = null;
            $product->save();
        }
    }
}
