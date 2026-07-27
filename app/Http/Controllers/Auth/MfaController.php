<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MfaController extends Controller
{
    public function showVerifyForm()
    {
        $user = auth()->user();

        // If social auth or already verified, skip
        if ($user->mfa_verified || $user->mfa_type === 'none') {
            return redirect()->route('dashboard');
        }

        return view('auth.verify-mfa', compact('user'));
    }

    public function verify(Request $request)
    {
        $request->validate([
            'mfa_code' => ['required', 'string', 'size:6'],
        ]);

        $user = auth()->user();

        if ($user->mfa_verified || $user->mfa_type === 'none') {
            return redirect()->route('dashboard');
        }

        if ($user->mfa_expires_at && $user->mfa_expires_at < now()) {
            return back()->with('error', 'The verification code has expired. Please request a new one.');
        }

        if ($user->mfa_code !== $request->mfa_code) {
            return back()->with('error', 'The verification code is incorrect. Please try again.');
        }

        $user->update([
            'mfa_verified' => true,
            'mfa_code' => null,
            'mfa_expires_at' => null,
        ]);

        // If they are an owner, send them to shop settings to ensure modules are configured
        if ($user->role === \App\Enums\UserRole::OWNER) {
            return redirect()->route('settings.workspace.edit')->with('status', 'Your account has been successfully verified! Please review your workspace settings and enabled modules.');
        }

        return redirect()->intended('dashboard')->with('status', 'Your account has been successfully verified!');
    }

    public function resend(Request $request)
    {
        $user = auth()->user();

        if ($user->mfa_verified || $user->mfa_type === 'none') {
            return redirect()->route('dashboard');
        }

        if ($request->has('method') && in_array($request->method, ['sms', 'email'])) {
            // Only switch to SMS if they actually have a phone number
            if ($request->method === 'sms' && empty($user->phone_number)) {
                return back()->with('error', 'You do not have a phone number on file to receive SMS.');
            }
            $user->mfa_type = $request->method;
            $user->save();
        }

        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $user->update([
            'mfa_code' => $code,
            'mfa_expires_at' => now()->addMinutes(15),
        ]);

        // Trigger SMS or Email dispatching here.
        if ($user->mfa_type === 'sms') {
            error_log("[MFA System] Resend Request - Sending SMS OTP ($code) to: {$user->phone_number}");
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'api-key' => env('ARKESEL_API_KEY', 'YOUR_ARKESEL_API_KEY'),
            ])->post('https://sms.arkesel.com/api/v2/sms/send', [
                'sender' => env('ARKESEL_SENDER_ID', 'flowexa'),
                'message' => "flowexa Security: Your new verification code is: {$code}. It expires in 15 minutes.",
                'recipients' => [$user->phone_number],
            ]);

            if (!$response->successful()) {
                error_log("[MFA System] Arkesel SMS Failed: " . $response->body());
                \Illuminate\Support\Facades\Log::error('Arkesel SMS Failed in Resend: ' . $response->body());
            } else {
                error_log("[MFA System] Arkesel SMS Sent Successfully! Response: " . $response->body());
            }
        } else {
            error_log("[MFA System] Resend Request - Dispatching Email OTP ($code) to: {$user->email}");
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

        return back()->with('status', 'A new verification code has been sent to your ' . $target . '.');
    }
}
