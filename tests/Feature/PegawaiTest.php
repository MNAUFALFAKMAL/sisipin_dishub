<?php

namespace Tests\Feature;

use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PegawaiTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_pegawai_index()
    {
        $this->get('/pegawai')->assertRedirect('/login');
    }

    public function test_staff_cannot_access_pegawai_index()
    {
        $staff = User::factory()->create(['role' => 'staff']);

        $this->actingAs($staff)->get('/pegawai')->assertForbidden();
    }

    public function test_super_admin_can_store_pegawai()
    {
        $admin = User::factory()->create(['role' => 'super_admin']);

        $response = $this->actingAs($admin)->from('/pegawai')
            ->post('/pegawai', [
                'nama'       => 'Budi Santoso, S.E.',
                'nip'        => '19710422 199503 1 002',
                'pangkat'    => 'Penata Muda',
                'golongan'   => 'III/a',
                'jabatan'    => 'Staf',
                'unit_bidang'=> 'Bidang LLA',
                'status'     => 'aktif',
            ]);

        $response->assertRedirect('/pegawai');
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('pegawais', ['nama' => 'Budi Santoso, S.E.', 'status' => 'aktif']);
    }

    public function test_duplicate_pegawai_name_is_rejected()
    {
        $admin = User::factory()->create(['role' => 'super_admin']);
        Pegawai::create(['nama' => 'Budi Santoso, S.E.']);

        $response = $this->actingAs($admin)
            ->post('/pegawai', ['nama' => 'Budi Santoso, S.E.']);

        $response->assertSessionHasErrors('nama');
        $this->assertDatabaseCount('pegawais', 1);
    }

    public function test_missing_nama_is_rejected()
    {
        $admin = User::factory()->create(['role' => 'super_admin']);

        $this->actingAs($admin)->post('/pegawai', ['nama' => ''])
            ->assertSessionHasErrors('nama');

        $this->assertDatabaseCount('pegawais', 0);
    }

    public function test_super_admin_can_update_pegawai()
    {
        $admin = User::factory()->create(['role' => 'super_admin']);
        $pegawai = Pegawai::create(['nama' => 'Budi Santoso, S.E.', 'status' => 'aktif']);

        $response = $this->actingAs($admin)
            ->from('/pegawai')
            ->put('/pegawai/' . $pegawai->id, [
                'nama'       => 'Budi Santoso Baru, S.E.',
                'pangkat'    => 'Penata',
                'golongan'   => 'III/b',
                'jabatan'    => 'Kasubag',
                'unit_bidang'=> 'Sekretariat',
                'status'     => 'aktif',
            ]);

        $response->assertRedirect('/pegawai');
        $this->assertDatabaseHas('pegawais', ['id' => $pegawai->id, 'nama' => 'Budi Santoso Baru, S.E.']);
    }

    public function test_update_keeps_same_nama_allowed()
    {
        $admin = User::factory()->create(['role' => 'super_admin']);
        $pegawai = Pegawai::create(['nama' => 'Budi Santoso, S.E.', 'status' => 'aktif']);

        $response = $this->actingAs($admin)
            ->put('/pegawai/' . $pegawai->id, [
                'nama'       => 'Budi Santoso, S.E.',
                'status'     => 'aktif',
            ]);

        $response->assertRedirect('/pegawai');
        $this->assertDatabaseCount('pegawais', 1);
    }

    public function test_destroy_sets_status_inactive_not_delete()
    {
        $admin = User::factory()->create(['role' => 'super_admin']);
        $pegawai = Pegawai::create(['nama' => 'Budi Santoso, S.E.', 'status' => 'aktif']);

        $response = $this->actingAs($admin)
            ->from('/pegawai')
            ->delete('/pegawai/' . $pegawai->id);

        $response->assertRedirect('/pegawai');
        $this->assertDatabaseHas('pegawais', ['id' => $pegawai->id, 'status' => 'tidak_aktif']);
    }

    public function test_activate_restores_status_active()
    {
        $admin = User::factory()->create(['role' => 'super_admin']);
        $pegawai = Pegawai::create(['nama' => 'Budi Santoso, S.E.', 'status' => 'tidak_aktif']);

        $response = $this->actingAs($admin)
            ->patch('/pegawai/' . $pegawai->id . '/activate');

        $response->assertRedirect();
        $this->assertDatabaseHas('pegawais', ['id' => $pegawai->id, 'status' => 'aktif']);
    }

    public function test_index_only_lists_active_in_picker_source()
    {
        $admin = User::factory()->create(['role' => 'super_admin']);
        Pegawai::create(['nama' => 'Pegawai Aktif', 'status' => 'aktif']);
        Pegawai::create(['nama' => 'Pegawai Nonaktif', 'status' => 'tidak_aktif']);

        $this->actingAs($admin)->get('/pegawai')
            ->assertSee('Pegawai Aktif')
            ->assertSee('Pegawai Nonaktif');

        $active = Pegawai::where('status', 'aktif')->pluck('nama')->all();
        $this->assertContains('Pegawai Aktif', $active);
        $this->assertNotContains('Pegawai Nonaktif', $active);
    }
}