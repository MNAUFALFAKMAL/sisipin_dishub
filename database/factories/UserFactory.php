<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Peran;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'           => fake()->name(),
            'email'          => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password'       => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'is_active'      => true,
            'peran_id'       => Peran::inRandomOrder()->first()?->id ?? Peran::factory(),
            'bidang_id'      => null,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Create a Super Admin user.
     */
    public function superAdmin(): static
    {
        return $this->state(fn (array $attributes) => [
            'peran_id' => Peran::where('slug', 'super_admin')->first()?->id ?? Peran::factory()->superAdmin(),
        ]);
    }

    /**
     * Create a Tata Usaha user.
     */
    public function tataUsaha(): static
    {
        return $this->state(fn (array $attributes) => [
            'peran_id' => Peran::where('slug', 'tata_usaha')->first()?->id ?? Peran::factory()->tataUsaha(),
        ]);
    }

    /**
     * Create a Pegawai user.
     */
    public function pegawai(): static
    {
        return $this->state(fn (array $attributes) => [
            'peran_id' => Peran::where('slug', 'pegawai')->first()?->id ?? Peran::factory()->pegawai(),
        ]);
    }

    /**
     * Create a Kepala Dinas user.
     */
    public function kepalaDinas(): static
    {
        return $this->state(fn (array $attributes) => [
            'peran_id' => Peran::where('slug', 'kepala_dinas')->first()?->id ?? Peran::factory()->kepalaDinas(),
        ]);
    }

    /**
     * Create a Kepala Bidang user.
     */
    public function kepalaBidang(): static
    {
        return $this->state(fn (array $attributes) => [
            'peran_id' => Peran::where('slug', 'kepala_bidang')->first()?->id ?? Peran::factory()->kepalaBidang(),
        ]);
    }

    /**
     * Create a Staff user.
     */
    public function staff(): static
    {
        return $this->state(fn (array $attributes) => [
            'peran_id' => Peran::where('slug', 'staff')->first()?->id ?? Peran::factory()->staff(),
        ]);
    }
}