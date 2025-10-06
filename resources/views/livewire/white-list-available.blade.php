@php /** @var \App\Models\Event $event */ @endphp

<div>
    @if($event->is_whitelist_available)
        @if($eventWhitelist)
            <form class="mt-8 pt-8 border-t border-gray-200" wire:submit="checkoutTickets">
                <h3 class="text-xl font-bold text-gray-800 mb-6 text-center">
                    Available tickets
                </h3>
                <div class="grid md:grid-cols-2 gap-4 mb-8">
                    <div>
                        <label for="adultTicket" class="block text-sm font-medium text-gray-700 mb-2">Adult ticket</label>
                        <input type="number"
                               id="adultTicket"
                               wire:model="checkoutAdultTicket"
                               max="{{ $this->numberAdultTicket }}"
                               class="outline-0 form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-teal focus:border-transparent"
                               placeholder="Amount"
                               required
                        >
                    </div>

                    <div>
                        <label for="childTicket" class="block text-sm font-medium text-gray-700 mb-2">Child ticket</label>
                        <input type="number"
                               id="childTicket"
                               wire:model="checkoutChildTicket"
                               max="{{ $this->numberChildTicket }}"
                               class="outline-0 form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-teal focus:border-transparent"
                               placeholder="Amount"
                               required
                        >
                    </div>
                </div>

                <button
                    class="w-full bg-brand-orange text-white py-4 rounded-xl font-semibold text-lg transform hover:scale-105 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none shadow-lg"
                >
                    Go To Checkout
                </button>
            </form>
        @else
            <form class="mt-8 pt-8 border-t border-gray-200" wire:submit="checkAvailability">
                <h3 class="text-xl font-bold text-gray-800 mb-6 text-center">
                    Enter your email to check available tickets
                </h3>

                <div class="mb-8">
                    <input type="email"
                           wire:model="email"
                           class="outline-0 form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-teal focus:border-transparent"
                           placeholder="Email *"
                           required
                    >
                    @error('email')
                    <div class="error-message text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <button
                    class="w-full bg-brand-orange text-white py-4 rounded-xl font-semibold text-lg transform hover:scale-105 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none shadow-lg"
                >
                    Check Availability
                </button>
            </form>
        @endif
    @else
        <div class="max-w-md w-full mx-auto mt-12">

            <!-- Cancellation Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 text-center fade-in">

                <!-- Icon -->
                <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-brand-orange" fill="none" stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>

                <!-- Title -->
                <h1 class="text-2xl font-semibold text-gray-800 mb-3">Sorry, the whitelist for this event is not
                    available.</h1>

                <!-- Help -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <p class="text-sm text-gray-500">
                        Need help?
                        <a href="mailto:flutemeet@gmail.com" class="text-brand-teal hover:underline">
                            Contact Support
                        </a>
                    </p>
                </div>

            </div>

        </div>
    @endif
</div>
