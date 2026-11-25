<?php

namespace Database\Factories;

use App\Models\Bidang;
use App\Models\KlasifikasiArsip;
use App\Models\SuratMasuk;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SuratMasuk>
 */
class SuratMasukFactory extends Factory
{
    protected $model = SuratMasuk::class;

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
            'jenis_surat' => fake()->randomElement(['Surat Masuk', 'Undangan Masuk']),
            'tahun' => $tahun,
            'no_surat' => fake()->unique()->numerify('005/###/V.13/').$tahun,
            'tanggal_surat' => now()->toDateString(),
            'tanggal_terima' => now()->toDateString(),
            'pengirim' => fake()->company(),
            'perihal' => fake()->sentence(),
            'isi_ringkas' => fake()->paragraph(),
            'file_surat' => null,
            'bidang_tujuan_id' => Bidang::factory(),
            'diinput_oleh' => User::factory(),
            'status_disposisi' => 'belum',
            'keterangan' => fake()->sentence(),
        ];
    }
}
