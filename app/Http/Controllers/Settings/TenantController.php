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

        if (!$tenant) {
            $tenant = new \App\Models\Tenant();
            $tenantServices = collect();
        } else {
            $tenantServices = $tenant->services->keyBy('service_name');
        }

        $bannerOptions = [
            // Retail & General Shop
            'https://images.unsplash.com/photo-1441986300917-64674bd600d8?q=80&w=2070&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1472851294608-062f824d29cc?q=80&w=2070&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1555529669-e69e7aa0ba9a?q=80&w=2070&auto=format&fit=crop',

            // Food & Groceries
            'https://images.unsplash.com/photo-1542838132-92c53300491e?q=80&w=2074&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1516594798947-e65505dbb29d?q=80&w=2070&auto=format&fit=crop',

            // Kitchen & Utensils
            'https://images.unsplash.com/photo-1556911220-e15b29be8c8f?q=80&w=2070&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1590794056226-79ef3a8147e1?q=80&w=2070&auto=format&fit=crop',

            // Logistics & Supply Chain
            'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?q=80&w=2070&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1578575437130-527eed3abbec?q=80&w=2070&auto=format&fit=crop',

            // Abstract Patterns
            'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?q=80&w=1964&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1550684848-fac1c5b4e853?q=80&w=2070&auto=format&fit=crop',
        ];

        return view('settings.tenant.edit', compact('tenant', 'tenantServices', 'bannerOptions'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $tenant = $user->tenant;
        $isNewTenant = !$tenant;

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'subdomain' => [
                'nullable', 'string', 'max:100',
                Rule::unique('tenants')->ignore($tenant?->id),
            ],
            'banner_url' => ['nullable', 'string'],
            'settings' => ['nullable', 'array'],
            'services' => ['nullable', 'array'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'supply_chain_mode' => ['nullable', 'string', Rule::in(['buyer', 'supplier', 'both', 'none'])],
            'country' => ['nullable', 'string'],
            'city' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
            'landmark' => ['nullable', 'string'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
        ]);

        $settings = $validated['settings'] ?? ($tenant?->settings ?? []);
        if (isset($validated['banner_url'])) {
            $settings['banner_url'] = $validated['banner_url'];
        }

        if (!$tenant) {
            $tenant = \App\Models\Tenant::create([
                'name' => $validated['name'],
                'subdomain' => $validated['subdomain'] ?? null,
                'settings' => $settings,
                'supply_chain_mode' => $validated['supply_chain_mode'] ?? 'none',
                'country' => $validated['country'] ?? 'Ghana',
                'city' => $validated['city'] ?? null,
                'address' => $validated['address'] ?? null,
                'landmark' => $validated['landmark'] ?? null,
                'latitude' => $validated['latitude'] ?? null,
                'longitude' => $validated['longitude'] ?? null,
            ]);
            $user->update([
                'tenant_id' => $tenant->id,
                'phone_number' => $validated['phone_number'] ?? $user->phone_number,
            ]);
        } else {
            $tenant->update([
                'name' => $validated['name'],
                'subdomain' => $validated['subdomain'] ?? null,
                'settings' => $settings,
                'supply_chain_mode' => $validated['supply_chain_mode'] ?? $tenant->supply_chain_mode,
                'country' => $validated['country'] ?? $tenant->country,
                'city' => $validated['city'] ?? $tenant->city,
                'address' => $validated['address'] ?? $tenant->address,
                'landmark' => $validated['landmark'] ?? $tenant->landmark,
                'latitude' => $validated['latitude'] ?? $tenant->latitude,
                'longitude' => $validated['longitude'] ?? $tenant->longitude,
            ]);

            if ($request->filled('phone_number')) {
                $user->update(['phone_number' => $request->phone_number]);
            }
        }

        $allModules = ['ecommerce', 'pos', 'inventory', 'crm', 'marketing', 'supply_chain', 'reporting'];

        foreach ($allModules as $module) {
            // If services are not provided (e.g. from the simple registration modal),
            // activate all by default for a brand new tenant
            if (!$request->has('services') && $isNewTenant) {
                $isActive = true;
            } else {
                $isActive = isset($validated['services'][$module]['is_active']) && $validated['services'][$module]['is_active'] == '1';
            }

            $subCategory = $request->input("services.{$module}.sub_category", 'default');

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

        if ($isNewTenant) {
            return redirect()->route('settings.workspace.edit')->with('status', 'Workspace created! You can now configure your modules and tools below.');
        }

        return back()->with('status', 'Workspace settings and modules updated successfully.');
    }
}
