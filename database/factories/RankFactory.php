<?php

namespace Database\Factories;

use App\Enums\UserRole;
use App\Models\Rank;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
 */
class RankFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => fake()->bothify('??'),
            'short_name' => fake()->jobTitle(),
            'alias' =>  fake()->jobTitle(),
        ];
    }
}
