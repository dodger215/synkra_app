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
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $tenant = Tenant::create([
            'name' => $request->tenant_name,
        ]);

        $user = User::create([
            'tenant_id' => $tenant->id,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => \App\Enums\UserRole::OWNER,
            'permissions' => $request->services ?? [], // Grant owner all selected permissions
        ]);

        if ($request->has('services')) {
            foreach ($request->services as $serviceName => $subs) {
                \App\Models\TenantService::create([
                    'tenant_id' => $tenant->id,
                    'service_name' => $serviceName,
                    'sub_category' => 'default', // Or whatever logic maps to sub_category
                    'is_active' => true,
                    'config' => $subs,
                    'activated_at' => now(),
                ]);
            }
        }

        Auth::login($user);

        return redirect()->intended('/');
    }
}
