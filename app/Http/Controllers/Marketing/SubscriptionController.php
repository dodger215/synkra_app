<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MarketingSubscription;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SubscriptionController extends Controller
{
    private $plans = [
        'basic' => [
            'name' => 'Starter Marketing',
            'price' => 50,
            'platforms' => ['Meta'],
            'features' => ['1 Ad Campaign', 'Standard Reporting']
        ],
        'professional' => [
            'name' => 'Professional Growth',
            'price' => 150,
            'platforms' => ['Meta', 'Google'],
            'features' => ['Unlimited Campaigns', 'Automated Rules', 'Advanced Analytics']
        ],
        'enterprise' => [
            'name' => 'Full Scale Enterprise',
            'price' => 500,
            'platforms' => ['Meta', 'Google', 'TikTok', 'X'],
            'features' => ['All Platforms', 'Dedicated Account Manager', 'Custom API Access']
        ]
    ];

    public function index()
    {
        $tenantId = Auth::user()->tenant_id;
        $subscription = MarketingSubscription::where('tenant_id', $tenantId)->where('status', 'active')->first();

        $plans = $this->plans;

        return view('marketing.subscriptions.index', compact('subscription', 'plans'));
    }

    public function subscribe(Request $request)
    {
        $request->validate(['plan' => 'required|in:basic,professional,enterprise']);

        $user = Auth::user();
        $planKey = $request->plan;
        $plan = $this->plans[$planKey];

        // Initiate Paystack Payment
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.paystack.secret_key'),
                'Content-Type' => 'application/json',
            ])->post('https://api.paystack.co/transaction/initialize', [
                'amount' => $plan['price'] * 100, // Paystack amount in pesewas/kobo
                'email' => $user->email,
                'callback_url' => route('marketing.subscriptions.callback'),
                'metadata' => [
                    'plan' => $planKey,
                    'tenant_id' => $user->tenant_id,
                    'user_id' => $user->id
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return redirect()->away($data['data']['authorization_url']);
            }

            Log::error('Paystack Initialization Failed', ['response' => $response->body()]);
            return back()->with('error', 'Could not initialize payment. Please try again.');

        } catch (\Exception $e) {
            Log::error('Paystack Exception', ['message' => $e->getMessage()]);
            return back()->with('error', 'An error occurred while processing your payment.');
        }
    }

    public function callback(Request $request)
    {
        $reference = $request->query('reference');

        if (!$reference) {
            return redirect()->route('marketing.subscriptions.index')->with('error', 'No reference supplied.');
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.paystack.secret_key'),
            ])->get("https://api.paystack.co/transaction/verify/{$reference}");

            if ($response->successful()) {
                $data = $response->json();

                if ($data['data']['status'] === 'success') {
                    $metadata = $data['data']['metadata'];
                    $planKey = $metadata['plan'];
                    $tenantId = $metadata['tenant_id'];
                    $plan = $this->plans[$planKey];

                    // Deactivate old subscription
                    MarketingSubscription::where('tenant_id', $tenantId)->update(['status' => 'cancelled']);

                    // Create new subscription
                    MarketingSubscription::create([
                        'tenant_id' => $tenantId,
                        'plan_name' => $planKey,
                        'monthly_price' => $plan['price'],
                        'features' => ['allowed_platforms' => $plan['platforms']],
                        'starts_at' => now(),
                        'ends_at' => now()->addMonth(),
                        'status' => 'active',
                    ]);

                    return redirect()->route('marketing.connections.index')->with('success', 'Subscribed to ' . ucfirst($planKey) . ' plan successfully.');
                }
            }

            return redirect()->route('marketing.subscriptions.index')->with('error', 'Payment verification failed.');

        } catch (\Exception $e) {
            Log::error('Paystack Verification Exception', ['message' => $e->getMessage()]);
            return redirect()->route('marketing.subscriptions.index')->with('error', 'An error occurred while verifying your payment.');
        }
    }
}
