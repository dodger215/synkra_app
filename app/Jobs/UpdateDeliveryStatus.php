<?php

namespace App\Jobs;

use App\Models\DeliveryOrder;
use App\Services\DeliveryService;
use App\Services\SmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UpdateDeliveryStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $deliveryOrderId;

    public function __construct($deliveryOrderId)
    {
        $this->deliveryOrderId = $deliveryOrderId;
    }

    public function handle(DeliveryService $deliveryService): void
    {
        $delivery = DeliveryOrder::find($this->deliveryOrderId);
        if (!$delivery || !$delivery->external_id) return;

        // In a real implementation, you would call the provider API to get the latest status
        // For this prototype, we'll simulate a status progression or log the intent

        Log::info("Updating status for Delivery Order: {$delivery->id} ({$delivery->provider})");

        // Example: if status is 'paid', move to 'searching_courier'
        if ($delivery->status === 'paid') {
            $delivery->update(['status' => 'searching_courier']);

            // Re-dispatch itself to check again in 2 minutes
            self::dispatch($delivery->id)->delay(now()->addMinutes(2));
        }
    }
}
