<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\TicketType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Event extends Model
{
    /** @use HasFactory<\Database\Factories\EventFactory> */
    use HasFactory;

    protected $casts = [
        'is_active' => 'boolean',

        'start_date' => 'datetime',
        'end_date' => 'datetime',

        'number_adult_ticket' => 'integer',
        'number_child_ticket' => 'integer',
        'price_adult_ticket' => 'integer',
        'price_child_ticket' => 'integer',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function orderTickets(): HasManyThrough
    {
        return $this->hasManyThrough(OrderTicket::class, Order::class);
    }

    protected function getRemainingChildTicketAttribute(): int
    {
        return $this->number_child_ticket - Order::whereEventId($this->id)
            ->join('order_tickets', 'order_tickets.order_id', '=', 'orders.id')
            ->where('order_tickets.ticket_type', TicketType::CHILD)
            ->where('orders.status', '!=',OrderStatus::REJECTED)
            ->count();
    }

    protected function getRemainingAdultTicketAttribute(): int
    {
        return $this->number_adult_ticket - Order::whereEventId($this->id)
                ->join('order_tickets', 'order_tickets.order_id', '=', 'orders.id')
                ->where('order_tickets.ticket_type', TicketType::ADULT)
                ->where('orders.status', '!=',OrderStatus::REJECTED)
                ->count();
    }
}
