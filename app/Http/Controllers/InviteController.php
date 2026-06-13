<?php

namespace App\Http\Controllers;

use App\Models\Invite;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserInvitation;
use Illuminate\Support\Facades\Auth;

class InviteController extends Controller
{
    /**
     * List all pending invites for the current tenant.
     */
    public function index()
    {
        $invites = Invite::where('tenant_id', Auth::user()->tenant_id)
            ->where('status', 'pending')
            ->with('inviter')
            ->latest()
            ->get();

        $roles = config('permissions.roles');
        $permissions = config('permissions.modules');

        return view('invites.index', compact('invites', 'roles', 'permissions'));
    }

    /**
     * Send a new invite.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'role' => 'required|string',
            'permissions' => 'nullable|array',
        ]);

        // Prevent duplicate invites or inviting existing users
        $existingUser = User::where('email', $request->email)
            ->where('tenant_id', Auth::user()->tenant_id)
            ->first();

        if ($existingUser) {
            return back()->withErrors(['email' => 'This user is already a member of your workspace.']);
        }

        $existingInvite = Invite::where('email', $request->email)
            ->where('tenant_id', Auth::user()->tenant_id)
            ->where('status', 'pending')
            ->first();

        if ($existingInvite) {
            return back()->withErrors(['email' => 'An invite has already been sent to this email.']);
        }

        $invite = Invite::create([
            'tenant_id' => Auth::user()->tenant_id,
            'invited_by' => Auth::id(),
            'email' => $request->email,
            'role' => UserRole::tryFrom($request->role) ?? UserRole::VIEWER,
            'token' => Str::random(40),
            'permissions' => $request->permissions,
            'status' => 'pending',
            'expires_at' => now()->addDays(7),
        ]);

        Mail::to($invite->email)->send(new UserInvitation($invite));

        return back()->with('status', 'Invitation sent to ' . $invite->email);
    }

    /**
     * Resend an existing pending invite.
     */
    public function resend($id)
    {
        $invite = Invite::where('id', $id)
            ->where('tenant_id', Auth::user()->tenant_id)
            ->firstOrFail();

        // Refresh token and expiry
        $invite->update([
            'token' => Str::random(40),
            'status' => 'pending',
            'expires_at' => now()->addDays(7),
        ]);

        Mail::to($invite->email)->send(new UserInvitation($invite->fresh()));

        return back()->with('status', 'Invitation resent to ' . $invite->email);
    }

    /**
     * Revoke a pending invite.
     */
    public function revoke($id)
    {
        $invite = Invite::where('id', $id)
            ->where('tenant_id', Auth::user()->tenant_id)
            ->where('status', 'pending')
            ->firstOrFail();

        $invite->update(['status' => 'revoked']);

        return back()->with('status', 'Invitation revoked.');
    }
}
