<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Delivery;

class AdminDeliveryStaffController extends Controller
{
    public function index()
    {
        $deliveryStaff = User::where('role', 'delivery')
            ->with('deliveries')
            ->latest()
            ->paginate(20);

        return view('admin.delivery-staff.index', compact('deliveryStaff'));
    }

    public function create()
    {
        return view('admin.delivery-staff.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'status' => 'required|in:active,inactive',
            'profile_image' => 'nullable|image|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
            'role' => 'delivery',
            'status' => $request->status,
        ];

        if ($request->hasFile('profile_image')) {
            $data['profile_image'] = $request->file('profile_image')->store('profile_images', 'public');
        }

        User::create($data);

        return redirect()->route('admin.delivery-staff.index')->with('success', 'Delivery staff created successfully.');
    }

    public function edit($delivery_staff)
    {
        $staff = User::where('role', 'delivery')->findOrFail($delivery_staff);
        return view('admin.delivery-staff.edit', compact('staff'));
    }

    public function update(Request $request, $delivery_staff)
    {
        $delivery_staff = User::where('role', 'delivery')->findOrFail($delivery_staff);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $delivery_staff->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'status' => 'required|in:active,inactive',
            'password' => 'nullable|string|min:8',
            'profile_image' => 'nullable|image|max:2048',
        ]);

        $data = $request->only('name', 'email', 'phone', 'address', 'status');

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $data['profile_image'] = $path;
        }

        $delivery_staff->update($data);

        return redirect()->route('admin.delivery-staff.index')->with('success', 'Delivery staff updated successfully.');
    }

    public function destroy($delivery_staff)
    {
        $delivery_staff = User::where('role', 'delivery')->findOrFail($delivery_staff);
        $delivery_staff->delete();
        return back()->with('success', 'Delivery staff deleted successfully.');
    }

    public function toggleStatus($delivery_staff)
    {
        $delivery_staff = User::where('role', 'delivery')->findOrFail($delivery_staff);
        $delivery_staff->update(['status' => $delivery_staff->status === 'active' ? 'inactive' : 'active']);
        return back()->with('success', 'Delivery staff status updated.');
    }
}
