<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class FoodSeeder extends Seeder
{
    public function run(): void
    {
        $categories = \App\Models\Category::all();

        $foods = [
            ['category_id' => $categories[0]->id, 'name' => 'Margherita Pizza', 'description' => 'Classic tomato sauce, mozzarella, and fresh basil', 'price' => 12.99, 'status' => 'available'],
            ['category_id' => $categories[0]->id, 'name' => 'Pepperoni Pizza', 'description' => 'Spicy pepperoni with mozzarella cheese', 'price' => 14.99, 'status' => 'available'],
            ['category_id' => $categories[0]->id, 'name' => 'Veggie Supreme', 'description' => 'Mixed vegetables with olive oil', 'price' => 13.49, 'status' => 'available'],
            ['category_id' => $categories[1]->id, 'name' => 'Classic Burger', 'description' => 'Beef patty with lettuce, tomato, and cheese', 'price' => 9.99, 'status' => 'available'],
            ['category_id' => $categories[1]->id, 'name' => 'Cheese Burger', 'description' => 'Double cheese with special sauce', 'price' => 11.49, 'status' => 'available'],
            ['category_id' => $categories[1]->id, 'name' => 'Chicken Burger', 'description' => 'Grilled chicken with mayo and pickles', 'price' => 10.99, 'status' => 'available'],
            ['category_id' => $categories[2]->id, 'name' => 'Fried Chicken', 'description' => 'Crispy fried chicken pieces', 'price' => 15.99, 'status' => 'available'],
            ['category_id' => $categories[2]->id, 'name' => 'Grilled Chicken', 'description' => 'Herb-marinated grilled chicken', 'price' => 16.99, 'status' => 'available'],
            ['category_id' => $categories[2]->id, 'name' => 'Chicken Wings', 'description' => 'Spicy buffalo wings with dip', 'price' => 12.49, 'status' => 'available'],
            ['category_id' => $categories[3]->id, 'name' => 'Coca Cola', 'description' => 'Chilled classic Coca Cola', 'price' => 2.49, 'status' => 'available'],
            ['category_id' => $categories[3]->id, 'name' => 'Fresh Orange Juice', 'description' => 'Freshly squeezed orange juice', 'price' => 4.99, 'status' => 'available'],
            ['category_id' => $categories[3]->id, 'name' => 'Iced Lemon Tea', 'description' => 'Refreshing iced lemon tea', 'price' => 3.49, 'status' => 'available'],
            ['category_id' => $categories[4]->id, 'name' => 'Chocolate Cake', 'description' => 'Rich chocolate layer cake', 'price' => 8.99, 'status' => 'available'],
            ['category_id' => $categories[4]->id, 'name' => 'Ice Cream Sundae', 'description' => 'Vanilla ice cream with chocolate sauce', 'price' => 6.49, 'status' => 'available'],
            ['category_id' => $categories[4]->id, 'name' => 'Cheesecake', 'description' => 'New York style cheesecake', 'price' => 7.99, 'status' => 'available'],
        ];

        foreach ($foods as $food) {
            \App\Models\Food::create($food);
        }
    }
}
