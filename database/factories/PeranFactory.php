<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Peran;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Peran>
 */
class PeranFactory extends Factory
{
    protected $model = Peran::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama'       => fake()->jobTitle(),
            'slug'       => fake()->unique()->slug(),
            'deskripsi'  => fake()->sentence(),
            'is_active'  => true,
        ];
    }

    public function superAdmin(): static
    {
        return $this->state(fn (array $attributes) => [
            'nama'      => 'Super Admin',
            'slug'      => 'super_admin',
            'deskripsi' => 'Akses penuh ke seluruh sistem',
        ]);
    }

    public function tataUsaha(): static
    {
        return $this->state(fn (array $attributes) => [
            'nama'      => 'Tata Usaha',
            'slug'      => 'tata_usaha',
            'deskripsi' => 'Mengelola surat masuk/keluar, disposisi, dan administrasi umum',
        ]);
    }

    public function pegawai(): static
    {
        return $this->state(fn (array $attributes) => [
            'nama'      => 'Pegawai',
            'slug'      => 'pegawai',
            'deskripsi' => 'Membuat dan mengelola surat serta tugas sehari-hari',
        ]);
    }

    public function kepalaDinas(): static
    {
        return $this->state(fn (array $attributes) => [
            'nama'      => 'Kepala Dinas',
            'slug'      => 'kepala_dinas',
            'deskripsi' => 'Menyetujui surat keluar dan mengakses laporan',
        ]);
    }

    public function kepalaBidang(): static
    {
        return $this->state(fn (array $attributes) => [
            'nama'      => 'Kepala Bidang',
            'slug'      => 'kepala_bidang',
            'deskripsi' => 'Menyetujui disposisi dan mengelola surat dalam bidangnya',
        ]);
    }

    public function staff(): static
    {
        return $this->state(fn (array $attributes) => [
            'nama'      => 'Staff',
            'slug'      => 'staff',
            'deskripsi' => 'Membuat surat dan melaksanakan tugas operasional',
        ]);
    }
}