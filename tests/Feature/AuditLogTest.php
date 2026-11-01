<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\Bidang;
use App\Models\KlasifikasiArsip;
use App\Models\Peran;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use App\Models\TahunArsip;
use App\Models\Template;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class AuditLogTest extends TestCase
{
    use RefreshDatabase;

    protected User $superAdmin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->superAdmin = User::factory()->superAdmin()->create();
    }

    public function test_login_is_recorded_in_audit_log(): void
    {
        $user = User::factory()->pegawai()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $user->id,
            'activity' => 'login',
            'model_type' => User::class,
            'model_id' => $user->id,
        ]);
    }

    public function test_logout_is_recorded_in_audit_log(): void
    {
        $user = User::factory()->pegawai()->create();

        $this->actingAs($user)->post('/logout');

        $this->assertGuest();

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $user->id,
            'activity' => 'logout',
            'model_type' => User::class,
            'model_id' => $user->id,
        ]);
    }

    public function test_creating_surat_masuk_records_exactly_one_audit_log(): void
    {
        [$tahun, $bidang, $klasifikasi] = $this->createSuratFixtures();

        $response = $this->actingAs($this->superAdmin)
            ->post(route('surat-masuk.store'), $this->suratMasukPayload($tahun, $bidang, $klasifikasi));

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $surat = SuratMasuk::where('no_agenda', '1')->firstOrFail();

        $logs = AuditLog::where('model_type', SuratMasuk::class)
            ->where('model_id', $surat->id)
            ->get();

        $this->assertCount(1, $logs);
        $this->assertSame('created', $logs->first()->activity);
        $this->assertSame($this->superAdmin->id, $logs->first()->user_id);
    }

    public function test_updating_surat_masuk_is_recorded_in_audit_log(): void
    {
        [$tahun, $bidang, $klasifikasi] = $this->createSuratFixtures();
        $surat = $this->createSuratMasuk($tahun, $bidang, $klasifikasi);

        $response = $this->actingAs($this->superAdmin)->put(
            route('surat-masuk.update', $surat),
            $this->suratMasukPayload($tahun, $bidang, $klasifikasi, [
                'perihal' => 'Perihal Setelah Diubah',
            ])
        );

        $response->assertSessionHasNoErrors();

        $log = AuditLog::where('model_type', SuratMasuk::class)
            ->where('model_id', $surat->id)
            ->where('activity', 'updated')
            ->first();

        $this->assertNotNull($log);
        $this->assertSame($this->superAdmin->id, $log->user_id);
        $this->assertSame('Perihal Setelah Diubah', $log->new_values['perihal']);
    }

    public function test_deleting_surat_masuk_is_recorded_in_audit_log(): void
    {
        [$tahun, $bidang, $klasifikasi] = $this->createSuratFixtures();
        $surat = $this->createSuratMasuk($tahun, $bidang, $klasifikasi);

        $response = $this->actingAs($this->superAdmin)->delete(route('surat-masuk.destroy', $surat));

        $response->assertRedirect();

        $log = AuditLog::where('model_type', SuratMasuk::class)
            ->where('model_id', $surat->id)
            ->where('activity', 'deleted')
            ->first();

        $this->assertNotNull($log);
        $this->assertSame($this->superAdmin->id, $log->user_id);
        $this->assertSame('Perihal Test', $log->old_values['perihal']);
    }

    public function test_creating_surat_keluar_is_recorded_in_audit_log(): void
    {
        [$tahun, $bidang, $klasifikasi] = $this->createSuratFixtures();

        $surat = $this->createSuratKeluar($tahun, $bidang, $klasifikasi);

        $log = AuditLog::where('model_type', SuratKeluar::class)
            ->where('model_id', $surat->id)
            ->first();

        $this->assertNotNull($log);
        $this->assertSame('created', $log->activity);
        $this->assertSame($this->superAdmin->id, $log->user_id);
    }

    public function test_updating_and_deleting_surat_keluar_is_recorded_in_audit_log(): void
    {
        [$tahun, $bidang, $klasifikasi] = $this->createSuratFixtures();

        $surat = $this->createSuratKeluar($tahun, $bidang, $klasifikasi);

        $surat->update(['perihal' => 'Perihal Surat Keluar Diubah']);
        $surat->delete();

        $updated = AuditLog::where('model_type', SuratKeluar::class)
            ->where('model_id', $surat->id)
            ->where('activity', 'updated')
            ->first();

        $deleted = AuditLog::where('model_type', SuratKeluar::class)
            ->where('model_id', $surat->id)
            ->where('activity', 'deleted')
            ->first();

        $this->assertNotNull($updated);
        $this->assertSame('Perihal Surat Keluar Diubah', $updated->new_values['perihal']);
        $this->assertNotNull($deleted);
        $this->assertSame('Perihal Surat Keluar Diubah', $deleted->old_values['perihal']);
        $this->assertSame($this->superAdmin->id, $deleted->user_id);
    }

    public function test_creating_template_is_recorded_in_audit_log(): void
    {
        $this->actingAs($this->superAdmin);

        $template = Template::create([
            'name' => 'Template Audit Log',
            'file_path' => 'templates/template-audit.docx',
            'category' => 'Surat',
            'is_active' => true,
        ]);

        $log = AuditLog::where('model_type', Template::class)
            ->where('model_id', $template->id)
            ->first();

        $this->assertNotNull($log);
        $this->assertSame('created', $log->activity);
        $this->assertSame($this->superAdmin->id, $log->user_id);
    }

    public function test_updating_and_deleting_template_is_recorded_in_audit_log(): void
    {
        $this->actingAs($this->superAdmin);

        $template = Template::create([
            'name' => 'Template Audit Log',
            'file_path' => 'templates/template-audit.docx',
            'category' => 'Surat',
            'is_active' => true,
        ]);

        $template->update(['name' => 'Template Setelah Diubah']);
        $template->delete();

        $updated = AuditLog::where('model_type', Template::class)
            ->where('model_id', $template->id)
            ->where('activity', 'updated')
            ->first();

        $deleted = AuditLog::where('model_type', Template::class)
            ->where('model_id', $template->id)
            ->where('activity', 'deleted')
            ->first();

        $this->assertNotNull($updated);
        $this->assertSame('Template Setelah Diubah', $updated->new_values['name']);
        $this->assertNotNull($deleted);
        $this->assertSame('Template Setelah Diubah', $deleted->old_values['name']);
        $this->assertSame($this->superAdmin->id, $deleted->user_id);
    }

    public function test_creating_user_is_recorded_in_audit_log(): void
    {
        $peran = Peran::firstOrCreate(
            ['slug' => 'tata_usaha'],
            ['nama' => 'Tata Usaha', 'deskripsi' => 'Tata Usaha', 'is_active' => true]
        );

        $response = $this->actingAs($this->superAdmin)->post('/pengguna', [
            'name' => 'Pengguna Baru',
            'username' => 'penggunabaru@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'peran_id' => $peran->id,
            'bidang_id' => null,
            'is_active' => true,
        ]);

        $response->assertRedirect('/pengguna');

        $user = User::where('email', 'penggunabaru@test.com')->firstOrFail();

        $log = AuditLog::where('model_type', User::class)
            ->where('model_id', $user->id)
            ->where('activity', 'created')
            ->first();

        $this->assertNotNull($log);
        $this->assertSame($this->superAdmin->id, $log->user_id);
    }

    public function test_changing_user_role_is_recorded_as_role_changed(): void
    {
        $user = User::factory()->pegawai()->create();
        $peranLama = $user->peran_id;

        $peranBaru = Peran::firstOrCreate(
            ['slug' => 'tata_usaha'],
            ['nama' => 'Tata Usaha', 'deskripsi' => 'Tata Usaha', 'is_active' => true]
        );

        $response = $this->actingAs($this->superAdmin)->put('/pengguna/'.$user->id, [
            'name' => $user->name,
            'username' => $user->email,
            'peran_id' => $peranBaru->id,
            'bidang_id' => null,
            'is_active' => true,
        ]);

        $response->assertRedirect('/pengguna');

        $log = AuditLog::where('model_type', User::class)
            ->where('model_id', $user->id)
            ->where('activity', 'role_changed')
            ->first();

        $this->assertNotNull($log);
        $this->assertSame($this->superAdmin->id, $log->user_id);
        $this->assertSame($peranLama, $log->old_values['peran_id']);
        $this->assertSame($peranBaru->id, $log->new_values['peran_id']);
    }

    public function test_deactivating_user_is_recorded_in_audit_log(): void
    {
        $user = User::factory()->pegawai()->create();

        $response = $this->actingAs($this->superAdmin)->delete('/pengguna/'.$user->id);

        $response->assertRedirect('/pengguna');

        $log = AuditLog::where('model_type', User::class)
            ->where('model_id', $user->id)
            ->where('activity', 'updated')
            ->first();

        $this->assertNotNull($log);
        $this->assertSame($this->superAdmin->id, $log->user_id);
        $this->assertArrayHasKey('is_active', $log->new_values);
    }

    public function test_only_super_admin_can_view_audit_logs(): void
    {
        $this->actingAs($this->superAdmin)
            ->get(route('audit-logs.index'))
            ->assertOk();

        $tataUsaha = User::factory()->tataUsaha()->create();
        $this->actingAs($tataUsaha)
            ->get(route('audit-logs.index'))
            ->assertForbidden();

        $pegawai = User::factory()->pegawai()->create();
        $this->actingAs($pegawai)
            ->get(route('audit-logs.index'))
            ->assertForbidden();
    }

    public function test_audit_log_page_is_read_only(): void
    {
        $this->assertFalse(Route::has('audit-logs.create'));
        $this->assertFalse(Route::has('audit-logs.store'));
        $this->assertFalse(Route::has('audit-logs.edit'));
        $this->assertFalse(Route::has('audit-logs.update'));
        $this->assertFalse(Route::has('audit-logs.destroy'));

        $response = $this->actingAs($this->superAdmin)->get(route('audit-logs.index'));
        $response->assertOk();
        $response->assertDontSee('audit-logs/create');
        $response->assertDontSee('audit-logs/edit');
    }

    /**
     * @return array{0: TahunArsip, 1: Bidang, 2: KlasifikasiArsip}
     */
    private function createSuratFixtures(): array
    {
        $tahun = TahunArsip::create(['tahun' => (int) date('Y')]);
        $bidang = Bidang::create([
            'nama_bidang' => 'Bidang Test',
            'kode_bidang' => 'BT',
        ]);
        $klasifikasi = KlasifikasiArsip::create([
            'kode' => '000.1',
            'nama' => 'Klasifikasi Test',
        ]);

        return [$tahun, $bidang, $klasifikasi];
    }

    private function suratMasukPayload(
        TahunArsip $tahun,
        Bidang $bidang,
        KlasifikasiArsip $klasifikasi,
        array $overrides = []
    ): array {
        return array_merge([
            'jenis_surat' => 'Surat Masuk',
            'tahun' => $tahun->tahun,
            'no_agenda' => '1',
            'no_surat' => '001/TEST/'.$tahun->tahun,
            'tanggal_surat' => now()->toDateString(),
            'tanggal_terima' => now()->toDateString(),
            'pengirim' => 'Instansi Test',
            'perihal' => 'Perihal Test',
            'isi_ringkas' => 'Isi ringkas test',
            'klasifikasi_id' => $klasifikasi->id,
            'bidang_tujuan_id' => $bidang->id,
            'keterangan' => 'Keterangan test',
        ], $overrides);
    }

    private function createSuratKeluar(
        TahunArsip $tahun,
        Bidang $bidang,
        KlasifikasiArsip $klasifikasi
    ): SuratKeluar {
        $this->actingAs($this->superAdmin);

        return SuratKeluar::create([
            'no_agenda' => '1',
            'tahun' => $tahun->tahun,
            'jenis_surat' => 'SPT',
            'no_surat' => '002/TEST/'.$tahun->tahun,
            'tanggal_surat' => now()->toDateString(),
            'tanggal_terima' => now()->toDateString(),
            'tujuan' => 'Instansi Tujuan',
            'perihal' => 'Perihal Surat Keluar',
            'klasifikasi_id' => $klasifikasi->id,
            'bidang_id' => $bidang->id,
        ]);
    }

    private function createSuratMasuk(
        TahunArsip $tahun,
        Bidang $bidang,
        KlasifikasiArsip $klasifikasi
    ): SuratMasuk {
        $response = $this->actingAs($this->superAdmin)
            ->post(route('surat-masuk.store'), $this->suratMasukPayload($tahun, $bidang, $klasifikasi));

        $response->assertSessionHasNoErrors();

        return SuratMasuk::where('no_agenda', '1')->firstOrFail();
    }
}
