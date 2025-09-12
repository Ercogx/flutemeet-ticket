@php /** @var \App\Models\Order $order */ @endphp

@extends('layout')

@section('content')
<div class="container mx-auto px-4 py-12 max-w-2xl">

    <!-- Success Icon and Header -->
    <div class="text-center mb-8 animate-bounce-in">
        <div class="w-20 h-20 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
            </svg>
        </div>

        <h1 class="text-4xl font-bold text-gray-800 mb-3">Order Confirmed!</h1>
        <p class="text-xl text-gray-600">Thank you for your purchase</p>
    </div>

    <!-- Main Confirmation Card -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden animate-slide-up">
        <div class="p-8">
            <!-- Order Details -->
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ $order->event->name }}</h2>
                <p class="text-gray-600 mb-4 flex justify-center items-center">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                              d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                              clip-rule="evenodd"></path>
                    </svg>
                    {{ $order->event->start_date->format('M j, Y H:i') }} - {{ $order->event->end_date->format('M j, Y H:i') }}
                </p>

                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                    <div class="flex items-center justify-center mb-2">
                        <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-green-800 font-semibold">Payment Successful</span>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="border-t border-b border-gray-200 py-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Your Tickets</h3>
                <div id="ticket-summary" class="space-y-3">
                    @foreach($order->orderTickets as $key => $ticket)
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                            <div>
                                <div class="font-semibold text-gray-800">Ticket #{{ $key + 1 }} - {{ $ticket->ticket_type->value }}</div>
                                <div class="text-sm text-gray-600">{{ $ticket->name }}</div>
                                <div class="text-xs text-gray-500">{{ $ticket->email }}</div>
                            </div>
                            <div class="text-right">
                                <div class="font-semibold text-gray-800">€{{ $ticket->ticket_type === \App\Enums\TicketType::ADULT ? $order->event->price_adult_ticket : $order->event->price_child_ticket }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4 pt-4 border-t border-gray-200">
                    <div class="flex justify-between items-center text-lg font-bold">
                        <span>Total Paid:</span>
                        <span class="text-green-600" id="total-amount">€{{ $order->totalPrice() }}</span>
                    </div>
                </div>
            </div>

            <!-- Important Information -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <h4 class="font-semibold text-blue-800 mb-2 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    Important Information
                </h4>
                <ul class="text-sm text-blue-700 space-y-1">
                    <li>• Your tickets have been sent to the email addresses provided</li>
                    <li>• Please check your spam/junk folder if you don't see them</li>
                </ul>
            </div>

            <!-- Contact Information -->
            <div class="text-center text-sm text-gray-600 mb-6">
                <p class="mb-2">Questions about your order?</p>
                <p>Contact us at <a href="mailto:flutemeet@gmail.com" class="text-blue-600 hover:underline">flutemeet@gmail.com</a></p>
            </div>

        </div>
    </div>

    <!-- Back to Home -->
    <div class="text-center mt-8">
        <a href="https://flutemeet.org/" class="text-gray-600 hover:text-gray-800 transition-colors">
            ← Return To Main Site
        </a>
    </div>
</div>

<script>
    // Add some confetti effect on load
    setTimeout(() => {
        createConfetti();
    }, 500);

    function createConfetti() {
        const colors = ['#10b981', '#3b82f6', '#8b5cf6', '#f59e0b', '#ef4444'];

        for (let i = 0; i < 50; i++) {
            const confetti = document.createElement('div');
            confetti.className = 'fixed pointer-events-none z-10';
            confetti.style.left = Math.random() * 100 + 'vw';
            confetti.style.top = '-10px';
            confetti.style.width = '10px';
            confetti.style.height = '10px';
            confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
            confetti.style.animation = `confettiFall ${Math.random() * 3 + 2}s linear forwards`;

            document.body.appendChild(confetti);

            setTimeout(() => {
                confetti.remove();
            }, 5000);
        }
    }
</script>
@endsection
