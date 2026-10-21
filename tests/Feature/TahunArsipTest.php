<?php

namespace Tests\Feature;

use App\Models\SuratMasuk;
use App\Models\TahunArsip;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TahunArsipTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_store_tahun()
    {
        $this->post('/tahun-arsip', ['tahun' => 2027])->assertRedirect('/login');
    }

    public function test_super_admin_can_store_tahun()
    {
        $admin = User::factory()->superAdmin()->create();

        $response = $this->actingAs($admin)->from('/dashboard')
            ->post('/tahun-arsip', ['tahun' => 2027]);

        $response->assertRedirect('/dashboard');
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('tahun_arsips', ['tahun' => 2027]);
    }

    public function test_duplicate_tahun_is_rejected()
    {
        $admin = User::factory()->superAdmin()->create();
        TahunArsip::create(['tahun' => 2026]);

        $response = $this->actingAs($admin)
            ->post('/tahun-arsip', ['tahun' => 2026]);

        $response->assertSessionHasErrors('tahun');
        $this->assertDatabaseCount('tahun_arsips', 1);
    }

    public function test_invalid_tahun_is_rejected()
    {
        $admin = User::factory()->superAdmin()->create();

        $this->actingAs($admin)->post('/tahun-arsip', ['tahun' => 1899])
            ->assertSessionHasErrors('tahun');

        $this->actingAs($admin)->post('/tahun-arsip', ['tahun' => 'abc'])
            ->assertSessionHasErrors('tahun');

        $this->assertDatabaseCount('tahun_arsips', 0);
    }

    public function test_guest_cannot_destroy_tahun()
    {
        $arsip = TahunArsip::create(['tahun' => 2028]);

        $this->delete('/tahun-arsip/2028')->assertRedirect('/login');

        $this->assertDatabaseHas('tahun_arsips', ['id' => $arsip->id]);
    }

    public function test_super_admin_can_destroy_empty_tahun()
    {
        $admin = User::factory()->superAdmin()->create();
        $arsip = TahunArsip::create(['tahun' => 2028]);

        $response = $this->actingAs($admin)->from('/dashboard')
            ->delete('/tahun-arsip/2028');

        $response->assertRedirect('/dashboard');
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('tahun_arsips', ['id' => $arsip->id]);
    }

    public function test_tahun_with_surat_cannot_be_destroyed()
    {
        $admin = User::factory()->superAdmin()->create();
        TahunArsip::create(['tahun' => 2026]);
        SuratMasuk::create([
            'no_surat'       => '900/1/SM',
            'tanggal_surat'  => '2026-03-01',
            'tanggal_terima' => '2026-03-02',
            'pengirim'       => 'Dinas PU',
            'perihal'        => 'Undangan Rapat',
            'jenis_surat'    => 'Surat Masuk',
            'tahun'          => 2026,
        ]);

        $response = $this->actingAs($admin)->from('/dashboard')
            ->delete('/tahun-arsip/2026');

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('tahun_arsips', ['tahun' => 2026]);
    }
}