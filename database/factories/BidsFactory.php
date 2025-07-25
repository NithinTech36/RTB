<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Slots;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bids>
 */
class BidsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'slot_id' => Slots::factory(),
            'user_id' => User::factory(),   
            'amount' => $this->faker->numberBetween(100, 1000),

        ];
    }
}
