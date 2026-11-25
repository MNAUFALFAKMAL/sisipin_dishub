<?php

namespace Tests\Unit\Policies;

use App\Models\Bidang;
use App\Models\SuratMasuk;
use App\Models\User;
use App\Policies\SuratMasukPolicy;
use Tests\TestCase;

class SuratMasukPolicyTest extends TestCase
{
    protected SuratMasukPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new SuratMasukPolicy;
    }

    public function test_super_admin_can_view_any_surat_masuk()
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $this->assertTrue($this->policy->viewAny($superAdmin));
    }

    public function test_pegawai_can_view_any_surat_masuk()
    {
        $pegawai = User::factory()->pegawai()->create();
        $this->assertTrue($this->policy->viewAny($pegawai));
    }

    public function test_super_admin_can_view_surat_masuk()
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $surat = SuratMasuk::factory()->create();
        $this->assertTrue($this->policy->view($superAdmin, $surat));
    }

    public function test_tata_usaha_can_view_surat_masuk()
    {
        $tataUsaha = User::factory()->tataUsaha()->create();
        $surat = SuratMasuk::factory()->create();
        $this->assertTrue($this->policy->view($tataUsaha, $surat));
    }

    public function test_kepala_dinas_can_view_surat_masuk()
    {
        $kepalaDinas = User::factory()->kepalaDinas()->create();
        $surat = SuratMasuk::factory()->create();
        $this->assertTrue($this->policy->view($kepalaDinas, $surat));
    }

    public function test_kepala_bidang_can_view_own_bidang_surat_masuk()
    {
        $bidang = Bidang::factory()->create();
        $kepalaBidang = User::factory()->kepalaBidang()->create(['bidang_id' => $bidang->id]);
        $surat = SuratMasuk::factory()->create(['bidang_tujuan_id' => $bidang->id]);
        $this->assertTrue($this->policy->view($kepalaBidang, $surat));
    }

    public function test_kepala_bidang_cannot_view_other_bidang_surat_masuk()
    {
        $bidang1 = Bidang::factory()->create();
        $bidang2 = Bidang::factory()->create();
        $kepalaBidang = User::factory()->kepalaBidang()->create(['bidang_id' => $bidang1->id]);
        $surat = SuratMasuk::factory()->create(['bidang_tujuan_id' => $bidang2->id]);
        $this->assertFalse($this->policy->view($kepalaBidang, $surat));
    }

    public function test_staff_can_view_own_surat_masuk()
    {
        $staff = User::factory()->staff()->create();
        $surat = SuratMasuk::factory()->create(['diinput_oleh' => $staff->id]);
        $this->assertTrue($this->policy->view($staff, $surat));
    }

    public function test_staff_can_view_surat_masuk_to_own_bidang()
    {
        $bidang = Bidang::factory()->create();
        $staff = User::factory()->staff()->create(['bidang_id' => $bidang->id]);
        $surat = SuratMasuk::factory()->create(['bidang_tujuan_id' => $bidang->id]);
        $this->assertTrue($this->policy->view($staff, $surat));
    }

    public function test_staff_cannot_view_other_user_surat_masuk_outside_own_bidang()
    {
        $otherUser = User::factory()->staff()->create();
        $staff = User::factory()->staff()->create();
        $surat = SuratMasuk::factory()->create(['diinput_oleh' => $otherUser->id]);
        $this->assertFalse($this->policy->view($staff, $surat));
    }

    public function test_super_admin_can_create_surat_masuk()
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $this->assertTrue($this->policy->create($superAdmin));
    }

    public function test_pegawai_can_create_surat_masuk()
    {
        $pegawai = User::factory()->pegawai()->create();
        $this->assertTrue($this->policy->create($pegawai));
    }

    public function test_super_admin_can_update_any_surat_masuk()
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $surat = SuratMasuk::factory()->create();
        $this->assertTrue($this->policy->update($superAdmin, $surat));
    }

    public function test_tata_usaha_can_update_any_surat_masuk()
    {
        $tataUsaha = User::factory()->tataUsaha()->create();
        $surat = SuratMasuk::factory()->create();
        $this->assertTrue($this->policy->update($tataUsaha, $surat));
    }

    public function test_creator_can_update_own_surat_masuk_when_not_yet_disposed()
    {
        $user = User::factory()->staff()->create();
        $surat = SuratMasuk::factory()->create(['diinput_oleh' => $user->id, 'status_disposisi' => 'belum']);
        $this->assertTrue($this->policy->update($user, $surat));
    }

    public function test_creator_cannot_update_own_surat_masuk_after_disposed()
    {
        $user = User::factory()->staff()->create();
        $surat = SuratMasuk::factory()->create(['diinput_oleh' => $user->id, 'status_disposisi' => 'disposisi']);
        $this->assertFalse($this->policy->update($user, $surat));
    }

    public function test_user_cannot_update_other_user_surat_masuk()
    {
        $user = User::factory()->staff()->create();
        $otherUser = User::factory()->staff()->create();
        $surat = SuratMasuk::factory()->create(['diinput_oleh' => $otherUser->id, 'status_disposisi' => 'belum']);
        $this->assertFalse($this->policy->update($user, $surat));
    }

    public function test_super_admin_can_delete_any_surat_masuk()
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $surat = SuratMasuk::factory()->create();
        $this->assertTrue($this->policy->delete($superAdmin, $surat));
    }

    public function test_creator_can_delete_own_surat_masuk_when_not_yet_disposed()
    {
        $user = User::factory()->staff()->create();
        $surat = SuratMasuk::factory()->create(['diinput_oleh' => $user->id, 'status_disposisi' => 'belum']);
        $this->assertTrue($this->policy->delete($user, $surat));
    }

    public function test_creator_cannot_delete_own_surat_masuk_after_disposed()
    {
        $user = User::factory()->staff()->create();
        $surat = SuratMasuk::factory()->create(['diinput_oleh' => $user->id, 'status_disposisi' => 'diproses']);
        $this->assertFalse($this->policy->delete($user, $surat));
    }

    public function test_super_admin_can_create_disposisi()
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $surat = SuratMasuk::factory()->create();
        $this->assertTrue($this->policy->disposisi($superAdmin, $surat));
    }

    public function test_kepala_bidang_can_create_disposisi()
    {
        $kepalaBidang = User::factory()->kepalaBidang()->create();
        $surat = SuratMasuk::factory()->create();
        $this->assertTrue($this->policy->disposisi($kepalaBidang, $surat));
    }

    public function test_pegawai_cannot_create_disposisi()
    {
        $pegawai = User::factory()->pegawai()->create();
        $surat = SuratMasuk::factory()->create();
        $this->assertFalse($this->policy->disposisi($pegawai, $surat));
    }

    public function test_tata_usaha_can_change_status_disposisi()
    {
        $tataUsaha = User::factory()->tataUsaha()->create();
        $surat = SuratMasuk::factory()->create();
        $this->assertTrue($this->policy->changeStatusDisposisi($tataUsaha, $surat));
    }

    public function test_kepala_dinas_can_change_status_disposisi()
    {
        $kepalaDinas = User::factory()->kepalaDinas()->create();
        $surat = SuratMasuk::factory()->create();
        $this->assertTrue($this->policy->changeStatusDisposisi($kepalaDinas, $surat));
    }

    public function test_pegawai_cannot_change_status_disposisi()
    {
        $pegawai = User::factory()->pegawai()->create();
        $surat = SuratMasuk::factory()->create();
        $this->assertFalse($this->policy->changeStatusDisposisi($pegawai, $surat));
    }
}
