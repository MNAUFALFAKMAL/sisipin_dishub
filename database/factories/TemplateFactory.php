<?php

namespace Database\Factories;

use App\Models\Template;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Template>
 */
class TemplateFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'file_path' => 'templates/'.fake()->slug.'.docx',
            'category' => fake()->word(),
            'is_active' => true,
        ];
    }
}
