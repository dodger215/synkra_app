<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Models\Invite;
use Illuminate\Support\Facades\Auth;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            $inviteToken = session('invite_token');
            $invite = null;

            if ($inviteToken) {
                $invite = Invite::where('token', $inviteToken)->first();

                if ($invite && !$invite->isPending()) {
                    session()->forget('invite_token');
                    return redirect('/')->with('error', 'This invitation has expired or is no longer valid.');
                }

                if ($invite && $invite->email !== $googleUser->getEmail()) {
                    return redirect('/')->with('error', 'Your Google email does not match the invitation email.');
                }
            }

            $user = User::where('google_id', $googleUser->getId())
                        ->orWhere('email', $googleUser->getEmail())
                        ->first();

            if (!$user) {
                if ($invite) {
                    // Invited user signing up with Google
                    $user = User::create([
                        'tenant_id' => $invite->tenant_id,
                        'name' => $googleUser->getName(),
                        'email' => $googleUser->getEmail(),
                        'google_id' => $googleUser->getId(),
                        'role' => $invite->role,
                        'permissions' => $invite->permissions,
                    ]);
                    $invite->update(['status' => 'accepted']);
                    session()->forget('invite_token');
                } else {
                    // New owner signing up with Google
                    $user = User::create([
                        'name' => $googleUser->getName(),
                        'email' => $googleUser->getEmail(),
                        'google_id' => $googleUser->getId(),
                        'role' => \App\Enums\UserRole::OWNER,
                    ]);
                }
            } else {
                // Existing user — link Google ID if missing
                if (!$user->google_id) {
                    $user->update(['google_id' => $googleUser->getId()]);
                }

                // If they arrived via invite, update their tenant/role/permissions
                if ($invite) {
                    $user->update([
                        'tenant_id' => $invite->tenant_id,
                        'role' => $invite->role,
                        'permissions' => $invite->permissions,
                    ]);
                    $invite->update(['status' => 'accepted']);
                    session()->forget('invite_token');
                }
            }

            Auth::login($user);

            return redirect()->intended('/');
            
        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Authentication failed or was canceled.');
        }
    }
}
