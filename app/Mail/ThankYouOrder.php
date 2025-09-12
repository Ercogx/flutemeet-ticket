<?php

namespace App\Mail;

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
    )
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            to: $this->order->payer_email,
            subject: 'Thank You for Your Order! 🎵',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.thank-you-order',
        );
    }
}
