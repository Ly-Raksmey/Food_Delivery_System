<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Food;

class CategoryController extends Controller
{
    public function show(Category $category)
    {
        if ($category->status !== 'active') {
            abort(404);
        }

        $foods = Food::where('category_id', $category->id)
            ->where('status', 'available')
            ->latest()
            ->paginate(12);

        return view('customer.categories.show', compact('category', 'foods'));
    }
}
