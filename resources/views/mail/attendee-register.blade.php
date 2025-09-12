@php /** @var \App\Models\OrderTicket $orderTicket */ @endphp

<x-mail::message>
# Fáilte, {{ $orderTicket->name }}!

Welcome!

Your registration has been received for Cruinniú na bhFliúit 2025.

Ticket type: {{ $orderTicket->ticket_type->value }}.

You can access the full programme <a href="https://flutemeet.org/programme/">here</a>.  <br>
You will find answers to all your questions in the <a href="https://flutemeet.org/faq/">FAQ<a/> section of our website.  <br>
        You can follow our news on our Facebook page: <a href="https://www.facebook.com/cruinniunabhfliuit">Cruinniú na Bhfliúit | Facebook </a>.

If you need further information, feel free to email us at <a href="mailto:flutemeet@gmail.com">flutemeet@gmail.com</a>.<br>
Táimíd ag súil go mór le fáilte a chur romhat go mBaile Bhuirne.

We very much look forward to welcoming you to Ballyvourney in April.

Le gach dea-ghuí, best wishes, <br>
The Cruinniú Committee
</x-mail::message>
