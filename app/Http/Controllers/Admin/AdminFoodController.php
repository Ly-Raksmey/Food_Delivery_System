<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Food;
use App\Models\Category;

class AdminFoodController extends Controller
{
    public function index(Request $request)
    {
        $query = Food::with('category');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $foods = $query->latest()->paginate(20)->appends($request->all());
        $categories = Category::where('status', 'active')->get();

        return view('admin.foods.index', compact('foods', 'categories'));
    }

    public function grid(Request $request)
    {
        $query = Food::with('category');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $foods = $query->latest()->paginate(20)->appends($request->all());
        $categories = Category::where('status', 'active')->get();

        return view('admin.foods.grid', compact('foods', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('status', 'active')->get();
        return view('admin.foods.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'status' => 'required|in:available,unavailable',
        ]);

        $data = $request->only('category_id', 'name', 'description', 'price', 'status');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('foods', 'public');
        }

        Food::create($data);

        return redirect()->route('admin.foods.index')->with('success', 'Food item created successfully.');
    }

    public function edit(Food $food)
    {
        $categories = Category::where('status', 'active')->get();
        return view('admin.foods.edit', compact('food', 'categories'));
    }

    public function update(Request $request, Food $food)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'status' => 'required|in:available,unavailable',
        ]);

        $data = $request->only('category_id', 'name', 'description', 'price', 'status');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('foods', 'public');
        }

        $food->update($data);

        return redirect()->route('admin.foods.index')->with('success', 'Food item updated successfully.');
    }

    public function destroy(Food $food)
    {
        $food->delete();
        return back()->with('success', 'Food item deleted successfully.');
    }
}
