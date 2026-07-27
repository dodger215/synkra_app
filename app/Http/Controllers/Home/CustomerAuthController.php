<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class CustomerAuthController extends Controller
{
    public function showLogin()
    {
        return view('home.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('customer')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('home.customer.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('home.auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:customers,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $customer = Customer::create([
            'id' => Str::uuid(),
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            // customer_group, total_spent, etc. can use defaults or be null
        ]);

        Auth::guard('customer')->login($customer);

        return redirect()->route('home.index');
    }

    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home.index');
    }

    // Google Auth
    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->redirectUrl(url(config('services.google.customer_redirect')))
            ->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')
                ->redirectUrl(url(config('services.google.customer_redirect')))
                ->user();

            $customer = Customer::where('email', $googleUser->email)->first();

            if (!$customer) {
                $customer = Customer::create([
                    'first_name' => $googleUser->user['given_name'] ?? $googleUser->name,
                    'last_name' => $googleUser->user['family_name'] ?? '',
                    'email' => $googleUser->email,
                    'password' => Hash::make(Str::random(16)),
                    'is_active' => true,
                ]);
            }

            Auth::guard('customer')->login($customer);

            return redirect()->route('home.customer.dashboard');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Customer Google Auth Error: ' . $e->getMessage());
            return redirect()->route('home.customer.login')->withErrors(['email' => 'Google authentication failed.']);
        }
    }
}
