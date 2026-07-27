<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\EcommerceStore;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class EcommerceStoreController extends Controller
{
    public function index()
    {
        $stores = EcommerceStore::where('tenant_id', Auth::user()->tenant_id)->get();
        return view('ecommerce.stores.index', compact('stores'));
    }

    public function create()
    {
        return view('ecommerce.stores.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'store_name' => 'required|string|max:255',
            'domain' => 'nullable|string|max:255|unique:ecommerce_stores,domain',
            'primary_color' => 'nullable|string|max:7',
            'secondary_color' => 'nullable|string|max:7',
            'currency' => 'required|string|max:3',
        ]);

        $store = EcommerceStore::create([
            'id' => Str::uuid(),
            'tenant_id' => Auth::user()->tenant_id,
            'store_name' => $validated['store_name'],
            'domain' => $validated['domain'],
            'primary_color' => $validated['primary_color'] ?? '#f97316',
            'secondary_color' => $validated['secondary_color'] ?? '#1e293b',
            'currency' => $validated['currency'],
        ]);

        return redirect()->route('ecommerce.stores.show', $store->id)->with('success', 'Store created successfully.');
    }

    public function show(EcommerceStore $store)
    {
        $this->authorizeStore($store);
        return view('ecommerce.stores.show', compact('store'));
    }

    public function edit(EcommerceStore $store)
    {
        $this->authorizeStore($store);
        return view('ecommerce.stores.edit', compact('store'));
    }

    public function update(Request $request, EcommerceStore $store)
    {
        $this->authorizeStore($store);

        $validated = $request->validate([
            'store_name' => 'required|string|max:255',
            'domain' => 'nullable|string|max:255|unique:ecommerce_stores,domain,' . $store->id,
            'primary_color' => 'nullable|string|max:7',
            'secondary_color' => 'nullable|string|max:7',
            'currency' => 'required|string|max:3',
            'is_published' => 'boolean',
        ]);

        $store->update($validated);

        return redirect()->route('ecommerce.stores.index')->with('success', 'Store updated successfully.');
    }

    public function destroy(EcommerceStore $store)
    {
        $this->authorizeStore($store);
        $store->delete();
        return redirect()->route('ecommerce.stores.index')->with('success', 'Store deleted successfully.');
    }

    private function authorizeStore(EcommerceStore $store)
    {
        if ($store->tenant_id !== Auth::user()->tenant_id) {
            abort(403);
        }
    }
}
