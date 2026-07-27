<?php

namespace App\Http\Controllers\Api\Crm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::where('tenant_id', Auth::user()->tenant_id)->latest()->paginate(20);
        return response()->json($customers);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:50',
            'company_name' => 'nullable|string|max:255',
            'customer_group' => 'nullable|string',
        ]);

        $customer = Customer::create(array_merge($validated, [
            'tenant_id' => Auth::user()->tenant_id
        ]));

        return response()->json($customer, 201);
    }

    public function show($id)
    {
        $customer = Customer::where('tenant_id', Auth::user()->tenant_id)->findOrFail($id);
        return response()->json($customer);
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::where('tenant_id', Auth::user()->tenant_id)->findOrFail($id);
        $customer->update($request->all());
        return response()->json($customer);
    }

    public function destroy($id)
    {
        $customer = Customer::where('tenant_id', Auth::user()->tenant_id)->findOrFail($id);
        $customer->delete();
        return response()->json(null, 204);
    }
}
