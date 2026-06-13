<?php

namespace App\Mail;

use App\Models\Invite;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserInvitation extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Invite $invite) {}

    public function envelope(): Envelope
    {
        $inviterName = $this->invite->inviter?->name ?? 'Someone';
        $tenantName = $this->invite->tenant->name;

        return new Envelope(
            subject: "{$inviterName} invited you to join {$tenantName} on Synkra",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.invite',
            with: [
                'invite' => $this->invite,
                'inviterName' => $this->invite->inviter?->name ?? 'A team member',
                'tenantName' => $this->invite->tenant->name,
                'roleName' => ucwords(str_replace('_', ' ', $this->invite->role->value)),
                'acceptUrl' => route('invite.accept', $this->invite->token),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
