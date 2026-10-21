<?php

namespace Tests\Feature;

use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SuratFileUploadTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Create a user with tata_usaha role who can access surat-masuk/keluar
        $this->user = User::factory()->create(['role' => 'tata_usaha']);
    }

    /**
     * Test Surat Masuk: create with 1 file, then update with another file -> total 2 files
     */
    public function test_surat_masuk_append_file_on_update(): void
    {
        Storage::fake('public');

        // Create surat masuk with 1 file (use image which converts to PDF)
        $file1 = UploadedFile::fake()->image('surat1.jpg', 100, 100);
        $response = $this->actingAs($this->user)->post(route('surat-masuk.store'), [
            'jenis_surat' => 'Surat Masuk',
            'no_agenda' => '1',
            'tanggal_terima' => date('Y-m-d'),
            'tanggal_surat' => date('Y-m-d'),
            'pengirim' => 'Instansi A',
            'perihal' => 'Perihal Test',
            'file_surat' => [$file1],
            'bidang_tujuan_ids' => [],
        ]);

        $response->assertSessionHasNoErrors();

        $response->assertRedirect();

        $surat = SuratMasuk::where('no_agenda', '1')->first();
        $this->assertNotNull($surat, 'Surat harus terbuat');
        $this->assertCount(1, $surat->file_surat);
        $firstPath = $surat->file_surat[0];
        $this->assertTrue(Storage::disk('public')->exists($firstPath));

        // Update with another file
        $file2 = UploadedFile::fake()->image('surat2.jpg', 100, 100);
        $response = $this->actingAs($this->user)->put(route('surat-masuk.update', $surat->id), [
            'jenis_surat' => 'Surat Masuk',
            'no_agenda' => '1',
            'tanggal_terima' => date('Y-m-d'),
            'tanggal_surat' => date('Y-m-d'),
            'pengirim' => 'Instansi A',
            'perihal' => 'Perihal Test Updated',
            'file_surat' => [$file2],
            'bidang_tujuan_ids' => [],
            'status_disposisi' => 'belum',
        ]);
        $response->assertRedirect();

        $surat->refresh();
        $this->assertCount(2, $surat->file_surat);
        $this->assertTrue(Storage::disk('public')->exists($firstPath), 'File lama harus tetap ada');
        $this->assertTrue(Storage::disk('public')->exists($surat->file_surat[1]), 'File baru harus tersimpan');
    }

    /**
     * Test Surat Masuk: delete specific file via hapus_file[]
     */
    public function test_surat_masuk_delete_specific_file(): void
    {
        Storage::fake('public');

        // Create with 2 files (simulate by creating then updating)
        $file1 = UploadedFile::fake()->image('surat1.jpg', 100, 100);
        $file2 = UploadedFile::fake()->image('surat2.jpg', 120, 120);

        $response = $this->actingAs($this->user)->post(route('surat-masuk.store'), [
            'jenis_surat' => 'Surat Masuk',
            'no_agenda' => '2',
            'tanggal_terima' => date('Y-m-d'),
            'pengirim' => 'Instansi B',
            'perihal' => 'Perihal Test 2',
            'file_surat' => [$file1],
            'bidang_tujuan_ids' => [],
        ]);
        $response->assertRedirect();

        $surat = SuratMasuk::where('no_agenda', '2')->first();
        $firstPath = $surat->file_surat[0];

        // Add second file
        $response = $this->actingAs($this->user)->put(route('surat-masuk.update', $surat->id), [
            'jenis_surat' => 'Surat Masuk',
            'no_agenda' => '2',
            'tanggal_terima' => date('Y-m-d'),
            'pengirim' => 'Instansi B',
            'perihal' => 'Perihal Test 2 Updated',
            'file_surat' => [$file2],
            'bidang_tujuan_ids' => [],
            'status_disposisi' => 'belum',
        ]);
        $response->assertRedirect();

        $surat->refresh();
        $this->assertCount(2, $surat->file_surat);
        $secondPath = $surat->file_surat[1];

        // Delete first file only
        $response = $this->actingAs($this->user)->put(route('surat-masuk.update', $surat->id), [
            'jenis_surat' => 'Surat Masuk',
            'no_agenda' => '2',
            'tanggal_terima' => date('Y-m-d'),
            'pengirim' => 'Instansi B',
            'perihal' => 'Perihal Test 2 Updated',
            'hapus_file' => [$firstPath],
            'bidang_tujuan_ids' => [],
            'status_disposisi' => 'belum',
        ]);
        $response->assertRedirect();

        $surat->refresh();
        $this->assertCount(1, $surat->file_surat);
        $this->assertFalse(Storage::disk('public')->exists($firstPath), 'File pertama harus terhapus');
        $this->assertTrue(Storage::disk('public')->exists($secondPath), 'File kedua harus tetap ada');
        $this->assertEquals($secondPath, $surat->file_surat[0]);
    }

    /**
     * Test Surat Masuk: legacy hapus_file = '1' deletes all files
     */
    public function test_surat_masuk_legacy_hapus_file_deletes_all(): void
    {
        Storage::fake('public');

        $file1 = UploadedFile::fake()->image('surat1.jpg', 100, 100);
        $file2 = UploadedFile::fake()->image('surat2.jpg', 120, 120);

        $response = $this->actingAs($this->user)->post(route('surat-masuk.store'), [
            'jenis_surat' => 'Surat Masuk',
            'no_agenda' => '3',
            'tanggal_terima' => date('Y-m-d'),
            'pengirim' => 'Instansi C',
            'perihal' => 'Perihal Test 3',
            'file_surat' => [$file1],
            'bidang_tujuan_ids' => [],
        ]);
        $response->assertRedirect();

        $surat = SuratMasuk::where('no_agenda', '3')->first();
        $firstPath = $surat->file_surat[0];

        // Add second file
        $response = $this->actingAs($this->user)->put(route('surat-masuk.update', $surat->id), [
            'jenis_surat' => 'Surat Masuk',
            'no_agenda' => '3',
            'tanggal_terima' => date('Y-m-d'),
            'pengirim' => 'Instansi C',
            'perihal' => 'Perihal Test 3 Updated',
            'file_surat' => [$file2],
            'bidang_tujuan_ids' => [],
            'status_disposisi' => 'belum',
        ]);
        $response->assertRedirect();

        $surat->refresh();
        $this->assertCount(2, $surat->file_surat);

        // Legacy: hapus_file = '1' should delete all
        $response = $this->actingAs($this->user)->put(route('surat-masuk.update', $surat->id), [
            'jenis_surat' => 'Surat Masuk',
            'no_agenda' => '3',
            'tanggal_terima' => date('Y-m-d'),
            'pengirim' => 'Instansi C',
            'perihal' => 'Perihal Test 3 Updated',
            'hapus_file' => '1',
            'bidang_tujuan_ids' => [],
            'status_disposisi' => 'belum',
        ]);
        $response->assertRedirect();

        $surat->refresh();
        $this->assertEmpty($surat->file_surat);
        $this->assertFalse(Storage::disk('public')->exists($firstPath));
    }

    /**
     * Test Surat Keluar: create with 1 file, then update with another file -> total 2 files
     */
    public function test_surat_keluar_append_file_on_update(): void
    {
        Storage::fake('public');

        $file1 = UploadedFile::fake()->image('keluar1.jpg', 100, 100);
        $response = $this->actingAs($this->user)->post(route('surat-keluar.store'), [
            'jenis_surat' => 'Surat Keluar',
            'no_agenda' => '1',
            'tanggal_terima' => date('Y-m-d'),
            'tujuan' => 'Instansi X',
            'perihal' => 'Perihal Keluar',
            'file_surat' => [$file1],
            'bidang_ids' => [],
        ]);
        $response->assertRedirect();

        $surat = SuratKeluar::where('no_agenda', '1')->first();
        $this->assertCount(1, $surat->file_surat);
        $firstPath = $surat->file_surat[0];
        $this->assertTrue(Storage::disk('public')->exists($firstPath));

        $file2 = UploadedFile::fake()->image('keluar2.jpg', 120, 120);
        $response = $this->actingAs($this->user)->put(route('surat-keluar.update', $surat->id), [
            'jenis_surat' => 'Surat Keluar',
            'no_agenda' => '1',
            'tanggal_terima' => date('Y-m-d'),
            'tujuan' => 'Instansi X',
            'perihal' => 'Perihal Keluar Updated',
            'file_surat' => [$file2],
            'bidang_ids' => [],
        ]);
        $response->assertRedirect();

        $surat->refresh();
        $this->assertCount(2, $surat->file_surat);
        $this->assertTrue(Storage::disk('public')->exists($firstPath));
        $this->assertTrue(Storage::disk('public')->exists($surat->file_surat[1]));
    }

    /**
     * Test Surat Keluar: delete specific file via hapus_file[]
     */
    public function test_surat_keluar_delete_specific_file(): void
    {
        Storage::fake('public');

        $file1 = UploadedFile::fake()->image('keluar1.jpg', 100, 100);
        $file2 = UploadedFile::fake()->image('keluar2.jpg', 120, 120);

        $response = $this->actingAs($this->user)->post(route('surat-keluar.store'), [
            'jenis_surat' => 'Surat Keluar',
            'no_agenda' => '2',
            'tanggal_terima' => date('Y-m-d'),
            'tujuan' => 'Instansi Y',
            'perihal' => 'Perihal Keluar 2',
            'file_surat' => [$file1],
            'bidang_ids' => [],
        ]);
        $response->assertRedirect();

        $surat = SuratKeluar::where('no_agenda', '2')->first();
        $firstPath = $surat->file_surat[0];

        $response = $this->actingAs($this->user)->put(route('surat-keluar.update', $surat->id), [
            'jenis_surat' => 'Surat Keluar',
            'no_agenda' => '2',
            'tanggal_terima' => date('Y-m-d'),
            'tujuan' => 'Instansi Y',
            'perihal' => 'Perihal Keluar 2 Updated',
            'file_surat' => [$file2],
            'bidang_ids' => [],
        ]);
        $response->assertRedirect();

        $surat->refresh();
        $this->assertCount(2, $surat->file_surat);
        $secondPath = $surat->file_surat[1];

        $response = $this->actingAs($this->user)->put(route('surat-keluar.update', $surat->id), [
            'jenis_surat' => 'Surat Keluar',
            'no_agenda' => '2',
            'tanggal_terima' => date('Y-m-d'),
            'tujuan' => 'Instansi Y',
            'perihal' => 'Perihal Keluar 2 Updated',
            'hapus_file' => [$firstPath],
            'bidang_ids' => [],
        ]);
        $response->assertRedirect();

        $surat->refresh();
        $this->assertCount(1, $surat->file_surat);
        $this->assertFalse(Storage::disk('public')->exists($firstPath));
        $this->assertTrue(Storage::disk('public')->exists($secondPath));
        $this->assertEquals($secondPath, $surat->file_surat[0]);
    }
}
