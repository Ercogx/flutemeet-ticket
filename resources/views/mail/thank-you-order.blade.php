@php /** @var \App\Models\Order $order */ @endphp

<x-mail::message>
# Thank You {{ $order->payer_name }} for Your Order! 🎵

We're thrilled that you've chosen to join us at the {{ $order->event->name }}!

## Your Order Details

**Total:** €{{ $order->totalPrice() }} <br>
**Ticket Number:** {{ $order->orderTickets->count() }}

---

## Need Help?

If you have any questions about your order or the event:

**Email:** <a href="mailto:flutemeet@gmail.com">flutemeet@gmail.com</a>

---

Le gach dea-ghuí, best wishes, <br>
The Cruinniú Committee
</x-mail::message>
