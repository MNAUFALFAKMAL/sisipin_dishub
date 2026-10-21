<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_access_users_index()
    {
        $superAdmin = User::factory()->superAdmin()->create();

        $response = $this->actingAs($superAdmin)->get('/pengguna');
        $response->assertStatus(200);
    }

    public function test_tata_usaha_cannot_access_users_index()
    {
        $tataUsaha = User::factory()->tataUsaha()->create();

        $response = $this->actingAs($tataUsaha)->get('/pengguna');
        $response->assertStatus(403);
    }

    public function test_pegawai_cannot_access_users_index()
    {
        $pegawai = User::factory()->pegawai()->create();

        $response = $this->actingAs($pegawai)->get('/pengguna');
        $response->assertStatus(403);
    }

    public function test_super_admin_can_create_user()
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $peranTataUsaha = \App\Models\Peran::where('slug', 'tata_usaha')->first();

        $response = $this->actingAs($superAdmin)->post('/pengguna', [
            'name'      => 'User Baru',
            'username'  => 'userbaru@test.com',
            'password'  => 'password123',
            'password_confirmation' => 'password123',
            'peran_id'  => $peranTataUsaha->id,
            'bidang_id' => null,
            'is_active' => true,
        ]);

        $response->assertRedirect('/pengguna');
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('users', ['email' => 'userbaru@test.com']);
    }

    public function test_super_admin_can_edit_user()
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $user = User::factory()->pegawai()->create();
        $peranTataUsaha = \App\Models\Peran::where('slug', 'tata_usaha')->first();

        $response = $this->actingAs($superAdmin)->put('/pengguna/' . $user->id, [
            'name'      => 'User Diupdate',
            'username'  => $user->email,
            'peran_id'  => $peranTataUsaha->id,
            'bidang_id' => null,
            'is_active' => true,
        ]);

        $response->assertRedirect('/pengguna');
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'User Diupdate']);
    }

    public function test_super_admin_can_change_user_role()
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $user = User::factory()->pegawai()->create();
        $peranTataUsaha = \App\Models\Peran::where('slug', 'tata_usaha')->first();

        $response = $this->actingAs($superAdmin)->put('/pengguna/' . $user->id, [
            'name'      => $user->name,
            'username'  => $user->email,
            'peran_id'  => $peranTataUsaha->id,
            'bidang_id' => null,
            'is_active' => true,
        ]);

        $response->assertRedirect('/pengguna');
        $user->refresh();
        $this->assertEquals($peranTataUsaha->id, $user->peran_id);
    }

    public function test_tata_usaha_cannot_change_user_role()
    {
        $tataUsaha = User::factory()->tataUsaha()->create();
        $user = User::factory()->pegawai()->create();
        $peranStaff = \App\Models\Peran::where('slug', 'staff')->first();

        $response = $this->actingAs($tataUsaha)->put('/pengguna/' . $user->id, [
            'name'      => $user->name,
            'username'  => $user->email,
            'peran_id'  => $peranStaff->id,
            'bidang_id' => null,
            'is_active' => true,
        ]);

        // Should be forbidden since tata_usaha doesn't have role:super_admin
        $response->assertStatus(403);
    }

    public function test_super_admin_can_deactivate_user()
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $user = User::factory()->pegawai()->create();

        $response = $this->actingAs($superAdmin)->delete('/pengguna/' . $user->id);

        $response->assertRedirect('/pengguna');
        $response->assertSessionHas('success');
        $user->refresh();
        $this->assertFalse($user->is_active);
    }

    public function test_super_admin_cannot_deactivate_self()
    {
        $superAdmin = User::factory()->superAdmin()->create();

        $response = $this->actingAs($superAdmin)->delete('/pengguna/' . $superAdmin->id);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $superAdmin->refresh();
        $this->assertTrue($superAdmin->is_active);
    }

    public function test_super_admin_cannot_deactivate_other_super_admin()
    {
        $superAdmin1 = User::factory()->superAdmin()->create();
        $superAdmin2 = User::factory()->superAdmin()->create();

        $response = $this->actingAs($superAdmin1)->delete('/pengguna/' . $superAdmin2->id);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $superAdmin2->refresh();
        $this->assertTrue($superAdmin2->is_active);
    }

    public function test_super_admin_can_activate_user()
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $user = User::factory()->pegawai()->create(['is_active' => false]);

        $response = $this->actingAs($superAdmin)->patch('/pengguna/' . $user->id . '/activate');

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $user->refresh();
        $this->assertTrue($user->is_active);
    }

    public function test_user_can_update_own_profile_not_role()
    {
        $user = User::factory()->pegawai()->create();
        $peranStaff = \App\Models\Peran::where('slug', 'staff')->first();

        // User can update their own name and email but not role
        $response = $this->actingAs($user)->put('/pengguna/' . $user->id, [
            'name'      => 'Nama Baru',
            'username'  => 'newemail@test.com',
            'peran_id'  => $peranStaff->id, // Should be ignored
            'bidang_id' => null,
            'is_active' => true,
        ]);

        $response->assertRedirect('/pengguna');
        $user->refresh();
        $this->assertEquals('Nama Baru', $user->name);
        $this->assertEquals('newemail@test.com', $user->email);
        // Role should not change (pegawai remains pegawai)
        $this->assertEquals('pegawai', $user->peran?->slug);
    }
}