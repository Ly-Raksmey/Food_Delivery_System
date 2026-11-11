<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Restaurant;

class AdminRestaurantController extends Controller
{
    public function index()
    {
        $restaurant = Restaurant::first();

        return view('admin.restaurant.index', compact('restaurant'));
    }

    public function update(Request $request)
    {
        $restaurant = Restaurant::first();

        if (! $restaurant) {
            $restaurant = new Restaurant();
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'description' => 'nullable|string|max:1000',
            'logo' => 'nullable|image|max:2048',
        ]);

        $restaurant->fill([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'description' => $request->description,
        ]);

        if ($request->hasFile('logo')) {
            $restaurant->logo = $request->file('logo')->store('restaurant', 'public');
        }

        $restaurant->save();

        return back()->with('success', 'Restaurant settings updated successfully.');
    }
}
