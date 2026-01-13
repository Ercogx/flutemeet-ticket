<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Models\Event;
use App\Models\order;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class OrderFactory extends Factory
{
    protected $model = order::class;

    public function definition()
    {
        return [
            'session' => $this->faker->uuid(),
            'payer_name' => $this->faker->name(),
            'payer_email' => $this->faker->unique()->safeEmail(),
            'status' => OrderStatus::PAID,

            'event_id' => Event::factory(),
        ];
    }
}
