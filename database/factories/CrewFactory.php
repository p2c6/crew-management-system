<?php

namespace Database\Factories;

use App\Models\Rank;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Crew>
 */
class CrewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'rank_id' => Rank::inRandomOrder()->value('id'),
            'first_name' => fake()->firstName(),
            'middle_name' =>  fake()->firstName(),
            'last_name' =>  fake()->lastName(),
            'address' => fake()->address(),
            'birth_date' => fake()->date()
        ];
    }
}
