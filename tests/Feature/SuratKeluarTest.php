<?php

namespace Tests\Feature;

use App\Models\SuratKeluar;
use App\Models\User;
use App\Models\KlasifikasiArsip;
use App\Models\Bidang;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SuratKeluarTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected KlasifikasiArsip $klasifikasi;
    protected Bidang $bidang;

    protected function setUp(): void
    {
        parent::setUp();
        // Create a user with tata_usaha role who can access surat-keluar
        $this->user = User::factory()->tataUsaha()->create();

        // Create required related data
        $this->klasifikasi = KlasifikasiArsip::factory()->create([
            'kode' => '000.1',
            'nama' => 'Test Klasifikasi',
        ]);

        $this->bidang = Bidang::factory()->create([
            'nama_bidang' => 'Test Bidang',
            'kode_bidang' => 'TB',
        ]);
    }

    protected function getValidSuratKeluarData(array $overrides = []): array
    {
        return array_merge([
            'jenis_surat'      => 'SPT',
            'tahun'            => date('Y'),
            'no_agenda'        => '1',
            'no_surat'         => '800/1/V.13/' . date('Y'),
            'tanggal_surat'    => date('Y-m-d'),
            'tanggal_terima'   => date('Y-m-d'),
            'tujuan'           => 'Instansi Test',
            'perihal'          => 'Perihal Test',
            'isi_ringkas'      => 'Isi ringkas test',
            'klasifikasi_id'   => $this->klasifikasi->id,
            'bidang_id'        => $this->bidang->id,
            'penandatangan'    => 'Kepala Dinas Perhubungan',
            'keterangan'       => 'Keterangan test',
            'indeks_berkas'    => '001/DISHUB/2026',
            'tingkat_keamanan' => 'Biasa',
            'masa_simpan'      => '5 Tahun',
        ], $overrides);
    }

    /**
     * Test Surat Keluar: create with 1 file, then update with another file -> total 2 files
     */
    public function test_surat_keluar_append_file_on_update(): void
    {
        Storage::fake('public');

        // Create surat keluar with 1 file (use image which converts to PDF)
        $file1 = UploadedFile::fake()->image('keluar1.jpg', 100, 100);
        $data = $this->getValidSuratKeluarData([
            'no_agenda' => '1',
            'file_surat' => [$file1],
        ]);
        
        $response = $this->actingAs($this->user)->post(route('surat-keluar.store'), $data);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $surat = SuratKeluar::where('no_agenda', '1')->first();
        $this->assertNotNull($surat, 'Surat harus terbuat');
        $this->assertCount(1, $surat->file_surat);
        $firstPath = $surat->file_surat[0];
        $this->assertTrue(Storage::disk('public')->exists($firstPath));

        // Update with another file
        $file2 = UploadedFile::fake()->image('keluar2.jpg', 100, 100);
        $updateData = $this->getValidSuratKeluarData([
            'no_agenda' => '1',
            'perihal' => 'Perihal Test Updated',
            'file_surat' => [$file2],
        ]);
        
        $response = $this->actingAs($this->user)->put(route('surat-keluar.update', $surat->id), $updateData);
        $response->assertRedirect();

        $surat->refresh();
        $this->assertCount(2, $surat->file_surat);
        $this->assertTrue(Storage::disk('public')->exists($firstPath), 'File lama harus tetap ada');
        $this->assertTrue(Storage::disk('public')->exists($surat->file_surat[1]), 'File baru harus tersimpan');
    }

    /**
     * Test Surat Keluar: delete specific file via hapus_file[]
     */
    public function test_surat_keluar_delete_specific_file(): void
    {
        Storage::fake('public');

        // Create with 2 files (simulate by creating then updating)
        $file1 = UploadedFile::fake()->image('keluar1.jpg', 100, 100);
        $file2 = UploadedFile::fake()->image('keluar2.jpg', 120, 120);

        $data = $this->getValidSuratKeluarData([
            'no_agenda' => '2',
            'file_surat' => [$file1],
        ]);
        
        $response = $this->actingAs($this->user)->post(route('surat-keluar.store'), $data);
        $response->assertRedirect();

        $surat = SuratKeluar::where('no_agenda', '2')->first();
        $firstPath = $surat->file_surat[0];

        // Add second file
        $updateData = $this->getValidSuratKeluarData([
            'no_agenda' => '2',
            'perihal' => 'Perihal Test 2 Updated',
            'file_surat' => [$file2],
        ]);
        
        $response = $this->actingAs($this->user)->put(route('surat-keluar.update', $surat->id), $updateData);
        $response->assertRedirect();

        $surat->refresh();
        $this->assertCount(2, $surat->file_surat);
        $secondPath = $surat->file_surat[1];

        // Delete first file only
        $deleteData = $this->getValidSuratKeluarData([
            'no_agenda' => '2',
            'hapus_file' => [$firstPath],
        ]);
        
        $response = $this->actingAs($this->user)->put(route('surat-keluar.update', $surat->id), $deleteData);
        $response->assertRedirect();

        $surat->refresh();
        $this->assertCount(1, $surat->file_surat);
        $this->assertFalse(Storage::disk('public')->exists($firstPath), 'File pertama harus terhapus');
        $this->assertTrue(Storage::disk('public')->exists($secondPath), 'File kedua harus tetap ada');
        $this->assertEquals($secondPath, $surat->file_surat[0]);
    }

    /**
     * Test Surat Keluar: legacy hapus_file = '1' deletes all files
     */
    public function test_surat_keluar_legacy_hapus_file_deletes_all(): void
    {
        Storage::fake('public');

        $file1 = UploadedFile::fake()->image('keluar1.jpg', 100, 100);
        $file2 = UploadedFile::fake()->image('keluar2.jpg', 120, 120);

        $data = $this->getValidSuratKeluarData([
            'no_agenda' => '3',
            'file_surat' => [$file1],
        ]);
        
        $response = $this->actingAs($this->user)->post(route('surat-keluar.store'), $data);
        $response->assertRedirect();

        $surat = SuratKeluar::where('no_agenda', '3')->first();
        $firstPath = $surat->file_surat[0];

        // Add second file
        $updateData = $this->getValidSuratKeluarData([
            'no_agenda' => '3',
            'perihal' => 'Perihal Test 3 Updated',
            'file_surat' => [$file2],
        ]);
        
        $response = $this->actingAs($this->user)->put(route('surat-keluar.update', $surat->id), $updateData);
        $response->assertRedirect();

        $surat->refresh();
        $this->assertCount(2, $surat->file_surat);

        // Legacy: hapus_file = '1' should delete all
        $deleteData = $this->getValidSuratKeluarData([
            'no_agenda' => '3',
            'hapus_file' => '1',
        ]);
        
        $response = $this->actingAs($this->user)->put(route('surat-keluar.update', $surat->id), $deleteData);
        $response->assertRedirect();

        $surat->refresh();
        $this->assertEmpty($surat->file_surat);
        $this->assertFalse(Storage::disk('public')->exists($firstPath));
    }

    /**
     * Test Surat Keluar: jenis_surat enum validation
     */
    public function test_surat_keluar_jenis_surat_enum_validation(): void
    {
        $data = $this->getValidSuratKeluarData([
            'no_agenda' => '4',
            'jenis_surat' => 'InvalidJenis',
        ]);
        
        $response = $this->actingAs($this->user)->post(route('surat-keluar.store'), $data);
        $response->assertSessionHasErrors('jenis_surat');
    }

    /**
     * Test Surat Keluar: valid jenis_surat values
     */
    public function test_surat_keluar_valid_jenis_surat_values(): void
    {
        foreach (['SPT', 'SPPD', 'SK', 'Undangan'] as $jenis) {
            $data = $this->getValidSuratKeluarData([
                'no_agenda' => (string) (rand(1000, 9999)),
                'jenis_surat' => $jenis,
            ]);
            
            $response = $this->actingAs($this->user)->post(route('surat-keluar.store'), $data);
            $response->assertSessionHasNoErrors();
            $response->assertRedirect();
            
            $surat = SuratKeluar::where('no_agenda', $data['no_agenda'])->first();
            $this->assertEquals($jenis, $surat->jenis_surat);
        }
    }

    /**
     * Test Surat Keluar: penandatangan required
     */
    public function test_surat_keluar_penandatangan_required(): void
    {
        $data = $this->getValidSuratKeluarData([
            'no_agenda' => '5',
            'penandatangan' => '',
        ]);
        
        $response = $this->actingAs($this->user)->post(route('surat-keluar.store'), $data);
        $response->assertSessionHasErrors('penandatangan');
    }

    /**
     * Test Surat Keluar: tujuan required
     */
    public function test_surat_keluar_tujuan_required(): void
    {
        $data = $this->getValidSuratKeluarData([
            'no_agenda' => '6',
            'tujuan' => '',
        ]);
        
        $response = $this->actingAs($this->user)->post(route('surat-keluar.store'), $data);
        $response->assertSessionHasErrors('tujuan');
    }

    /**
     * Test Surat Keluar: no_agenda unique per tahun
     */
    public function test_surat_keluar_no_agenda_unique_per_tahun(): void
    {
        // Create first surat
        $data = $this->getValidSuratKeluarData([
            'no_agenda' => '7',
        ]);
        $this->actingAs($this->user)->post(route('surat-keluar.store'), $data);

        // Try to create second with same no_agenda and tahun
        $data2 = $this->getValidSuratKeluarData([
            'no_agenda' => '7',
            'no_surat' => '800/2/V.13/' . date('Y'),
        ]);
        $response = $this->actingAs($this->user)->post(route('surat-keluar.store'), $data2);
        $response->assertSessionHasErrors('no_agenda');
    }
}