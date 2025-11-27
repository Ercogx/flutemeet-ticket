<?php

namespace App\Mail;

use App\Actions\RetrieveWpSettings;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ThankYouOrder extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public Order $order
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            to: $this->order->payer_email,
            subject: app(RetrieveWpSettings::class)->handle('l_event_payer_subject'),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.thank-you-order',
            with: [
                'emailContent' => $this->generateEmailContent(),
            ]
        );
    }

    private function generateEmailContent(): string
    {
        $raw = app(RetrieveWpSettings::class)->handle('l_event_payer_email', true);

        return str_replace(
            ['{name}', '{event}', '{total}', '{numberf_of_tickets}'],
            [
                $this->order->payer_name,
                $this->order->event->name,
                $this->order->totalPrice(),
                $this->order->orderTickets()->count(),
            ],
            $raw
        );
    }
}
