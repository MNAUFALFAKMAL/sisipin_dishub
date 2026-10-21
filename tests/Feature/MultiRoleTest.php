<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MultiRoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_staff_cannot_access_pengguna_and_auditlog()
    {
        $staff = User::factory()->staff()->create();

        $responsePengguna = $this->actingAs($staff)->get('/pengguna');
        $responsePengguna->assertStatus(403);

        $responseAudit = $this->actingAs($staff)->get('/audit-logs');
        $responseAudit->assertStatus(403);
    }

    public function test_tata_usaha_can_access_surat_masuk_and_klasifikasi()
    {
        $tu = User::factory()->tataUsaha()->create();

        $responseSurat = $this->actingAs($tu)->get('/surat-masuk?jenis_surat=Surat+Masuk&tahun=' . date('Y'));
        $responseSurat->assertStatus(200);

        $responseKlasifikasi = $this->actingAs($tu)->get('/klasifikasi-arsip');
        $responseKlasifikasi->assertStatus(200);
    }

    public function test_super_admin_can_access_pengguna()
    {
        $admin = User::factory()->superAdmin()->create();

        $response = $this->actingAs($admin)->get('/pengguna');
        $response->assertStatus(200);
    }

    public function test_tata_usaha_cannot_access_pengguna()
    {
        $tu = User::factory()->tataUsaha()->create();

        $response = $this->actingAs($tu)->get('/pengguna');
        $response->assertStatus(403);
    }

    public function test_staff_cannot_access_templates_index()
    {
        $staff = User::factory()->staff()->create();

        $response = $this->actingAs($staff)->get('/templates');
        $response->assertStatus(403);
    }

    public function test_tata_usaha_can_access_templates_index()
    {
        $tu = User::factory()->tataUsaha()->create();

        $response = $this->actingAs($tu)->get('/templates');
        $response->assertStatus(200);
    }

    public function test_public_tata_naskah_returns_ok()
    {
        $response = $this->get('/tata-naskah');
        $response->assertStatus(200);
    }

    public function test_guest_cannot_access_dashboard()
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_register_route_is_disabled()
    {
        $response = $this->get('/register');
        $response->assertStatus(404);

        $response = $this->post('/register');
        $response->assertStatus(404);
    }
}