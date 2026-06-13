<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            if (!$user->mfa_verified && $user->mfa_type !== 'none') {
                $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
                $user->update([
                    'mfa_code' => $code,
                    'mfa_expires_at' => now()->addMinutes(15),
                ]);

                if ($user->mfa_type === 'sms') {
                    error_log("[MFA System] Intercept Login - Sending SMS OTP ($code) to: {$user->phone_number}");
                    $response = \Illuminate\Support\Facades\Http::withHeaders([
                        'api-key' => env('ARKESEL_API_KEY', 'YOUR_ARKESEL_API_KEY'),
                    ])->post('https://sms.arkesel.com/api/v2/sms/send', [
                        'sender' => env('ARKESEL_SENDER_ID', 'SYNKRA'),
                        'message' => "Synkra Security: Your verification code is: {$code}. It expires in 15 minutes.",
                        'recipients' => [$user->phone_number],
                    ]);

                    if (!$response->successful()) {
                        error_log("[MFA System] Arkesel SMS Failed: " . $response->body());
                        \Illuminate\Support\Facades\Log::error('Arkesel SMS Failed in Login: ' . $response->body());
                    } else {
                        error_log("[MFA System] Arkesel SMS Sent Successfully! Response: " . $response->body());
                    }
                } else {
                    error_log("[MFA System] Intercept Login - Dispatching Email OTP ($code) to: {$user->email}");
                    \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\MfaVerificationMail($code));
                }

                $target = '';
                if ($user->mfa_type === 'sms') {
                    $phone = $user->phone_number;
                    $obfuscated = strlen($phone) > 5 
                        ? substr($phone, 0, 3) . str_repeat('*', strlen($phone) - 5) . substr($phone, -2)
                        : '***';
                    $target = "phone ({$obfuscated})";
                } else {
                    $email = $user->email;
                    $parts = explode('@', $email);
                    $obfuscated = count($parts) === 2 
                        ? substr($parts[0], 0, 2) . str_repeat('*', max(0, strlen($parts[0]) - 2)) . '@' . $parts[1]
                        : '***';
                    $target = "email ({$obfuscated})";
                }

                return redirect()->route('mfa.verify')->with('status', 'Please verify your account to continue. A fresh verification code has been sent to your ' . $target . '.');
            }

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
