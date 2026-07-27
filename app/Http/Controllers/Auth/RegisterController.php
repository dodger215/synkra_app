<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        $services = config('permissions.modules');
        $bannerOptions = [
            // Retail & General Shop
            'https://images.unsplash.com/photo-1441986300917-64674bd600d8?q=80&w=2070&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1472851294608-062f824d29cc?q=80&w=2070&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1555529669-e69e7aa0ba9a?q=80&w=2070&auto=format&fit=crop',

            // Food & Groceries
            'https://images.unsplash.com/photo-1542838132-92c53300491e?q=80&w=2074&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1516594798947-e65505dbb29d?q=80&w=2070&auto=format&fit=crop',

            // Kitchen & Utensils
            'https://images.unsplash.com/photo-1556911220-e15b29be8c8f?q=80&w=2070&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1590794056226-79ef3a8147e1?q=80&w=2070&auto=format&fit=crop',

            // Logistics & Supply Chain
            'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?q=80&w=2070&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1578575437130-527eed3abbec?q=80&w=2070&auto=format&fit=crop',

            // Abstract Patterns
            'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?q=80&w=1964&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1550684848-fac1c5b4e853?q=80&w=2070&auto=format&fit=crop',
        ];
        return view('auth.register', compact('services', 'bannerOptions'));
    }
    public function register(Request $request)
    {
        $request->validate([
            'tenant_name' => ['required', 'string', 'max:255'],
            'banner_url' => ['nullable', 'string'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone_number' => ['nullable', 'string', 'regex:/^\+?[0-9]{10,15}$/'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'supply_chain_mode' => ['nullable', 'string', 'in:buyer,supplier,both,none'],
            'country' => ['required', 'string'],
            'city' => ['required', 'string'],
            'address' => ['required', 'string'],
            'landmark' => ['nullable', 'string'],
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
        ], [
            'phone_number.regex' => 'Please enter a valid phone number (10 to 15 digits).',
        ]);

        $settings = [];
        if ($request->filled('banner_url')) {
            $settings['banner_url'] = $request->banner_url;
        }

        if ($request->hasFile('logo')) {
            $settings['logo_url'] = '/storage/' . $request->file('logo')->store('logos', 'public');
        }

        $tenant = Tenant::create([
            'name' => $request->tenant_name,
            'settings' => $settings,
            'supply_chain_mode' => $request->supply_chain_mode ?? 'none',
            'country' => $request->country,
            'city' => $request->city,
            'address' => $request->address,
            'landmark' => $request->landmark,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        // Determine MFA type based on phone availability
        $mfaType = $request->filled('phone_number') ? 'sms' : 'email';
        $mfaCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $user = User::create([
            'tenant_id' => $tenant->id,
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'role' => \App\Enums\UserRole::OWNER,
            'permissions' => $request->services ?? [], // Grant owner all selected permissions
            'mfa_type' => $mfaType,
            'mfa_code' => $mfaCode,
            'mfa_expires_at' => now()->addMinutes(15),
            'mfa_verified' => false,
        ]);

        if ($request->has('services')) {
            foreach ($request->services as $serviceName => $subs) {
                \App\Models\TenantService::create([
                    'tenant_id' => $tenant->id,
                    'service_name' => $serviceName,
                    'sub_category' => 'default',
                    'is_active' => true,
                    'config' => $subs,
                    'activated_at' => now(),
                ]);
            }
        }

        // Dispatch MFA Code
        if ($mfaType === 'sms') {
            error_log("[MFA System] Registration - Sending SMS OTP ($mfaCode) to: {$user->phone_number}");
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'api-key' => env('ARKESEL_API_KEY', 'YOUR_ARKESEL_API_KEY'),
            ])->post('https://sms.arkesel.com/api/v2/sms/send', [
                'sender' => env('ARKESEL_SENDER_ID', 'flowexa'),
                'message' => "Welcome to flowexa! Your verification code is: {$mfaCode}. It expires in 15 minutes.",
                'recipients' => [$user->phone_number],
            ]);

            if (!$response->successful()) {
                error_log("[MFA System] Arkesel SMS Failed: " . $response->body());
                \Illuminate\Support\Facades\Log::error('Arkesel SMS Failed in Register: ' . $response->body());
            } else {
                error_log("[MFA System] Arkesel SMS Sent Successfully! Response: " . $response->body());
            }
        } else {
            error_log("[MFA System] Registration - Dispatching Email OTP ($mfaCode) to: {$user->email}");
            \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\MfaVerificationMail($mfaCode));
        }

        Auth::login($user);

        $target = '';
        if ($mfaType === 'sms') {
            $phone = $request->phone_number;
            $obfuscated = strlen($phone) > 5
                ? substr($phone, 0, 3) . str_repeat('*', strlen($phone) - 5) . substr($phone, -2)
                : '***';
            $target = "phone ({$obfuscated})";
        } else {
            $email = $request->email;
            $parts = explode('@', $email);
            $obfuscated = count($parts) === 2
                ? substr($parts[0], 0, 2) . str_repeat('*', max(0, strlen($parts[0]) - 2)) . '@' . $parts[1]
                : '***';
            $target = "email ({$obfuscated})";
        }

        return redirect()->route('mfa.verify')->with('status', 'Your workspace was created! A verification code has been sent to your ' . $target . '.');
    }
}
