<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\EcommerceStore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CustomerAuthController extends Controller
{
    public function showLogin(EcommerceStore $store)
    {
        return view('ecommerce.auth.login', compact('store'));
    }

    public function login(Request $request, EcommerceStore $store)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('customer')->attempt($credentials + ['tenant_id' => $store->tenant_id])) {
            $request->session()->regenerate();
            return redirect()->intended(route('ecommerce.stores.show', $store->id));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegister(EcommerceStore $store)
    {
        return view('ecommerce.auth.register', compact('store'));
    }

    public function register(Request $request, EcommerceStore $store)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:customers,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $customer = Customer::create([
            'id' => Str::uuid(),
            'tenant_id' => $store->tenant_id,
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        Auth::guard('customer')->login($customer);

        return redirect()->route('ecommerce.stores.show', $store->id);
    }

    public function logout(Request $request, EcommerceStore $store)
    {
        Auth::guard('customer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('ecommerce.stores.show', $store->id);
    }
}
