<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@parfum.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // User
        User::create([
            'name' => 'Client Test',
            'email' => 'client@parfum.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        // Categories
        $cat1 = \App\Models\Category::create(['name' => 'Parfum Homme', 'slug' => 'parfum-homme', 'description' => 'Parfums pour hommes']);
        $cat2 = \App\Models\Category::create(['name' => 'Parfum Femme', 'slug' => 'parfum-femme', 'description' => 'Parfums pour femmes']);

        // Brands
        $brand1 = \App\Models\Brand::create(['name' => 'Chanel', 'slug' => 'chanel']);
        $brand2 = \App\Models\Brand::create(['name' => 'Dior', 'slug' => 'dior']);

        // Products
        $p1 = \App\Models\Product::create([
            'name' => 'Bleu de Chanel',
            'slug' => 'bleu-de-chanel',
            'description' => 'Un parfum boisé aromatique pour homme.',
            'price' => 95.00,
            'stock' => 50,
            'category_id' => $cat1->id,
            'brand_id' => $brand1->id
        ]);
        
        $p2 = \App\Models\Product::create([
            'name' => 'Sauvage',
            'slug' => 'sauvage',
            'description' => 'Un parfum puissant et noble pour homme.',
            'price' => 105.00,
            'stock' => 30,
            'category_id' => $cat1->id,
            'brand_id' => $brand2->id
        ]);

        $p3 = \App\Models\Product::create([
            'name' => 'Coco Mademoiselle',
            'slug' => 'coco-mademoiselle',
            'description' => 'Un parfum oriental féminin.',
            'price' => 115.00,
            'stock' => 25,
            'category_id' => $cat2->id,
            'brand_id' => $brand1->id
        ]);
        
        $p4 = \App\Models\Product::create([
            'name' => 'J\'adore',
            'slug' => 'j-adore',
            'description' => 'Le grand floral féminin Dior.',
            'price' => 120.00,
            'stock' => 40,
            'category_id' => $cat2->id,
            'brand_id' => $brand2->id
        ]);

        // Packs
        $pack1 = \App\Models\Pack::create([
            'name' => 'Pack Duo Chanel',
            'slug' => 'pack-duo-chanel',
            'description' => 'Le meilleur de Chanel pour homme et femme.',
            'price' => 190.00,
        ]);
        
        $pack1->products()->attach([
            $p1->id => ['quantity' => 1],
            $p3->id => ['quantity' => 1],
        ]);
    }
}
