<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Restaurant;

class RestaurantSeeder extends Seeder
{
    public function run(): void
    {
        Restaurant::updateOrCreate(
            ['email' => 'info@krutongheng.com'],
            [
                'name' => 'Kru Tong Heng',
                'phone' => '+855 123 456 789',
                'address' => '123 Street Name, City',
                'description' => 'Best food in town',
            ]
        );
    }
}
