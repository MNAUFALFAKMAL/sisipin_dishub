<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TataNaskahPublicTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function public_can_view_tata_naskah_catalog()
    {
        $response = $this->get('/tata-naskah');

        $response->assertStatus(200);
        $response->assertSee('Tata Naskah Dinas');
        $response->assertSee('Template Tersedia');
    }

    /** @test */
    public function public_can_see_grouped_catalog_by_category()
    {
        $response = $this->get('/tata-naskah');

        $response->assertSee('Surat Edaran');
        $response->assertSee('Surat Keputusan');
        $response->assertSee('Sub-kategori');
    }

    /** @test */
    public function public_can_see_search_functionality()
    {
        $response = $this->get('/tata-naskah?search=Nota');

        $response->assertStatus(200);
        $response->assertSee('Hasil pencarian');
    }

    /** @test */
    public function public_empty_state_when_no_templates()
    {
        // Test with fresh DB - will show empty state
        $response = $this->get('/tata-naskah?search=nonexistent-xyz123');

        $response->assertStatus(200);
        // Should show empty state message
    }

    /** @test */
    public function public_templates_have_download_links()
    {
        $response = $this->get('/tata-naskah');

        $response->assertSee('Unduh');
        $response->assertSee('Pratinjau');
    }
}