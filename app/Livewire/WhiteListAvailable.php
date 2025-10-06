<?php

namespace App\Livewire;

use App\Enums\OrderStatus;
use App\Enums\TicketType;
use App\Models\Event;
use App\Models\EventWhitelist;
use App\Models\OrderTicket;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\Features\SupportRedirects\Redirector;

class WhiteListAvailable extends Component
{
    public Event $event;

    public string $email;

    public ?EventWhitelist $eventWhitelist = null;

    public int $checkoutAdultTicket;
    public int $checkoutChildTicket;

    public function checkAvailability(): void
    {
        $this->validate([
            'email' => [
                'required',
                Rule::exists('event_whitelists', 'email')
                    ->where('event_id', $this->event->id),
            ]
        ]);

        $this->eventWhitelist = $this->event->eventWhitelist()->where('email', $this->email)->first();

        $this->checkoutAdultTicket = $this->numberAdultTicket;
        $this->checkoutChildTicket = $this->numberChildTicket;
    }

    #[Computed]
    public function numberAdultTicket(): int
    {
        $purchasedTicket = OrderTicket::whereRelation('order', function (Builder $query) {
            $query->where('event_whitelist_id', $this->eventWhitelist->id)
                ->where('status', OrderStatus::PAID);
        })
            ->where('ticket_type', TicketType::ADULT)
            ->count();

        return $this->eventWhitelist->number_adult_ticket - $purchasedTicket;
    }

    #[Computed]
    public function numberChildTicket(): int
    {
        $purchasedTicket = OrderTicket::whereRelation('order', function (Builder $query) {
            $query->where('event_whitelist_id', $this->eventWhitelist->id)
                ->where('status', OrderStatus::PAID);
        })
            ->where('ticket_type', TicketType::CHILD)
            ->count();

        return $this->eventWhitelist->number_child_ticket - $purchasedTicket;
    }

    public function checkoutTickets(): RedirectResponse|Redirector
    {
        if ($this->checkoutChildTicket > $this->numberChildTicket || $this->checkoutAdultTicket > $this->numberAdultTicket) {
            return to_route('whitelist.show', $this->event);
        }

        $order = $this->event->orders()->create([
            'event_whitelist_id' => $this->eventWhitelist->id,
            'session' => session()->getId(),
            'status' => OrderStatus::PENDING,
        ]);

        $adult = array_fill(0, $this->checkoutAdultTicket, ['ticket_type' => TicketType::ADULT]);
        $child = array_fill(0, $this->checkoutChildTicket, ['ticket_type' => TicketType::CHILD]);

        $order->orderTickets()->createMany($adult);
        $order->orderTickets()->createMany($child);

        return to_route('checkout.index');
    }

    public function render(): View
    {
        return view('livewire.white-list-available');
    }
}
