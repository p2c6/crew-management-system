<?php

namespace Database\Factories;

use App\Enums\UserRole;
use App\Models\Rank;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
            'code' => Str::upper(fake()->bothify('??')),
            'short_name' => fake()->jobTitle(),
            'alias' =>  fake()->jobTitle(),
        ];
    }
}
