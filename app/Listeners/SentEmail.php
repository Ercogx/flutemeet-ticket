<?php

namespace App\Listeners;

use App\Events\OrderPaid;
use App\Mail\AttendeeRegister;
use App\Mail\ThankYouOrder;
use Illuminate\Support\Facades\Mail;

class SentEmail
{
    public function handle(OrderPaid $event): void
    {
        Mail::send(new ThankYouOrder($event->order));

        foreach ($event->order->orderTickets as $orderTicket) {
            Mail::send(new AttendeeRegister($orderTicket));
        }
    }
}
