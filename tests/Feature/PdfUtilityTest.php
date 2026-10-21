<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Mpdf\Mpdf;
use Tests\TestCase;

class PdfUtilityTest extends TestCase
{
    use RefreshDatabase;

    protected function makeUser(): User
    {
        return User::factory()->create(['role' => 'staff']);
    }

    protected function makeValidPdf(): string
    {
        $mpdf = new Mpdf();
        $mpdf->WriteHTML('<h1>Sample PDF</h1><p>FPDI merge test.</p>');
        $mpdf->AddPage();
        $mpdf->WriteHTML('<h1>Page Two</h1>');
        return $mpdf->Output('', 'S');
    }

    public function test_index_requires_auth(): void
    {
        $this->get('/pdf-utility')->assertRedirect('/login');
    }

    public function test_merge_pdfs_returns_download(): void
    {
        Storage::fake('local');
        $pdf = $this->makeValidPdf();
        $file = UploadedFile::fake()->createWithContent('sample.pdf', $pdf);

        $response = $this->actingAs($this->makeUser())
            ->post('/pdf-utility/merge-pdfs', [
                'pdfs' => [$file],
                'title' => 'gabungan-test',
            ]);

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_merge_pdfs_rejects_non_pdf(): void
    {
        $file = UploadedFile::fake()->create('file.txt', 100);

        $response = $this->actingAs($this->makeUser())
            ->post('/pdf-utility/merge-pdfs', [
                'pdfs' => [$file],
            ]);

        $response->assertSessionHasErrors('pdfs.0');
    }

    public function test_images_to_pdf_returns_download(): void
    {
        Storage::fake('local');
        $img = UploadedFile::fake()->image('scan.jpg', 200, 100);

        $response = $this->actingAs($this->makeUser())
            ->post('/pdf-utility/images-to-pdf', [
                'images' => [$img],
                'title' => 'foto-test',
            ]);

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }
}
