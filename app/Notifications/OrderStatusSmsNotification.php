<?php

namespace App\Notifications;

use App\Models\EcommerceOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;

class OrderStatusSmsNotification extends Notification
{
    use Queueable;

    protected $order;

    public function __construct(EcommerceOrder $order)
    {
        $this->order = $order;
    }

    public function via($notifiable): array
    {
        return ['sms']; // Custom channel or direct call
    }

    public function toSms($notifiable)
    {
        $message = "Your order #" . substr($this->order->id, 0, 8) . " status: " . strtoupper($this->order->status);

        // Direct integration with Arkesel as seen in other controllers
        Http::withHeaders([
            'api-key' => config('services.arkesel.key')
        ])->post('https://sms.arkesel.com/api/v2/sms/send', [
            'sender' => config('services.arkesel.sender'),
            'message' => $message,
            'recipients' => [$notifiable->phone],
        ]);
    }
}
