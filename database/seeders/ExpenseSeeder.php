<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ExpenseSeeder extends Seeder
{
    public function run(): void
    {
        $expenses = [
            ['title' => 'Flour Purchase', 'category' => 'Ingredients', 'amount' => 500, 'description' => 'Monthly flour supply', 'expense_date' => now()->subDays(5)],
            ['title' => 'Staff Salary', 'category' => 'Salary', 'amount' => 1200, 'description' => 'Chef monthly salary', 'expense_date' => now()->subDays(10)],
            ['title' => 'Delivery Fuel', 'category' => 'Delivery', 'amount' => 150, 'description' => 'Fuel for delivery scooters', 'expense_date' => now()->subDays(3)],
            ['title' => 'Electricity Bill', 'category' => 'Utilities', 'amount' => 300, 'description' => 'Restaurant electricity bill', 'expense_date' => now()->subDays(7)],
            ['title' => 'Packaging Materials', 'category' => 'Supplies', 'amount' => 80, 'description' => 'Food containers and bags', 'expense_date' => now()->subDays(2)],
        ];

        foreach ($expenses as $expense) {
            \App\Models\Expense::create($expense);
        }
    }
}
