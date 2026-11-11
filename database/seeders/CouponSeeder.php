<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        $coupons = [
            ['code' => 'FOOD20', 'type' => 'percentage', 'value' => 20, 'minimum_order_amount' => 20, 'maximum_discount' => 10, 'start_date' => '2024-01-01', 'end_date' => '2025-12-31', 'usage_limit' => 100, 'used_count' => 0, 'status' => 'active'],
            ['code' => 'SAVE10', 'type' => 'fixed', 'value' => 10, 'minimum_order_amount' => 30, 'maximum_discount' => null, 'start_date' => '2024-01-01', 'end_date' => '2025-12-31', 'usage_limit' => 50, 'used_count' => 0, 'status' => 'active'],
            ['code' => 'WELCOME', 'type' => 'percentage', 'value' => 15, 'minimum_order_amount' => 15, 'maximum_discount' => 8, 'start_date' => '2024-01-01', 'end_date' => '2025-12-31', 'usage_limit' => 200, 'used_count' => 0, 'status' => 'active'],
            ['code' => 'FREESHIP', 'type' => 'fixed', 'value' => 5, 'minimum_order_amount' => 25, 'maximum_discount' => null, 'start_date' => '2024-01-01', 'end_date' => '2025-12-31', 'usage_limit' => 150, 'used_count' => 0, 'status' => 'active'],
            ['code' => 'HUNGRY', 'type' => 'percentage', 'value' => 25, 'minimum_order_amount' => 40, 'maximum_discount' => 15, 'start_date' => '2024-01-01', 'end_date' => '2025-12-31', 'usage_limit' => 80, 'used_count' => 0, 'status' => 'active'],
        ];

        foreach ($coupons as $coupon) {
            \App\Models\Coupon::updateOrCreate(
                ['code' => $coupon['code']],
                $coupon
            );
        }
    }
}
