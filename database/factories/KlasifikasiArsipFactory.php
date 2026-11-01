<?php

namespace Database\Factories;

use App\Models\KlasifikasiArsip;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<KlasifikasiArsip>
 */
class KlasifikasiArsipFactory extends Factory
{
    protected $model = KlasifikasiArsip::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kode' => fake()->unique()->numerify('###.###'),
            'nama' => fake()->sentence(3),
            'parent_id' => null,
            'retensi_aktif_tahun' => fake()->numberBetween(1, 5),
            'retensi_inaktif_tahun' => fake()->numberBetween(1, 10),
            'nasib_akhir' => fake()->randomElement(['musnah', 'permanen', 'dinilai']),
            'keamanan' => fake()->randomElement(['Biasa', 'Terbatas', 'Rahasia', 'Sangat Rahasia']),
            'hak_akses' => 'Eselon III',
            'dasar_pertimbangan' => fake()->sentence(),
            'unit_pengolah' => fake()->words(2, true),
        ];
    }
}
