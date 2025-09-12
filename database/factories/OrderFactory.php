<?php

namespace Database\Factories;

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
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'status' => $this->faker->word(),

            'event_id' => Event::factory(),
        ];
    }
}
