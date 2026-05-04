<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::query()->firstOrCreate(
            ['email' => 'admin@juragandaging.test'],
            [
                'name' => 'Admin Juragan Daging',
                'phone' => '081234567890',
                'address' => 'Morowali, Sulawesi Tengah',
                'password' => 'admin12345',
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        User::query()->firstOrCreate(
            ['email' => 'member@juragandaging.test'],
            [
                'name' => 'Member Demo',
                'phone' => '081298765432',
                'address' => 'Morowali',
                'password' => 'member12345',
                'role' => 'customer',
                'email_verified_at' => now(),
            ]
        );

        $products = collect(config('products'))->map(function (array $item): array {
            return [
                'name' => $item['name'],
                'slug' => $item['slug'] ?? Str::slug($item['name']),
                'category' => $item['category'] ?? 'Lainnya',
                'badge' => $item['badge'] ?? null,
                'unit' => ltrim($item['unit'] ?? 'pcs', '/'),
                'price' => (int) ($item['price'] ?? 0),
                'stock' => 100,
                'image_url' => $item['image'] ?? null,
                'description' => $item['description'] ?? null,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        })->all();

        Product::query()->upsert(
            $products,
            ['slug'],
            ['name', 'category', 'badge', 'unit', 'price', 'stock', 'image_url', 'description', 'is_active', 'updated_at']
        );
    }
}
