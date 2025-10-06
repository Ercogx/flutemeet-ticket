<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\EventWhitelist;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class EventWhitelistFactory extends Factory
{
    protected $model = EventWhitelist::class;

    public function definition(): array
    {
        return [
            'email' => $this->faker->unique()->safeEmail(),
            'number_adult_ticket' => $this->faker->randomNumber(),
            'number_child_ticket' => $this->faker->randomNumber(),

            'event_id' => Event::factory(),
        ];
    }
}
