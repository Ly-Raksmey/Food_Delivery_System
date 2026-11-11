<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Pizza', 'description' => 'Delicious Italian pizzas with fresh ingredients', 'status' => 'active'],
            ['name' => 'Burger', 'description' => 'Juicy burgers with premium beef and fresh veggies', 'status' => 'active'],
            ['name' => 'Chicken', 'description' => 'Crispy and grilled chicken dishes', 'status' => 'active'],
            ['name' => 'Drinks', 'description' => 'Refreshing beverages and juices', 'status' => 'active'],
            ['name' => 'Dessert', 'description' => 'Sweet treats and cakes', 'status' => 'active'],
        ];

        foreach ($categories as $category) {
            \App\Models\Category::create($category);
        }
    }
}
