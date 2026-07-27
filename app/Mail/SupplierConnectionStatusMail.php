<?php

namespace App\Mail;

use App\Models\Supplier;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SupplierConnectionStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Supplier $supplier,
        public string $status,
        public ?string $reason = null
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Supplier Connection Request ' . ucfirst($this->status),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.supplier_connection_status',
        );
    }
}
