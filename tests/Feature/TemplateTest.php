<?php

namespace Tests\Feature;

use App\Models\Template;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class TemplateTest extends TestCase
{
    use RefreshDatabase;

    private User $tataUsaha;

    /** @var string[] */
    private array $createdFiles = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->tataUsaha = User::factory()->tataUsaha()->create();
    }

    protected function tearDown(): void
    {
        foreach ($this->createdFiles as $file) {
            if (File::isFile($file)) {
                File::delete($file);
            }
        }

        parent::tearDown();
    }

    public function test_super_admin_dan_tata_usaha_dapat_membuka_manajemen_template(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();

        $this->actingAs($superAdmin)->get(route('templates.index'))->assertOk();
        $this->actingAs($this->tataUsaha)->get(route('templates.index'))->assertOk();
        $this->actingAs($this->tataUsaha)->get(route('templates.create'))->assertOk();
    }

    public function test_pegawai_tidak_boleh_mengakses_manajemen_template(): void
    {
        $pegawai = User::factory()->pegawai()->create();

        $this->actingAs($pegawai)->get(route('templates.index'))->assertForbidden();
        $this->actingAs($pegawai)->get(route('templates.create'))->assertForbidden();
        $this->actingAs($pegawai)->post(route('templates.store'), [])->assertForbidden();
    }

    public function test_store_menyimpan_docx_dengan_nama_berkas_yang_aman(): void
    {
        $response = $this->actingAs($this->tataUsaha)->post(route('templates.store'), [
            'name' => 'Surat Perintah Dinas',
            'category' => 'A. Naskah Dinas Penugasan',
            'sub_kategori' => '1. Surat Perintah',
            'file' => UploadedFile::fake()->create('Surat Perintah (Draft)!.docx', 120),
        ]);

        $template = Template::firstOrFail();
        $this->trackTemplate($template);

        $response->assertRedirect(route('templates.edit', $template->id));
        $this->assertSame('Surat Perintah Dinas', $template->name);
        $this->assertSame('storage/templates/'.basename($template->file_path), $template->file_path);
        $this->assertMatchesRegularExpression(
            '/^surat-perintah-draft_\d{14}_[A-Za-z0-9]{6}\.docx$/',
            basename($template->file_path)
        );
        $this->assertFileExists($this->storageFile($template->file_path));
    }

    public function test_store_menerima_template_pdf(): void
    {
        $this->actingAs($this->tataUsaha)->post(route('templates.store'), [
            'name' => 'Template PDF',
            'category' => 'E. Lainnya',
            'file' => UploadedFile::fake()->create('template naskah dinas.pdf', 80),
        ])->assertRedirect();

        $template = Template::firstOrFail();
        $this->trackTemplate($template);

        $this->assertStringEndsWith('.pdf', $template->file_path);
        $this->assertFileExists($this->storageFile($template->file_path));
    }

    public function test_store_menolak_ekstensi_selain_docx_dan_pdf(): void
    {
        $this->actingAs($this->tataUsaha)->post(route('templates.store'), [
            'name' => 'File Berbahaya',
            'category' => 'E. Lainnya',
            'file' => UploadedFile::fake()->create('virus.exe', 10),
        ])->assertSessionHasErrors('file');

        $this->assertSame(0, Template::count());
    }

    public function test_store_menolak_berkas_lebih_dari_10_mb(): void
    {
        $this->actingAs($this->tataUsaha)->post(route('templates.store'), [
            'name' => 'File Kebesaran',
            'category' => 'E. Lainnya',
            'file' => UploadedFile::fake()->create('besar.docx', 10241),
        ])->assertSessionHasErrors('file');

        $this->assertSame(0, Template::count());
    }

    public function test_show_dan_edit_menampilkan_template(): void
    {
        $template = Template::factory()->create(['name' => 'Template Uji']);

        $this->actingAs($this->tataUsaha)->get(route('templates.show', $template->id))
            ->assertOk()
            ->assertSee('Template Uji');

        $this->actingAs($this->tataUsaha)->get(route('templates.edit', $template->id))
            ->assertOk()
            ->assertSee('Template Uji');
    }

    public function test_update_mengganti_berkas_dan_menghapus_berkas_lama(): void
    {
        $oldDocx = $this->createPhysicalFile('template-lama.docx');
        $oldPdf = $this->createPhysicalFile('pratinjau-lama.pdf');

        $template = Template::factory()->create([
            'file_path' => 'storage/templates/'.$oldDocx,
            'file_pdf' => 'storage/templates/'.$oldPdf,
        ]);

        $response = $this->actingAs($this->tataUsaha)->put(route('templates.update', $template->id), [
            'name' => 'Template Baru',
            'category' => 'B. Naskah Dinas Korespondensi Internal',
            'sub_kategori' => '2. Nota Dinas',
            'is_active' => '0',
            'file' => UploadedFile::fake()->create('template baru.docx', 50),
            'file_pdf' => UploadedFile::fake()->create('pratinjau baru.pdf', 40),
        ]);

        $response->assertRedirect(route('templates.index'));

        $template->refresh();
        $this->trackTemplate($template);

        $this->assertSame('Template Baru', $template->name);
        $this->assertFalse($template->is_active);
        $this->assertFileDoesNotExist($this->storageFile($oldDocx));
        $this->assertFileDoesNotExist($this->storageFile($oldPdf));
        $this->assertFileExists($this->storageFile($template->file_path));
        $this->assertFileExists($this->storageFile($template->file_pdf));
        $this->assertStringEndsWith('.pdf', $template->file_pdf);
    }

    public function test_update_menolak_ekstensi_yang_tidak_diizinkan(): void
    {
        $template = Template::factory()->create();

        $this->actingAs($this->tataUsaha)->put(route('templates.update', $template->id), [
            'name' => 'Template Baru',
            'category' => 'E. Lainnya',
            'file' => UploadedFile::fake()->create('skrip.php', 5),
        ])->assertSessionHasErrors('file');

        $this->assertSame($template->name, $template->fresh()->name);
    }

    public function test_remove_file_menghapus_berkas_fisik_dan_mengosongkan_path(): void
    {
        $fileName = $this->createPhysicalFile('hapus-docx.docx');

        $template = Template::factory()->create([
            'file_path' => 'storage/templates/'.$fileName,
        ]);

        $this->actingAs($this->tataUsaha)
            ->post(route('templates.remove-file', $template->id))
            ->assertRedirect(route('templates.edit', $template->id));

        $template->refresh();
        $this->assertSame('', $template->file_path);
        $this->assertNull($template->placeholders);
        $this->assertFileDoesNotExist($this->storageFile($fileName));
    }

    public function test_remove_file_pdf_menghapus_berkas_fisik(): void
    {
        $fileName = $this->createPhysicalFile('hapus-pdf.pdf');

        $template = Template::factory()->create([
            'file_pdf' => 'storage/templates/'.$fileName,
        ]);

        $this->actingAs($this->tataUsaha)
            ->post(route('templates.remove-file-pdf', $template->id))
            ->assertRedirect(route('templates.edit', $template->id));

        $template->refresh();
        $this->assertNull($template->file_pdf);
        $this->assertFileDoesNotExist($this->storageFile($fileName));
    }

    public function test_destroy_menghapus_record_dan_semua_berkas_fisik(): void
    {
        $docx = $this->createPhysicalFile('destroy-docx.docx');
        $pdf = $this->createPhysicalFile('destroy-pdf.pdf');

        $template = Template::factory()->create([
            'file_path' => 'storage/templates/'.$docx,
            'file_pdf' => 'storage/templates/'.$pdf,
        ]);

        $this->actingAs($this->tataUsaha)
            ->delete(route('templates.destroy', $template->id))
            ->assertRedirect(route('templates.index'));

        $this->assertDatabaseMissing('templates', ['id' => $template->id]);
        $this->assertFileDoesNotExist($this->storageFile($docx));
        $this->assertFileDoesNotExist($this->storageFile($pdf));
    }

    public function test_index_dapat_dicari_berdasarkan_nama(): void
    {
        Template::factory()->create(['name' => 'Surat Perintah Alpha']);
        Template::factory()->create(['name' => 'Nota Dinas Beta']);

        $this->actingAs($this->tataUsaha)
            ->get(route('templates.index', ['search' => 'Alpha']))
            ->assertOk()
            ->assertSee('Surat Perintah Alpha')
            ->assertDontSee('Nota Dinas Beta');
    }

    private function trackTemplate(Template $template): void
    {
        foreach ([$template->file_path, $template->file_pdf] as $path) {
            if ($path) {
                $this->createdFiles[] = $this->storageFile($path);
            }
        }
    }

    private function createPhysicalFile(string $fileName): string
    {
        $directory = storage_path('app/public/templates');
        File::ensureDirectoryExists($directory, 0755, true);

        $path = $directory.DIRECTORY_SEPARATOR.$fileName;
        File::put($path, 'uji');
        $this->createdFiles[] = $path;

        return $fileName;
    }

    private function storageFile(?string $path): string
    {
        return storage_path('app/public/templates/'.basename((string) $path));
    }
}
