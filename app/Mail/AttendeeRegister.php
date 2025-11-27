<?php

namespace App\Mail;

use App\Actions\RetrieveWpSettings;
use App\Models\OrderTicket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AttendeeRegister extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public OrderTicket $orderTicket
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            to: $this->orderTicket->email,
            subject: app(RetrieveWpSettings::class)->handle('l_event_attendee_subject'),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.attendee-register',
            with: [
                'emailContent' => $this->generateEmailContent(),
            ]
        );
    }

    private function generateEmailContent(): string
    {
        $raw = app(RetrieveWpSettings::class)->handle('l_event_attendee_email', true);

        return str_replace(
            ['{name}', '{event}', '{ticket_type}', '{event_datetime}'],
            [
                $this->orderTicket->name,
                $this->orderTicket->order->event->name,
                $this->orderTicket->ticket_type->value,
                $this->orderTicket->order->event->start_date->format('M j, Y H:i'),
            ],
            $raw
        );
    }
}
