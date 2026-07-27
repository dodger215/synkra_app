<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invite;
use Illuminate\Support\Facades\Auth;

class InviteController extends Controller
{
    public function index()
    {
        $invites = Invite::where('tenant_id', Auth::user()->tenant_id)->latest()->get();
        return response()->json($invites);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:users,email',
            'role' => 'required|string',
        ]);

        $invite = Invite::create([
            'tenant_id' => Auth::user()->tenant_id,
            'email' => $validated['email'],
            'role' => $validated['role'],
            'token' => bin2hex(random_bytes(32)),
            'expires_at' => now()->addDays(7),
        ]);

        // In real app, send email here

        return response()->json($invite, 201);
    }
}
