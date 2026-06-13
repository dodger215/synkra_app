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
        return view('settings.tenant.edit', [
            'tenant' => Auth::user()->tenant,
        ]);
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
        ]);

        $tenant->update($validated);

        return back()->with('status', 'Workspace settings updated successfully.');
    }
}
