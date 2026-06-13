<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TenantContact;

class TenantContactController extends Controller
{
    public function index()
    {
        $contacts = Auth::user()->tenant->contacts;
        return view('settings.contacts.index', compact('contacts'));
    }

    public function store(Request $request)
    {
        $tenant = Auth::user()->tenant;

        $validated = $request->validate([
            'contact_type' => ['nullable', 'string', 'max:50'],
            'platform' => ['required', 'string', 'max:50'],
            'handle' => ['nullable', 'string', 'max:255'],
            'url' => ['nullable', 'url', 'max:255'],
            'is_primary' => ['boolean'],
        ]);

        if ($request->is_primary) {
            // Unset previous primary contact
            $tenant->contacts()->update(['is_primary' => false]);
        }

        $tenant->contacts()->create($validated);

        return back()->with('status', 'Contact added successfully.');
    }

    public function update(Request $request, $id)
    {
        $tenant = Auth::user()->tenant;
        $contact = $tenant->contacts()->findOrFail($id);

        $validated = $request->validate([
            'contact_type' => ['nullable', 'string', 'max:50'],
            'platform' => ['required', 'string', 'max:50'],
            'handle' => ['nullable', 'string', 'max:255'],
            'url' => ['nullable', 'url', 'max:255'],
            'is_primary' => ['boolean'],
        ]);

        if ($request->is_primary && !$contact->is_primary) {
            // Unset previous primary contact
            $tenant->contacts()->update(['is_primary' => false]);
        }

        $contact->update($validated);

        return back()->with('status', 'Contact updated successfully.');
    }

    public function destroy($id)
    {
        $contact = Auth::user()->tenant->contacts()->findOrFail($id);
        $contact->delete();

        return back()->with('status', 'Contact deleted.');
    }
}
