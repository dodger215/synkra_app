<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TenantController extends Controller
{
    public function edit()
    {
        $tenant = Auth::user()->tenant;
        $tenantServices = $tenant->services->keyBy('service_name');

        return view('settings.tenant.edit', compact('tenant', 'tenantServices'));
    }

    public function update(Request $request)
    {
        $tenant = Auth::user()->tenant;

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'subdomain' => [
                'nullable', 'string', 'max:100',
                Rule::unique('tenants')->ignore($tenant->id),
            ],
            'settings' => ['nullable', 'array'],
            'services' => ['nullable', 'array'],
        ]);

        $tenant->update([
            'name' => $validated['name'],
            'subdomain' => $validated['subdomain'] ?? null,
            'settings' => $validated['settings'] ?? null,
        ]);

        $allModules = ['ecommerce', 'pos', 'inventory', 'crm', 'marketing', 'supply_chain', 'reporting'];
        
        foreach ($allModules as $module) {
            $isActive = isset($validated['services'][$module]['is_active']) && $validated['services'][$module]['is_active'] == '1';
            $subCategory = $request->input("services.{$module}.sub_category", null);
            
            $service = $tenant->services()->where('service_name', $module)->first();
            if ($service) {
                $service->update([
                    'is_active' => $isActive,
                    'sub_category' => $subCategory,
                    'activated_at' => ($isActive && !$service->is_active) ? now() : $service->activated_at,
                ]);
            } else {
                $tenant->services()->create([
                    'service_name' => $module,
                    'is_active' => $isActive,
                    'sub_category' => $subCategory,
                    'activated_at' => $isActive ? now() : null
                ]);
            }
        }

        return back()->with('status', 'Workspace settings and modules updated successfully.');
    }
}
