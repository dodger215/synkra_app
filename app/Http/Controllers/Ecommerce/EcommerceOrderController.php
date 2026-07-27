<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\EcommerceOrder;
use App\Models\EcommerceStore;
use App\Notifications\OrderStatusChanged;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Notifications\OrderStatusSmsNotification;

class EcommerceOrderController extends Controller
{
    // ... index, show methods ...

    public function updateStatus(Request $request, EcommerceStore $store, EcommerceOrder $order)
    {
        $this->authorizeStore($store);
        if ($order->store_id !== $store->id) abort(404);

        $validated = $request->validate([
            'status' => 'nullable|string',
            'payment_status' => 'nullable|string',
            'fulfillment_status' => 'nullable|string',
        ]);

        $order->update($validated);

        if ($order->customer) {
            $order->customer->notify(new OrderStatusChanged($order));

            if ($order->customer->phone) {
                // Trigger SMS notification
                (new OrderStatusSmsNotification($order))->toSms($order->customer);
            }
        }

        return back()->with('success', 'Order status updated.');
    }

    private function authorizeStore(EcommerceStore $store)
    {
        if ($store->tenant_id !== Auth::user()->tenant_id) {
            abort(403);
        }
    }
}
