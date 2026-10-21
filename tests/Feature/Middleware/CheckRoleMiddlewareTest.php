<?php

namespace Tests\Feature\Middleware;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckRoleMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_access_super_admin_route()
    {
        $superAdmin = User::factory()->superAdmin()->create();

        $response = $this->actingAs($superAdmin)->get('/admin');
        $response->assertStatus(200);
    }

    public function test_tata_usaha_cannot_access_super_admin_route()
    {
        $tataUsaha = User::factory()->tataUsaha()->create();

        $response = $this->actingAs($tataUsaha)->get('/admin');
        $response->assertStatus(403);
    }

    public function test_pegawai_cannot_access_super_admin_route()
    {
        $pegawai = User::factory()->pegawai()->create();

        $response = $this->actingAs($pegawai)->get('/admin');
        $response->assertStatus(403);
    }

    public function test_tata_usaha_can_access_tata_usaha_route()
    {
        $tataUsaha = User::factory()->tataUsaha()->create();

        $response = $this->actingAs($tataUsaha)->get('/tata-usaha');
        $response->assertStatus(200);
    }

    public function test_super_admin_cannot_access_tata_usaha_route()
    {
        $superAdmin = User::factory()->superAdmin()->create();

        $response = $this->actingAs($superAdmin)->get('/tata-usaha');
        $response->assertStatus(403);
    }

    public function test_pegawai_can_access_pegawai_route()
    {
        $pegawai = User::factory()->pegawai()->create();

        $response = $this->actingAs($pegawai)->get('/pegawai');
        $response->assertStatus(200);
    }

    public function test_super_admin_cannot_access_pegawai_route()
    {
        $superAdmin = User::factory()->superAdmin()->create();

        $response = $this->actingAs($superAdmin)->get('/pegawai');
        $response->assertStatus(403);
    }

    public function test_kepala_dinas_can_access_kepala_dinas_route()
    {
        $kepalaDinas = User::factory()->kepalaDinas()->create();

        $response = $this->actingAs($kepalaDinas)->get('/kepala-dinas');
        $response->assertStatus(200);
    }

    public function test_kepala_bidang_can_access_kepala_bidang_route()
    {
        $kepalaBidang = User::factory()->kepalaBidang()->create();

        $response = $this->actingAs($kepalaBidang)->get('/kepala-bidang');
        $response->assertStatus(200);
    }

    public function test_guest_redirected_to_login()
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/login');
    }

    public function test_inactive_user_cannot_access_protected_route()
    {
        $user = User::factory()->superAdmin()->create(['is_active' => false]);

        $response = $this->actingAs($user)->get('/admin');
        $response->assertRedirect('/login');
    }

    public function test_multiple_roles_allowed_in_middleware()
    {
        // Route with role:super_admin,tata_usaha should allow both
        $superAdmin = User::factory()->superAdmin()->create();
        $tataUsaha = User::factory()->tataUsaha()->create();

        $response1 = $this->actingAs($superAdmin)->get('/klasifikasi-arsip');
        $response1->assertStatus(200);

        $response2 = $this->actingAs($tataUsaha)->get('/klasifikasi-arsip');
        $response2->assertStatus(200);
    }

    public function test_staff_cannot_access_klasifikasi_arsip()
    {
        $staff = User::factory()->staff()->create();

        $response = $this->actingAs($staff)->get('/klasifikasi-arsip');
        $response->assertStatus(403);
    }
}