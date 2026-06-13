<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Invite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AcceptInviteController extends Controller
{
    /**
     * Show the accept-invite page (choose Google or password).
     */
    public function show($token)
    {
        $invite = Invite::where('token', $token)->firstOrFail();

        if (!$invite->isPending()) {
            return redirect('/login')->with('error', 'This invitation has expired or is no longer valid.');
        }

        // Store token in session so the Google OAuth callback can consume it
        session(['invite_token' => $token]);

        return view('auth.accept-invite', compact('invite'));
    }

    /**
     * Accept the invite by creating a password-based account.
     */
    public function store(Request $request, $token)
    {
        $invite = Invite::where('token', $token)->firstOrFail();

        if (!$invite->isPending()) {
            return redirect('/login')->with('error', 'This invitation has expired or is no longer valid.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Check if a user with this email already exists (e.g. from another tenant)
        $user = User::where('email', $invite->email)->first();

        if ($user) {
            // Link the existing user to this tenant if they aren't already
            if ($user->tenant_id !== $invite->tenant_id) {
                $user->update([
                    'tenant_id' => $invite->tenant_id,
                    'role' => $invite->role,
                    'permissions' => $invite->permissions,
                ]);
            }
        } else {
            $user = User::create([
                'tenant_id' => $invite->tenant_id,
                'name' => $request->name,
                'email' => $invite->email,
                'password' => Hash::make($request->password),
                'role' => $invite->role,
                'permissions' => $invite->permissions,
            ]);
        }

        $invite->update(['status' => 'accepted']);

        Auth::login($user);
        session()->forget('invite_token');

        return redirect()->intended('/');
    }
}
