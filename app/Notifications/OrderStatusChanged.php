<?php

namespace App\Notifications;

use App\Models\EcommerceOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusChanged extends Notification
{
    use Queueable;

    protected $order;

    public function __construct(EcommerceOrder $order)
    {
        $this->order = $order;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Order Status Updated - #' . substr($this->order->id, 0, 8))
            ->greeting('Hello ' . ($notifiable->first_name ?? 'Customer') . '!')
            ->line('Your order status has been updated to: ' . strtoupper($this->order->status))
            ->line('Payment Status: ' . strtoupper($this->order->payment_status))
            ->line('Fulfillment Status: ' . strtoupper($this->order->fulfillment_status))
            ->action('View Order', url('/')) // Should be store order URL
            ->line('Thank you for shopping with us!');
    }
}
