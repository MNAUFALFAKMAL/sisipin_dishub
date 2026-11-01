<?php

namespace Database\Factories;

use App\Models\Bidang;
use App\Models\KlasifikasiArsip;
use App\Models\SuratKeluar;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SuratKeluar>
 */
class SuratKeluarFactory extends Factory
{
    protected $model = SuratKeluar::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tahun = (int) date('Y');

        return [
            'no_agenda' => (string) fake()->unique()->numberBetween(1, 9999),
            'klasifikasi_id' => KlasifikasiArsip::factory(),
            'jenis_surat' => fake()->randomElement(['SPT', 'SPPD', 'SK', 'Undangan']),
            'tahun' => $tahun,
            'no_surat' => fake()->unique()->numerify('800/###/V.13/').$tahun,
            'tanggal_surat' => now()->toDateString(),
            'tanggal_terima' => now()->toDateString(),
            'tujuan' => fake()->company(),
            'perihal' => fake()->sentence(),
            'isi_ringkas' => fake()->paragraph(),
            'file_surat' => null,
            'bidang_id' => Bidang::factory(),
            'dibuat_oleh' => User::factory(),
            'keterangan' => fake()->sentence(),
        ];
    }
}
