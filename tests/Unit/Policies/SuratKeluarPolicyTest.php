<?php

namespace Tests\Unit\Policies;

use App\Models\SuratKeluar;
use App\Models\User;
use App\Policies\SuratKeluarPolicy;
use Tests\TestCase;

class SuratKeluarPolicyTest extends TestCase
{
    protected SuratKeluarPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new SuratKeluarPolicy();
    }

    public function test_super_admin_can_view_any_surat_keluar()
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $this->assertTrue($this->policy->viewAny($superAdmin));
    }

    public function test_tata_usaha_can_view_any_surat_keluar()
    {
        $tataUsaha = User::factory()->tataUsaha()->create();
        $this->assertTrue($this->policy->viewAny($tataUsaha));
    }

    public function test_pegawai_can_view_any_surat_keluar()
    {
        $pegawai = User::factory()->pegawai()->create();
        $this->assertTrue($this->policy->viewAny($pegawai));
    }

    public function test_super_admin_can_view_surat_keluar()
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $surat = SuratKeluar::factory()->create();
        $this->assertTrue($this->policy->view($superAdmin, $surat));
    }

    public function test_user_can_view_own_surat_keluar()
    {
        $user = User::factory()->create();
        $surat = SuratKeluar::factory()->create(['dibuat_oleh' => $user->id]);
        $this->assertTrue($this->policy->view($user, $surat));
    }

    public function test_tata_usaha_can_view_surat_keluar()
    {
        $tataUsaha = User::factory()->tataUsaha()->create();
        $surat = SuratKeluar::factory()->create();
        $this->assertTrue($this->policy->view($tataUsaha, $surat));
    }

    public function test_kepala_dinas_can_view_surat_keluar()
    {
        $kepalaDinas = User::factory()->kepalaDinas()->create();
        $surat = SuratKeluar::factory()->create();
        $this->assertTrue($this->policy->view($kepalaDinas, $surat));
    }

    public function test_kepala_bidang_can_view_own_bidang_surat_keluar()
    {
        $bidang = \App\Models\Bidang::factory()->create();
        $kepalaBidang = User::factory()->kepalaBidang()->create(['bidang_id' => $bidang->id]);
        $surat = SuratKeluar::factory()->create(['bidang_id' => $bidang->id]);
        $this->assertTrue($this->policy->view($kepalaBidang, $surat));
    }

    public function test_kepala_bidang_cannot_view_other_bidang_surat_keluar()
    {
        $bidang1 = \App\Models\Bidang::factory()->create();
        $bidang2 = \App\Models\Bidang::factory()->create();
        $kepalaBidang = User::factory()->kepalaBidang()->create(['bidang_id' => $bidang1->id]);
        $surat = SuratKeluar::factory()->create(['bidang_id' => $bidang2->id]);
        $this->assertFalse($this->policy->view($kepalaBidang, $surat));
    }

    public function test_pegawai_cannot_view_other_user_surat_keluar()
    {
        $pegawai = User::factory()->pegawai()->create();
        $otherUser = User::factory()->create();
        $surat = SuratKeluar::factory()->create(['dibuat_oleh' => $otherUser->id]);
        $this->assertFalse($this->policy->view($pegawai, $surat));
    }

    public function test_super_admin_can_create_surat_keluar()
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $this->assertTrue($this->policy->create($superAdmin));
    }

    public function test_tata_usaha_can_create_surat_keluar()
    {
        $tataUsaha = User::factory()->tataUsaha()->create();
        $this->assertTrue($this->policy->create($tataUsaha));
    }

    public function test_pegawai_can_create_surat_keluar()
    {
        $pegawai = User::factory()->pegawai()->create();
        $this->assertTrue($this->policy->create($pegawai));
    }

    public function test_super_admin_can_update_any_surat_keluar()
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $surat = SuratKeluar::factory()->create();
        $this->assertTrue($this->policy->update($superAdmin, $surat));
    }

    public function test_tata_usaha_can_update_any_surat_keluar()
    {
        $tataUsaha = User::factory()->tataUsaha()->create();
        $surat = SuratKeluar::factory()->create();
        $this->assertTrue($this->policy->update($tataUsaha, $surat));
    }

    public function test_user_can_update_own_draft_surat_keluar()
    {
        $user = User::factory()->create();
        $surat = SuratKeluar::factory()->create(['dibuat_oleh' => $user->id, 'status' => 'draft']);
        $this->assertTrue($this->policy->update($user, $surat));
    }

    public function test_user_cannot_update_non_draft_surat_keluar()
    {
        $user = User::factory()->create();
        $surat = SuratKeluar::factory()->create(['dibuat_oleh' => $user->id, 'status' => 'dikirim']);
        $this->assertFalse($this->policy->update($user, $surat));
    }

    public function test_user_cannot_update_other_user_surat_keluar()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $surat = SuratKeluar::factory()->create(['dibuat_oleh' => $otherUser->id, 'status' => 'draft']);
        $this->assertFalse($this->policy->update($user, $surat));
    }

    public function test_super_admin_can_delete_any_surat_keluar()
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $surat = SuratKeluar::factory()->create();
        $this->assertTrue($this->policy->delete($superAdmin, $surat));
    }

    public function test_tata_usaha_can_delete_any_surat_keluar()
    {
        $tataUsaha = User::factory()->tataUsaha()->create();
        $surat = SuratKeluar::factory()->create();
        $this->assertTrue($this->policy->delete($tataUsaha, $surat));
    }

    public function test_user_can_delete_own_draft_surat_keluar()
    {
        $user = User::factory()->create();
        $surat = SuratKeluar::factory()->create(['dibuat_oleh' => $user->id, 'status' => 'draft']);
        $this->assertTrue($this->policy->delete($user, $surat));
    }

    public function test_user_cannot_delete_non_draft_surat_keluar()
    {
        $user = User::factory()->create();
        $surat = SuratKeluar::factory()->create(['dibuat_oleh' => $user->id, 'status' => 'dikirim']);
        $this->assertFalse($this->policy->delete($user, $surat));
    }

    public function test_super_admin_can_change_status()
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $surat = SuratKeluar::factory()->create();
        $this->assertTrue($this->policy->changeStatus($superAdmin, $surat));
    }

    public function test_tata_usaha_can_change_status()
    {
        $tataUsaha = User::factory()->tataUsaha()->create();
        $surat = SuratKeluar::factory()->create();
        $this->assertTrue($this->policy->changeStatus($tataUsaha, $surat));
    }

    public function test_kepala_dinas_can_change_status()
    {
        $kepalaDinas = User::factory()->kepalaDinas()->create();
        $surat = SuratKeluar::factory()->create();
        $this->assertTrue($this->policy->changeStatus($kepalaDinas, $surat));
    }

    public function test_kepala_bidang_can_change_status()
    {
        $kepalaBidang = User::factory()->kepalaBidang()->create();
        $surat = SuratKeluar::factory()->create();
        $this->assertTrue($this->policy->changeStatus($kepalaBidang, $surat));
    }

    public function test_pegawai_cannot_change_status()
    {
        $pegawai = User::factory()->pegawai()->create();
        $surat = SuratKeluar::factory()->create();
        $this->assertFalse($this->policy->changeStatus($pegawai, $surat));
    }
}