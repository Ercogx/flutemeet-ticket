<?php

namespace App\Mail;

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
    )
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            to: $this->orderTicket->email,
            subject: 'Attendee Register',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.attendee-register',
        );
    }
}
