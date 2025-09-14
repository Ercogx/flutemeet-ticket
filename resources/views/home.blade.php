@php /** @var ?\App\Models\Event $event */ @endphp

@extends('layout')

@section('content')
    @if(is_null($event))
        <div class="container mx-auto px-4 py-8 max-w-4xl">
            <!-- Header -->
            <div class="text-center mb-12 animate-fade-in">
                <h1 class="text-4xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent mb-4">
                    No active events
                </h1>
            </div>
        </div>
    @else
        <div class="container mx-auto px-4 py-8 max-w-4xl">
            <!-- Header -->
            <div class="text-center mb-12 animate-fade-in">
                <img src="/img/logo.png" alt="logo" class="h-24 mx-auto mb-4">
                <h1 class="font-bold text-brand-dark mb-4" style="font-size: 4rem;">
                    {{ $event->name }}
                </h1>
                <p class="text-brand-dark text-xl">Select your tickets for an unforgettable experience</p>
                <div class="flex items-center justify-center mt-4 text-sm text-gray-500">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                              d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                              clip-rule="evenodd"></path>
                    </svg>
                    {{ $event->start_date?->format('M j, Y H:i') }} - {{ $event->end_date?->format('M j, Y H:i') }}
                </div>
            </div>

            <!-- Ticket Selection Card -->
            <form
                method="POST"
                action="{{ route('order.create', $event) }}"
                class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden animate-slide-up"
            >
                @csrf
                <div class="p-8">
                    <!-- Ticket Types -->
                    <div class="space-y-6">
                        <!-- Adult Tickets -->
                        <div
                            class="border border-gray-200 rounded-xl p-6 hover:shadow-md transition-all duration-300">
                            <div class="flex items-center flex-col md:flex-row justify-between md:mb-4">
                                <div class="mb-4 md:mb-0">
                                    <h3 class="text-xl font-bold text-brand-dark mb-1">Adult Ticket</h3>
                                    <p class="text-gray-500 text-sm text-center md:text-left">Ages 18+</p>
                                    <div class="flex items-center mt-2">
                                        <span class="text-2xl font-bold text-brand-teal">€{{ $event->price_adult_ticket }}</span>
                                        <span class="bg-gray-200 text-red-600 text-xs px-2 py-1 rounded-full ml-2 @if($event->remaining_adult_ticket > 10) hidden @endif"
                                              id="adult-remaining"
                                        >Left {{ $event->remaining_adult_ticket }}</span>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <button onclick="updateQuantity('adult', -1)"
                                            type="button"
                                            class="w-10 h-10 rounded-full border-2 border-gray-300 flex items-center justify-center hover:border-brand-teal hover:text-brand-teal transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M20 12H4"></path>
                                        </svg>
                                    </button>
                                    <span id="adult-quantity" class="text-xl font-semibold min-w-[2rem] text-center">0</span>
                                    <input type="hidden" id="adult-quantity-input" name="adult_input" value="0"/>
                                    <button onclick="updateQuantity('adult', 1)"
                                            type="button"
                                            class="w-10 h-10 rounded-full border-2 border-gray-300 flex items-center justify-center hover:border-brand-teal hover:text-brand-teal transition-colors duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Child Tickets -->
                        <div
                            class="border border-gray-200 rounded-xl p-6 hover:shadow-md transition-all duration-300">
                            <div class="flex items-center flex-col md:flex-row justify-between md:mb-4">
                                <div class="mb-4 md:mb-0">
                                    <h3 class="text-xl font-bold text-brand-dark mb-1">Child Ticket</h3>
                                    <p class="text-gray-500 text-sm text-center md:text-left">Ages 5-17</p>
                                    <div class="flex items-center mt-2">
                                        <span class="text-2xl  font-bold text-brand-teal">€{{ $event->price_child_ticket }}</span>
                                        <span class="bg-gray-200 text-red-600 text-xs px-2 py-1 rounded-full ml-2 @if($event->remaining_child_ticket > 10) hidden @endif"
                                              id="child-remaining"
                                        >Left {{ $event->remaining_child_ticket }}</span>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <button onclick="updateQuantity('child', -1)"
                                            type="button"
                                            class="w-10 h-10 rounded-full border-2 border-gray-300 flex items-center justify-center hover:border-brand-teal hover:text-brand-teal transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M20 12H4"></path>
                                        </svg>
                                    </button>
                                    <span id="child-quantity" class="text-xl font-semibold min-w-[2rem] text-center">0</span>
                                    <input type="hidden" id="child-quantity-input" name="child_input" value="0" />
                                    <button onclick="updateQuantity('child', 1)"
                                            type="button"
                                            class="w-10 h-10 rounded-full border-2 border-gray-300 flex items-center justify-center hover:border-brand-teal hover:text-brand-teal transition-colors duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div id="order-summary" class="mt-8 bg-gray-50 rounded-xl p-6 hidden">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Order Summary</h3>
                        <div class="space-y-2">
                            <div id="adult-line" class="flex justify-between items-center hidden">
                                <span class="text-gray-600">Adult Tickets × <span id="adult-count">0</span></span>
                                <span class="font-semibold" id="adult-total">€0</span>
                            </div>
                            <div id="child-line" class="flex justify-between items-center hidden">
                                <span class="text-gray-600">Child Tickets × <span id="child-count">0</span></span>
                                <span class="font-semibold" id="child-total">€0</span>
                            </div>
                            <hr class="my-3">
                            <div class="flex justify-between items-center text-lg font-bold">
                                <span>Total</span>
                                <span class="text-brand-teal" id="grand-total">€0</span>
                            </div>
                        </div>
                    </div>

                    <!-- Continue Button -->
                    <div class="mt-8">
                        <button id="continue-btn"
                                type="submit"
                                class="w-full bg-brand-orange text-white py-4 rounded-xl font-semibold text-lg transform hover:scale-105 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none shadow-lg"
                                disabled>
                            Select Tickets to Continue
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <script>
            let quantities = {
                adult: 0,
                child: 0
            };

            const prices = {
                adult: {{ $event->price_adult_ticket }},
                child: {{ $event->price_child_ticket }}
            };

            // Remaining tickets inventory
            let remainingTickets = {
                adult: {{ $event->remaining_adult_ticket }},
                child: {{ $event->remaining_child_ticket }}
            };

            function updateQuantity(type, change) {
                const newQuantity = quantities[type] + change;
                const available = remainingTickets[type];

                // Check if we can make this change
                if (change > 0 && newQuantity > available) {
                    // Show warning if trying to exceed available tickets
                    showTicketWarning(type, available);
                    return;
                }

                if (newQuantity < 0) return;

                quantities[type] = newQuantity;
                document.getElementById(`${type}-quantity`).textContent = quantities[type];
                document.getElementById(`${type}-quantity-input`).value = quantities[type];

                // Add pulse animation to the quantity
                const quantityEl = document.getElementById(`${type}-quantity`);
                quantityEl.classList.add('animate-pulse-once');
                setTimeout(() => quantityEl.classList.remove('animate-pulse-once'), 500);

                updateRemainingDisplay();
                updateOrderSummary();
                updateButtonStates();
            }

            function updateRemainingDisplay() {
                // Update remaining ticket displays
                const adultRemaining = remainingTickets.adult - quantities.adult;
                const childRemaining = remainingTickets.child - quantities.child;

                const adultRemainingEl = document.getElementById('adult-remaining');
                const childRemainingEl = document.getElementById('child-remaining');

                // Update adult remaining
                adultRemainingEl.textContent = `${adultRemaining} left`
                if (adultRemaining <= 10) {
                    adultRemainingEl.classList.remove('hidden');
                } else {
                    adultRemainingEl.classList.add('hidden');
                }

                // Update child remaining
                childRemainingEl.textContent = `${childRemaining} left`;
                if (childRemaining <= 10) {
                    childRemainingEl.classList.remove('hidden');
                } else {
                    childRemainingEl.classList.add('hidden');
                }
            }

            function updateButtonStates() {
                // Update button states based on availability
                const adultPlusBtn = document.querySelector('button[onclick="updateQuantity(\'adult\', 1)"]');
                const childPlusBtn = document.querySelector('button[onclick="updateQuantity(\'child\', 1)"]');
                const adultMinusBtn = document.querySelector('button[onclick="updateQuantity(\'adult\', -1)"]');
                const childMinusBtn = document.querySelector('button[onclick="updateQuantity(\'child\', -1)"]');

                // Disable plus buttons if no more tickets available
                if (quantities.adult >= remainingTickets.adult) {
                    adultPlusBtn.disabled = true;
                    adultPlusBtn.classList.add('opacity-50', 'cursor-not-allowed');
                } else {
                    adultPlusBtn.disabled = false;
                    adultPlusBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                }

                if (quantities.child >= remainingTickets.child) {
                    childPlusBtn.disabled = true;
                    childPlusBtn.classList.add('opacity-50', 'cursor-not-allowed');
                } else {
                    childPlusBtn.disabled = false;
                    childPlusBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                }

                // Disable minus buttons if quantity is 0
                if (quantities.adult <= 0) {
                    adultMinusBtn.disabled = true;
                    adultMinusBtn.classList.add('opacity-50', 'cursor-not-allowed');
                } else {
                    adultMinusBtn.disabled = false;
                    adultMinusBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                }

                if (quantities.child <= 0) {
                    childMinusBtn.disabled = true;
                    childMinusBtn.classList.add('opacity-50', 'cursor-not-allowed');
                } else {
                    childMinusBtn.disabled = false;
                    childMinusBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            }

            function showTicketWarning(type, available) {
                const ticketType = type.charAt(0).toUpperCase() + type.slice(1);
                const warning = document.createElement('div');
                warning.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-slide-up';
                warning.innerHTML = `
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <span>Only ${available} ${ticketType.toLowerCase()} tickets remaining!</span>
                </div>
            `;
                document.body.appendChild(warning);

                setTimeout(() => {
                    warning.remove();
                }, 3000);
            }

            function updateOrderSummary() {
                const totalTickets = quantities.adult + quantities.child;
                const orderSummary = document.getElementById('order-summary');
                const continueBtn = document.getElementById('continue-btn');

                if (totalTickets > 0) {
                    orderSummary.classList.remove('hidden');
                    continueBtn.disabled = false;
                    continueBtn.textContent = `Continue with ${totalTickets} ticket${totalTickets > 1 ? 's' : ''}`;

                    // Update adult line
                    const adultLine = document.getElementById('adult-line');
                    if (quantities.adult > 0) {
                        adultLine.classList.remove('hidden');
                        document.getElementById('adult-count').textContent = quantities.adult;
                        document.getElementById('adult-total').textContent = `€${(quantities.adult * prices.adult)}`;
                    } else {
                        adultLine.classList.add('hidden');
                    }

                    // Update child line
                    const childLine = document.getElementById('child-line');
                    if (quantities.child > 0) {
                        childLine.classList.remove('hidden');
                        document.getElementById('child-count').textContent = quantities.child;
                        document.getElementById('child-total').textContent = `€${(quantities.child * prices.child)}`;
                    } else {
                        childLine.classList.add('hidden');
                    }

                    // Update grand total
                    const grandTotal = (quantities.adult * prices.adult) + (quantities.child * prices.child);
                    document.getElementById('grand-total').textContent = `€${grandTotal}`;
                } else {
                    orderSummary.classList.add('hidden');
                    continueBtn.disabled = true;
                    continueBtn.textContent = 'Select Tickets to Continue';
                }
            }

            // Initialize
            updateRemainingDisplay();
            updateOrderSummary();
            updateButtonStates();
        </script>
    @endif
@endsection
