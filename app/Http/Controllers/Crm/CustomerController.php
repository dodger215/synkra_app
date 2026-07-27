<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Mail\CustomerMessageMail;

class CustomerController extends Controller
{
    public function index()
    {
        $tenantId = Auth::user()->tenant_id;
        $customers = Customer::where('tenant_id', $tenantId)->latest()->paginate(20);
        return view('crm.customers.index', compact('customers'));
    }

    public function create()
    {
        return view('crm.customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:50',
            'company_name' => 'nullable|string|max:255',
        ]);

        Customer::create(array_merge($request->all(), [
            'tenant_id' => Auth::user()->tenant_id
        ]));

        return redirect()->route('crm.customers.index')->with('success', 'Customer created successfully.');
    }

    public function show($id)
    {
        $tenantId = Auth::user()->tenant_id;
        $customer = Customer::where('tenant_id', $tenantId)->findOrFail($id);
        return view('crm.customers.show', compact('customer'));
    }

    public function edit($id)
    {
        $tenantId = Auth::user()->tenant_id;
        $customer = Customer::where('tenant_id', $tenantId)->findOrFail($id);
        return view('crm.customers.edit', compact('customer'));
    }

    public function update(Request $request, $id)
    {
        $tenantId = Auth::user()->tenant_id;
        $customer = Customer::where('tenant_id', $tenantId)->findOrFail($id);
        $customer->update($request->all());

        return redirect()->route('crm.customers.index')->with('success', 'Customer updated successfully.');
    }

    public function destroy($id)
    {
        $tenantId = Auth::user()->tenant_id;
        $customer = Customer::where('tenant_id', $tenantId)->findOrFail($id);
        $customer->delete();

        return redirect()->route('crm.customers.index')->with('success', 'Customer deleted.');
    }

    public function sendMessage(Request $request, $id)
    {
        $request->validate([
            'subject' => 'required_if:type,email|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:email,sms,both',
        ]);

        $tenantId = Auth::user()->tenant_id;
        $customer = Customer::where('tenant_id', $tenantId)->findOrFail($id);

        $sent = false;

        if (in_array($request->type, ['email', 'both']) && $customer->email) {
            try {
                Mail::to($customer->email)->send(new \App\Mail\CustomerMessageMail($customer, $request->subject, $request->message));
                $sent = true;
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("CRM Email Failed: " . $e->getMessage());
            }
        }

        if (in_array($request->type, ['sms', 'both']) && $customer->phone) {
            try {
                $response = Http::withHeaders([
                    'api-key' => config('services.arkesel.api_key'),
                ])->post('https://sms.arkesel.com/api/v2/sms/send', [
                    'sender' => 'flowexa',
                    'message' => $request->message,
                    'recipients' => [$customer->phone],
                ]);

                if ($response->successful()) {
                    $sent = true;
                } else {
                    \Illuminate\Support\Facades\Log::error("CRM Arkesel SMS Failed: " . $response->body());
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("CRM SMS Exception: " . $e->getMessage());
            }
        }

        if (!$sent) {
            return redirect()->back()->with('error', 'Failed to send message. Please check customer contact details.');
        }

        // Log Interaction
        \App\Models\CustomerInteraction::create([
            'tenant_id' => $tenantId,
            'customer_id' => $customer->id,
            'interaction_type' => $request->type === 'both' ? 'message' : $request->type,
            'channel' => $request->type,
            'subject' => $request->subject,
            'content' => $request->message,
            'created_by' => Auth::id(),
            'resolved_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Message sent successfully.');
    }
}
