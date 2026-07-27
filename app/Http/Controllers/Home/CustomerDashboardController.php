<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class CustomerDashboardController extends Controller
{
    public function index()
    {
        $customer = Auth::guard('customer')->user();
        $recentOrders = $customer->ecommerceOrders()->latest()->take(5)->get();

        return view('home.customer.dashboard', compact('customer', 'recentOrders'));
    }

    public function orders()
    {
        $customer = Auth::guard('customer')->user();
        $orders = $customer->ecommerceOrders()->latest()->paginate(10);

        return view('home.customer.orders', compact('customer', 'orders'));
    }

    public function savedItems()
    {
        $customer = Auth::guard('customer')->user();
        $savedItems = $customer->likedProducts()->with('product.tenant', 'product.category')->latest()->paginate(12);

        return view('home.customer.saved_items', compact('customer', 'savedItems'));
    }

    public function settings()
    {
        $customer = Auth::guard('customer')->user();
        return view('home.customer.settings', compact('customer'));
    }

    public function updateSettings(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:customers,email,' . $customer->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $customer->update($validated);

        return back()->with('status', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password:customer'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $request->user('customer')->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('status', 'Password updated successfully.');
    }
}
