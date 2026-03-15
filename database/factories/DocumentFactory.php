<?php

namespace Database\Factories;

use App\Models\Crew;
use App\Models\DocumentType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Document>
 */
class DocumentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fileName = fake()->uuid() . '.pdf';

        return [
            'crew_id' => Crew::inRandomOrder()->value('id'),
            'document_type_id' => DocumentType::inRandomOrder()->value('id'),
            'user_id' => User::inRandomOrder()->value('id'),
            'file_name' =>  $fileName,
            'file_path' => 'documents/' . $fileName,
            'code' =>  Str::upper(fake()->bothify('???')),
            'issued_date' => fake()->date(),
            'expiry_date' => fake()->date(),
        ];
    }
}
