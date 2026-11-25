<?php

namespace Tests\Feature;

use App\Models\Bidang;
use App\Models\KlasifikasiArsip;
use App\Models\SuratMasuk;
use App\Models\TahunArsip;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SuratMasukTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected KlasifikasiArsip $klasifikasi;

    protected Bidang $bidang;

    protected function setUp(): void
    {
        parent::setUp();
        // Create a user with tata_usaha role who can access surat-masuk
        $this->user = User::factory()->tataUsaha()->create();

        // StoreSuratMasukRequest requires tahun to exist in tahun_arsips.
        TahunArsip::create(['tahun' => (int) date('Y')]);

        // Create required related data
        $this->klasifikasi = KlasifikasiArsip::factory()->create([
            'kode' => '005.1',
            'nama' => 'Test Klasifikasi Masuk',
        ]);

        $this->bidang = Bidang::factory()->create([
            'nama_bidang' => 'Test Bidang Tujuan',
            'kode_bidang' => 'TBM',
        ]);
    }

    protected function getValidSuratMasukData(array $overrides = []): array
    {
        return array_merge([
            'jenis_surat' => 'Surat Masuk',
            'tahun' => date('Y'),
            'no_agenda' => '1',
            'no_surat' => '005/1/V.13/'.date('Y'),
            'tanggal_surat' => date('Y-m-d'),
            'tanggal_terima' => date('Y-m-d'),
            'pengirim' => 'Instansi Pengirim Test',
            'perihal' => 'Perihal Test',
            'isi_ringkas' => 'Isi ringkas test',
            'klasifikasi_id' => $this->klasifikasi->id,
            'bidang_tujuan_id' => $this->bidang->id,
            'keterangan' => 'Keterangan test',
        ], $overrides);
    }

    /**
     * Test Surat Masuk: create with 1 file, then update with another file -> total 2 files
     */
    public function test_surat_masuk_append_file_on_update(): void
    {
        Storage::fake('public');

        $file1 = UploadedFile::fake()->image('masuk1.jpg', 100, 100);
        $data = $this->getValidSuratMasukData([
            'no_agenda' => '1',
            'file_surat' => [$file1],
        ]);

        $response = $this->actingAs($this->user)->post(route('surat-masuk.store'), $data);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $surat = SuratMasuk::where('no_agenda', '1')->first();
        $this->assertNotNull($surat, 'Surat harus terbuat');
        $this->assertCount(1, $surat->file_surat);
        $firstPath = $surat->file_surat[0];
        $this->assertTrue(Storage::disk('public')->exists($firstPath));

        $file2 = UploadedFile::fake()->image('masuk2.jpg', 100, 100);
        $updateData = $this->getValidSuratMasukData([
            'no_agenda' => '1',
            'perihal' => 'Perihal Test Updated',
            'file_surat' => [$file2],
        ]);

        $response = $this->actingAs($this->user)->put(route('surat-masuk.update', $surat->id), $updateData);
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

        $file1 = UploadedFile::fake()->image('masuk1.jpg', 100, 100);
        $file2 = UploadedFile::fake()->image('masuk2.jpg', 120, 120);

        $data = $this->getValidSuratMasukData([
            'no_agenda' => '2',
            'file_surat' => [$file1],
        ]);

        $response = $this->actingAs($this->user)->post(route('surat-masuk.store'), $data);
        $response->assertRedirect();

        $surat = SuratMasuk::where('no_agenda', '2')->first();
        $firstPath = $surat->file_surat[0];

        $updateData = $this->getValidSuratMasukData([
            'no_agenda' => '2',
            'perihal' => 'Perihal Test 2 Updated',
            'file_surat' => [$file2],
        ]);

        $response = $this->actingAs($this->user)->put(route('surat-masuk.update', $surat->id), $updateData);
        $response->assertRedirect();

        $surat->refresh();
        $this->assertCount(2, $surat->file_surat);
        $secondPath = $surat->file_surat[1];

        $deleteData = $this->getValidSuratMasukData([
            'no_agenda' => '2',
            'hapus_file' => [$firstPath],
        ]);

        $response = $this->actingAs($this->user)->put(route('surat-masuk.update', $surat->id), $deleteData);
        $response->assertRedirect();

        $surat->refresh();
        $this->assertCount(1, $surat->file_surat);
        $this->assertFalse(Storage::disk('public')->exists($firstPath), 'File pertama harus terhapus');
        $this->assertTrue(Storage::disk('public')->exists($secondPath), 'File kedua harus tetap ada');
        $this->assertEquals($secondPath, $surat->file_surat[0]);
    }

    /**
     * Test Surat Masuk: passing all stored paths in hapus_file deletes them all
     */
    public function test_surat_masuk_hapus_file_deletes_all(): void
    {
        Storage::fake('public');

        $file1 = UploadedFile::fake()->image('masuk1.jpg', 100, 100);
        $file2 = UploadedFile::fake()->image('masuk2.jpg', 120, 120);

        $data = $this->getValidSuratMasukData([
            'no_agenda' => '3',
            'file_surat' => [$file1],
        ]);

        $response = $this->actingAs($this->user)->post(route('surat-masuk.store'), $data);
        $response->assertRedirect();

        $surat = SuratMasuk::where('no_agenda', '3')->first();
        $firstPath = $surat->file_surat[0];

        $updateData = $this->getValidSuratMasukData([
            'no_agenda' => '3',
            'perihal' => 'Perihal Test 3 Updated',
            'file_surat' => [$file2],
        ]);

        $response = $this->actingAs($this->user)->put(route('surat-masuk.update', $surat->id), $updateData);
        $response->assertRedirect();

        $surat->refresh();
        $this->assertCount(2, $surat->file_surat);

        $deleteData = $this->getValidSuratMasukData([
            'no_agenda' => '3',
            'hapus_file' => $surat->file_surat,
        ]);

        $response = $this->actingAs($this->user)->put(route('surat-masuk.update', $surat->id), $deleteData);
        $response->assertRedirect();

        $surat->refresh();
        $this->assertEmpty($surat->file_surat);
        $this->assertFalse(Storage::disk('public')->exists($firstPath));
    }

    /**
     * Test Surat Masuk: jenis_surat enum validation
     */
    public function test_surat_masuk_jenis_surat_enum_validation(): void
    {
        $data = $this->getValidSuratMasukData([
            'no_agenda' => '4',
            'jenis_surat' => 'InvalidJenis',
        ]);

        $response = $this->actingAs($this->user)->post(route('surat-masuk.store'), $data);
        $response->assertSessionHasErrors('jenis_surat');
    }

    /**
     * Test Surat Masuk: valid jenis_surat values (Surat Masuk, Undangan Masuk)
     */
    public function test_surat_masuk_valid_jenis_surat_values(): void
    {
        foreach (['Surat Masuk', 'Undangan Masuk'] as $jenis) {
            $data = $this->getValidSuratMasukData([
                'no_agenda' => (string) (rand(1000, 9999)),
                'jenis_surat' => $jenis,
            ]);

            $response = $this->actingAs($this->user)->post(route('surat-masuk.store'), $data);
            $response->assertSessionHasNoErrors();
            $response->assertRedirect();

            $surat = SuratMasuk::where('no_agenda', $data['no_agenda'])->first();
            $this->assertEquals($jenis, $surat->jenis_surat);
        }
    }

    /**
     * Test Surat Masuk: pengirim required
     */
    public function test_surat_masuk_pengirim_required(): void
    {
        $data = $this->getValidSuratMasukData([
            'no_agenda' => '5',
            'pengirim' => '',
        ]);

        $response = $this->actingAs($this->user)->post(route('surat-masuk.store'), $data);
        $response->assertSessionHasErrors('pengirim');
    }

    /**
     * Test Surat Masuk: perihal required
     */
    public function test_surat_masuk_perihal_required(): void
    {
        $data = $this->getValidSuratMasukData([
            'no_agenda' => '6',
            'perihal' => '',
        ]);

        $response = $this->actingAs($this->user)->post(route('surat-masuk.store'), $data);
        $response->assertSessionHasErrors('perihal');
    }

    /**
     * Test Surat Masuk: bidang_tujuan_id required
     */
    public function test_surat_masuk_bidang_tujuan_required(): void
    {
        $data = $this->getValidSuratMasukData([
            'no_agenda' => '7',
            'bidang_tujuan_id' => '',
        ]);

        $response = $this->actingAs($this->user)->post(route('surat-masuk.store'), $data);
        $response->assertSessionHasErrors('bidang_tujuan_id');
    }

    /**
     * Test Surat Masuk: no_agenda unique per tahun
     */
    public function test_surat_masuk_no_agenda_unique_per_tahun(): void
    {
        $data = $this->getValidSuratMasukData([
            'no_agenda' => '8',
        ]);
        $this->actingAs($this->user)->post(route('surat-masuk.store'), $data);

        $data2 = $this->getValidSuratMasukData([
            'no_agenda' => '8',
            'no_surat' => '005/2/V.13/'.date('Y'),
        ]);
        $response = $this->actingAs($this->user)->post(route('surat-masuk.store'), $data2);
        $response->assertSessionHasErrors('no_agenda');
    }

    /**
     * Test Surat Masuk: status_disposisi defaults to 'belum' when created
     */
    public function test_surat_masuk_default_status_disposisi_belum(): void
    {
        $data = $this->getValidSuratMasukData(['no_agenda' => '9']);
        $this->actingAs($this->user)->post(route('surat-masuk.store'), $data);

        $surat = SuratMasuk::where('no_agenda', '9')->first();
        $this->assertEquals('belum', $surat->status_disposisi);
    }

    /**
     * Test Surat Masuk: tata usaha can delete surat masuk
     */
    public function test_tata_usaha_can_delete_surat_masuk(): void
    {
        $data = $this->getValidSuratMasukData(['no_agenda' => '10']);
        $this->actingAs($this->user)->post(route('surat-masuk.store'), $data);
        $surat = SuratMasuk::where('no_agenda', '10')->first();

        $response = $this->actingAs($this->user)->delete(route('surat-masuk.destroy', $surat->id));
        $response->assertRedirect();
        $this->assertDatabaseMissing('surat_masuks', ['id' => $surat->id]);
    }
}
