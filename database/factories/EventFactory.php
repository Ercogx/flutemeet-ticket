<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'is_active' => true,
            'number_adult_ticket' => rand(0, 200),
            'number_child_ticket' => rand(0, 50),
            'price_adult_ticket' => rand(0, 200),
            'price_child_ticket' => rand(0, 150),
            'start_date' => $this->faker->dateTimeBetween('now', '+1 year'),
            'end_date' => fn (array $attributes) => $this->faker->dateTimeBetween($attributes['start_date'], '+2 years'),
        ];
    }

    public function inActive(): EventFactory
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => false,
        ]);
    }
}
