<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\TicketType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    protected $casts = [
        'status' => OrderStatus::class,
    ];

    public function orderTickets(): HasMany
    {
        return $this->hasMany(OrderTicket::class)->chaperone();
    }

    /** @return Collection<int, OrderTicket> */
    public function adultTickets(): Collection
    {
        return $this->orderTickets->where('ticket_type', TicketType::ADULT);
    }

    /** @return Collection<int, OrderTicket> */
    public function childTickets(): Collection
    {
        return $this->orderTickets->where('ticket_type', TicketType::CHILD);
    }

    public function totalPrice(): int
    {
        return $this->childTickets()->count() * $this->event->price_child_ticket
            + $this->adultTickets()->count() * $this->event->price_adult_ticket;
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function toPayPalItems(): array
    {
        return $this->orderTickets->groupBy('ticket_type')
            ->map(fn (Collection $tickets) => [
                'name' => $tickets->first()->ticket_type->value,
                'description' => 'Ticket for '. $this->event->name,
                'quantity' => $tickets->count(),
                'category' => 'DIGITAL_GOODS',
                'unit_amount' => [
                    'currency_code' => config('paypal.currency'),
                    'value' => $tickets->count() * ($tickets->first()->ticket_type === TicketType::ADULT
                        ? $this->event->price_adult_ticket
                        : $this->event->price_child_ticket),

                ]
            ])
            ->toArray();
    }
}
