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
        return view('auth.register', compact('services'));
    }
    public function register(Request $request)
    {
        $request->validate([
            'tenant_name' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone_number' => ['nullable', 'string', 'regex:/^\+?[0-9]{10,15}$/'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'phone_number.regex' => 'Please enter a valid phone number (10 to 15 digits).',
        ]);

        $tenant = Tenant::create([
            'name' => $request->tenant_name,
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
                'sender' => env('ARKESEL_SENDER_ID', 'SYNKRA'),
                'message' => "Welcome to Synkra! Your verification code is: {$mfaCode}. It expires in 15 minutes.",
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
