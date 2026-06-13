<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('settings.profile.edit', [
            'user' => Auth::user(),
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 'string', 'email', 'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'phone_number' => ['nullable', 'string', 'regex:/^\+?[0-9]{10,15}$/'],
            'mfa_type' => ['required', 'string', Rule::in(['email', 'sms', 'none'])],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ], [
            'phone_number.regex' => 'Please enter a valid phone number (10 to 15 digits).',
        ]);

        // If they chose SMS but didn't provide a phone number
        if ($validated['mfa_type'] === 'sms' && empty($validated['phone_number'])) {
            return back()->withErrors(['mfa_type' => 'You must provide a phone number to use SMS Verification.'])->withInput();
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone_number = $validated['phone_number'];
        $user->mfa_type = $validated['mfa_type'];

        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return back()->with('status', 'Profile updated successfully.');
    }
}
