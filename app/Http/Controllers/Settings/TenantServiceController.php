<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TenantService;

class TenantServiceController extends Controller
{
    public function index()
    {
        $tenant = Auth::user()->tenant;
        $services = $tenant->services;

        // Return view with available services configuration
        return view('settings.services.index', compact('tenant', 'services'));
    }

    public function update(Request $request, $id)
    {
        // Enforce owner role. We can also use middleware in routes.
        if (Auth::user()->role->value !== 'owner') {
            abort(403, 'Unauthorized action.');
        }

        $tenant = Auth::user()->tenant;
        
        $service = TenantService::where('tenant_id', $tenant->id)
            ->findOrFail($id);

        $validated = $request->validate([
            'is_active' => ['required', 'boolean'],
            'config' => ['nullable', 'array'],
        ]);

        if ($validated['is_active'] && !$service->is_active) {
            $service->activated_at = now();
        }

        $service->update([
            'is_active' => $validated['is_active'],
            'config' => $validated['config'] ?? $service->config,
        ]);

        return back()->with('status', 'Service settings updated successfully.');
    }
}
