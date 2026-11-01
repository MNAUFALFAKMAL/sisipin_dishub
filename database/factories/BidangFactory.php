<?php

namespace Database\Factories;

use App\Models\Bidang;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Bidang>
 */
class BidangFactory extends Factory
{
    protected $model = Bidang::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_bidang' => fake()->unique()->words(2, true),
            'kode_bidang' => strtoupper(fake()->unique()->bothify('B??')),
            'deskripsi' => fake()->sentence(),
        ];
    }
}
