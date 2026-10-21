<?php

namespace Tests\Unit\Policies;

use App\Models\User;
use App\Policies\UserPolicy;
use Tests\TestCase;

class UserPolicyTest extends TestCase
{
    protected UserPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new UserPolicy();
    }

    public function test_super_admin_can_view_any_users()
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $this->assertTrue($this->policy->viewAny($superAdmin));
    }

    public function test_tata_usaha_cannot_view_any_users()
    {
        $tataUsaha = User::factory()->tataUsaha()->create();
        $this->assertFalse($this->policy->viewAny($tataUsaha));
    }

    public function test_pegawai_cannot_view_any_users()
    {
        $pegawai = User::factory()->pegawai()->create();
        $this->assertFalse($this->policy->viewAny($pegawai));
    }

    public function test_super_admin_can_view_user()
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $user = User::factory()->create();

        $this->assertTrue($this->policy->view($superAdmin, $user));
    }

    public function test_user_can_view_own_profile()
    {
        $user = User::factory()->create();

        $this->assertTrue($this->policy->view($user, $user));
    }

    public function test_tata_usaha_cannot_view_other_user()
    {
        $tataUsaha = User::factory()->tataUsaha()->create();
        $otherUser = User::factory()->create();

        $this->assertFalse($this->policy->view($tataUsaha, $otherUser));
    }

    public function test_super_admin_can_create_user()
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $this->assertTrue($this->policy->create($superAdmin));
    }

    public function test_tata_usaha_cannot_create_user()
    {
        $tataUsaha = User::factory()->tataUsaha()->create();
        $this->assertFalse($this->policy->create($tataUsaha));
    }

    public function test_super_admin_can_update_any_user()
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $user = User::factory()->create();

        $this->assertTrue($this->policy->update($superAdmin, $user));
    }

    public function test_user_can_update_own_profile()
    {
        $user = User::factory()->create();

        $this->assertTrue($this->policy->update($user, $user));
    }

    public function test_tata_usaha_cannot_update_other_user()
    {
        $tataUsaha = User::factory()->tataUsaha()->create();
        $otherUser = User::factory()->create();

        $this->assertFalse($this->policy->update($tataUsaha, $otherUser));
    }

    public function test_super_admin_can_delete_other_user()
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $user = User::factory()->create();

        $this->assertTrue($this->policy->delete($superAdmin, $user));
    }

    public function test_super_admin_cannot_delete_self()
    {
        $superAdmin = User::factory()->superAdmin()->create();

        $this->assertFalse($this->policy->delete($superAdmin, $superAdmin));
    }

    public function test_tata_usaha_cannot_delete_user()
    {
        $tataUsaha = User::factory()->tataUsaha()->create();
        $user = User::factory()->create();

        $this->assertFalse($this->policy->delete($tataUsaha, $user));
    }

    public function test_super_admin_can_activate_user()
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $user = User::factory()->create();

        $this->assertTrue($this->policy->activate($superAdmin, $user));
    }

    public function test_super_admin_cannot_activate_self()
    {
        $superAdmin = User::factory()->superAdmin()->create();

        $this->assertFalse($this->policy->activate($superAdmin, $superAdmin));
    }

    public function test_super_admin_can_deactivate_user()
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $user = User::factory()->create();

        $this->assertTrue($this->policy->deactivate($superAdmin, $user));
    }

    public function test_super_admin_cannot_deactivate_self()
    {
        $superAdmin = User::factory()->superAdmin()->create();

        $this->assertFalse($this->policy->deactivate($superAdmin, $superAdmin));
    }

    public function test_super_admin_can_change_role()
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $user = User::factory()->create();

        $this->assertTrue($this->policy->changeRole($superAdmin, $user));
    }

    public function test_super_admin_cannot_change_own_role()
    {
        $superAdmin = User::factory()->superAdmin()->create();

        $this->assertFalse($this->policy->changeRole($superAdmin, $superAdmin));
    }

    public function test_tata_usaha_cannot_change_role()
    {
        $tataUsaha = User::factory()->tataUsaha()->create();
        $user = User::factory()->create();

        $this->assertFalse($this->policy->changeRole($tataUsaha, $user));
    }
}