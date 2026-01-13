<?php

namespace Database\Factories;

use App\Enums\TicketType;
use App\Models\order;
use App\Models\OrderTicket;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class OrderTicketFactory extends Factory
{
    protected $model = OrderTicket::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'ticket_type' => $this->faker->randomElement(TicketType::cases()),

            'order_id' => Order::factory(),
        ];
    }
}
