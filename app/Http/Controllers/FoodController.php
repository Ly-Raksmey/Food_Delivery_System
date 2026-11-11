<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Food;
use App\Models\Review;

class FoodController extends Controller
{
    public function index(Request $request)
    {
        $query = Food::where('status', 'available')->with('category');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('min_price') && $request->filled('max_price')) {
            $query->whereBetween('price', [$request->min_price, $request->max_price]);
        }

        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'name':
                    $query->orderBy('name', 'asc');
                    break;
            }
        } else {
            $query->latest();
        }

        $foods = $query->paginate(12);
        $categories = Category::where('status', 'active')->get();

        return view('customer.foods.index', compact('foods', 'categories'));
    }

    public function show(Food $food)
    {
        if ($food->status !== 'available') {
            abort(404);
        }

        $food->load('category', 'reviews.user', 'reviews.replies.user');
        $reviews = $food->reviews()->where('status', 'active')->whereNull('parent_id')->latest()->get();

        return view('customer.foods.show', compact('food', 'reviews'));
    }
}
