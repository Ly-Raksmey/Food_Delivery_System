<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Food;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::where('status', 'active')->take(8)->get();
        $popularFoods = Food::where('status', 'available')
            ->with('category')
            ->inRandomOrder()
            ->take(8)
            ->get();
        $specialFoods = Food::where('status', 'available')
            ->with('category')
            ->withAvg('reviews', 'rating')
            ->latest()
            ->take(4)
            ->get();

        return view('customer.home', compact('categories', 'popularFoods', 'specialFoods'));
    }
}
