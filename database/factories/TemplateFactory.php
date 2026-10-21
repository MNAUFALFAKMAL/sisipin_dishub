<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Template>
 */
class TemplateFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'file_path' => 'templates/' . fake()->slug . '.docx',
            'category' => fake()->word(),
            'is_active' => true,
        ];
    }
}
