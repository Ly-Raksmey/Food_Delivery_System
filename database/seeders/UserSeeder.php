<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        \App\Models\User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'phone' => '012345678',
                'address' => 'Phnom Penh, Cambodia',
            ]
        );

        $faker = Faker::create();
        $customerData = [
            ['John Doe', 'john@example.com'],
            ['Sarah Smith', 'sarah@example.com'],
            ['Mike Johnson', 'mike@example.com'],
            ['Emma Wilson', 'emma@example.com'],
            ['Alex Brown', 'alex@example.com'],
        ];

        foreach ($customerData as $data) {
            $customer = \App\Models\User::updateOrCreate(
                ['email' => $data[1]],
                [
                    'name' => $data[0],
                    'password' => Hash::make('customer123'),
                    'role' => 'customer',
                    'phone' => $faker->phoneNumber,
                    'address' => $faker->address,
                ]
            );

            \App\Models\Address::create([
                'user_id' => $customer->id,
                'recipient_name' => $customer->name,
                'phone' => $faker->phoneNumber,
                'address' => $faker->streetAddress,
                'city' => 'Phnom Penh',
                'latitude' => $faker->latitude,
                'longitude' => $faker->longitude,
                'is_default' => true,
            ]);

            \App\Models\Address::create([
                'user_id' => $customer->id,
                'recipient_name' => $customer->name,
                'phone' => $faker->phoneNumber,
                'address' => $faker->streetAddress,
                'city' => 'Phnom Penh',
                'latitude' => $faker->latitude,
                'longitude' => $faker->longitude,
                'is_default' => false,
            ]);

            \App\Models\Cart::create([
                'user_id' => $customer->id,
            ]);
        }

        $deliveryData = [
            ['Delivery Staff', 'delivery@gmail.com'],
            ['John Delivery', 'john.delivery@example.com'],
            ['Sarah Delivery', 'sarah.delivery@example.com'],
        ];

        foreach ($deliveryData as $data) {
            \App\Models\User::updateOrCreate(
                ['email' => $data[1]],
                [
                    'name' => $data[0],
                    'password' => Hash::make('delivery123'),
                    'role' => 'delivery',
                    'phone' => $faker->phoneNumber,
                    'address' => $faker->address,
                ]
            );
        }
    }
}
