@php /** @var \App\Models\Order $order */ @endphp

@extends('layout')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-6xl">
        <!-- Header -->
        <div class="text-center mb-8 animate-fade-in">
            <div class="flex items-center justify-center mb-4">
                <a href="{{ route('home') }}" class="mr-4 p-2 rounded-full hover:bg-gray-100 transition-colors">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                    Checkout
                </h1>
            </div>
            <p class="text-gray-600">Complete your ticket purchase for {{ $order->event->name }}</p>
        </div>

        <div class="max-w-md mx-auto bg-white shadow-xl rounded-2xl p-6 mb-8  animate-slide-up">
            <!-- Timer strip with inline time -->
            <div class="flex items-center gap-3">
                <!-- Progress bar -->
                <div class="flex-1 bg-gray-200 rounded-full h-3 overflow-hidden">
                    <div id="progress" class="bg-indigo-600 h-3 rounded-full" style="width: 100%;"></div>
                </div>

                <!-- Time text -->
                <div id="time" class="text-sm font-medium text-gray-700 w-12 text-right">05:00</div>
            </div>
        </div>

        <form id="checkout-form" class="grid grid-cols-1 lg:grid-cols-5 gap-8">
            <!-- Main Form -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden animate-slide-up">
                    <div class="p-8">
                        <h2 class="text-2xl font-bold text-gray-800">Ticket Information</h2>

                        <!-- Ticket Holder Forms -->
                        <div id="ticket-holders-container">
                            @foreach($order->orderTickets as $key => $ticket)
                                <h4 class="text-md font-semibold text-gray-800 my-4 flex items-center">
                                    <span class="{{ $ticket->ticket_type === \App\Enums\TicketType::ADULT ? 'bg-indigo-500' : 'bg-purple-500' }} text-white text-xs px-2 py-1 rounded-full mr-2">
                                        {{ $ticket->ticket_type->value }}
                                    </span>

                                    Attendee #{{ $key + 1 }}
                                </h4>
                                <input type="hidden" name="ticket[{{ $key }}][id]" value="{{ $ticket->id }}">
                                @error('ticket.'.$key.'.id')
                                    <div class="error-message text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="ticket-{{ $key }}-name" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                                        <input type="text"
                                               id="ticket-{{ $key }}-name"
                                               name="ticket[{{ $key }}][name]"
                                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                               placeholder="Enter ticket holder's name"
                                               required
                                        >
                                        @error('ticket.'.$key.'.name')
                                            <div class="error-message text-red-500 text-sm mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="ticket-{{ $key  }}-email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                                        <input type="email"
                                               id="ticket-{{ $key }}-email"
                                               name="ticket[{{ $key }}][email]"
                                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                               placeholder="Enter ticket holder's email"
                                               required
                                        >
                                        @error('ticket.'.$key.'.email')
                                            <div class="error-message text-red-500 text-sm mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <!-- Payer Information -->
                        <div class="mt-8 pt-8 border-t border-gray-200">
                            <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                </svg>
                                Payer Information
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="payer-name" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                                    <input type="text" id="payer-name" name="payer_name"
                                           class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                           placeholder="Enter payer's full name" required>
                                    <div class="error-message hidden text-red-500 text-sm mt-1"></div>
                                </div>

                                <div>
                                    <label for="payer-email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                                    <input type="email" id="payer-email" name="payer_email"
                                           class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                           placeholder="Enter payer's email" required>
                                    <div class="error-message hidden text-red-500 text-sm mt-1"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary & Payment -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden animate-slide-up sticky top-8">
                    <!-- Order Summary -->
                    <div class="p-6 bg-gray-50">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Order Summary</h3>
                        <div id="order-summary-details">
                            <div class="space-y-2">
                                @if($order->adultTickets()->count() > 0)
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600">Adult Tickets × {{ $order->adultTickets()->count() }}</span>
                                        <span class="font-semibold">€{{ $order->adultTickets()->count() * $order->event->price_adult_ticket }}</span>
                                    </div>
                                @endif
                                @if($order->childTickets()->count() > 0)
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600">Child Tickets × {{ $order->childTickets()->count() }}</span>
                                        <span class="font-semibold">€{{ $order->childTickets()->count() * $order->event->price_child_ticket }}</span>
                                    </div>
                                @endif
                                <div class="border-t border-gray-300 pt-2 mt-2">
                                    <div class="flex justify-between items-center text-lg font-bold">
                                        <span>Total</span>
                                        <span class="text-indigo-600">€{{ $order->totalPrice() }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4 text-center">
                                <div class="text-sm text-gray-500">Total Tickets: {{ $order->adultTickets()->count() + $order->childTickets()->count() }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Section -->
                    <div class="p-6">
                        <!-- PayPal Button Container -->
                        <div id="paypal-button-container"></div>
                        <!-- Security Info -->
                        <div class="text-center text-xs text-gray-500 mt-4">
                            <div class="flex items-center justify-center mb-2">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                </svg>
                                Secure SSL Encryption
                            </div>
                            <p>Your payment information is protected</p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script src="https://www.paypal.com/sdk/js?client-id={{ config('paypal.mode') === 'sandbox' ? config('paypal.sandbox.client_id') : config('paypal.live.client_id') }}&currency=EUR"></script>
    <script>
        const totalTime = 300;
        let endTime = {{ $order->created_at->addMinutes(5)->getTimestampMs() }};

        const timeDisplay = document.getElementById("time");
        const progressBar = document.getElementById("progress");

        function updateTimer() {
            const now = Date.now();
            let timeLeft = Math.max(0, Math.floor((endTime - now) / 1000));

            // Format MM:SS
            const minutes = String(Math.floor(timeLeft / 60)).padStart(2, '0');
            const seconds = String(timeLeft % 60).padStart(2, '0');
            timeDisplay.textContent = `${minutes}:${seconds}`;

            // Progress %
            const percent = (timeLeft / totalTime) * 100;
            progressBar.style.width = percent + "%";

            if (timeLeft <= 0) {
                clearInterval(timer);
                window.location.replace("{{ route('home') }}");
            }
        }

        // Start timer
        updateTimer();
        const timer = setInterval(updateTimer, 1000);

        paypal.Buttons({
            onInit: function(data, actions) {
                const form = document.getElementById('checkout-form');
                const inputs = form.querySelectorAll('input[type="text"], input[type="email"], textarea, select');

                // Start disabled
                actions.disable();

                function update() {
                    if (form.checkValidity()) {
                        actions.enable();
                    } else {
                        actions.disable();
                    }
                }

                // Listen to inputs directly
                inputs.forEach(inp => {
                    inp.addEventListener('input', update);
                    inp.addEventListener('change', update);
                    inp.addEventListener('blur', update);
                });

                // Prevent native submit by Enter
                form.addEventListener('keydown', e => {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        form.reportValidity();
                        update();
                    }
                });

                // Initial check (in case prefilled)
                update();
            },
            onClick: function(data, actions) {
                let form = document.getElementById('checkout-form');

                if (!form.checkValidity()) {
                    form.reportValidity(); // show browser validation messages
                    return actions.reject(); // block PayPal flow
                }

                return actions.resolve(); // validation passed
            },
            createOrder: function(data, actions) {
                let formData = new FormData(document.getElementById('checkout-form'));

                return fetch('{{ route('checkout.submitOrder', $order) }}', {
                    method: 'post',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                    .then(res => res.json())
                    .then(order => order.id);
            },
            onApprove: function(data, actions) {
                return fetch('{{ route('checkout.captureOrder') }}/' + data.orderID, {
                    method: 'post',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }).then(res => res.json())
                    .then(details => {
                        window.location.replace(details.redirect);
                    });
            },
            onError: function(err) {
                console.error('PayPal error:', err);
                alert('An error occurred with PayPal. Please try again.');
            }

        }).render('#paypal-button-container');
    </script>
@endsection
