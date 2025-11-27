<?php

namespace App\Mail;

use App\Actions\RetrieveWpSettings;
use App\Models\EventWhitelist;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WhitelistNotify extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public function __construct(public EventWhitelist $eventWhitelist)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            to: $this->eventWhitelist->email,
            subject: app(RetrieveWpSettings::class)->handle('l_event_waitlist_subject'),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.white-list-notify',
            with: [
                'emailContent' => $this->generateEmailContent(),
            ]
        );
    }

    private function generateEmailContent(): string
    {
        return app(RetrieveWpSettings::class)
            ->handle('l_event_whitelist_email', true, [
                'http://{url}' => route('whitelist.show', $this->eventWhitelist->event),
                'https://{url}' => route('whitelist.show', $this->eventWhitelist->event),
                '{url}' => route('whitelist.show', $this->eventWhitelist->event),
                '{event}' => $this->eventWhitelist->event->name,
                '{event_datetime}' => $this->eventWhitelist->event->start_date->format('M j, Y H:i'),
            ]);
    }
}
