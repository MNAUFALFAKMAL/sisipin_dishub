<?php

namespace Tests\Feature;

use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use App\Models\User;
use App\Models\KlasifikasiArsip;
use App\Models\Bidang;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected KlasifikasiArsip $klasifikasi;
    protected Bidang $bidang;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->tataUsaha()->create();

        $this->klasifikasi = KlasifikasiArsip::factory()->create([
            'kode' => '000.1',
            'nama' => 'Test Klasifikasi',
        ]);

        $this->bidang = Bidang::factory()->create([
            'nama_bidang' => 'Test Bidang',
            'kode_bidang' => 'TB',
        ]);
    }

    protected function getValidSuratMasukData(array $overrides = []): array
    {
        return array_merge([
            'jenis_surat'     => 'Surat Masuk',
            'tahun'           => date('Y'),
            'no_agenda'       => '1',
            'no_surat'        => '001/TEST/' . date('Y'),
            'tanggal_surat'   => date('Y-m-d'),
            'tanggal_terima'  => date('Y-m-d'),
            'pengirim'        => 'Instansi Test',
            'perihal'         => 'Perihal Test',
            'isi_ringkas'     => 'Isi ringkas test',
            'klasifikasi_id'  => $this->klasifikasi->id,
            'bidang_tujuan_id'=> $this->bidang->id,
            'keterangan'      => 'Keterangan test',
        ], $overrides);
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
        ], $overrides);
    }

    public function test_surat_masuk_search_endpoint_requires_auth()
    {
        $response = $this->get('/surat-masuk/search');
        $response->assertRedirect('/login');
    }

    public function test_surat_masuk_search_returns_json()
    {
        $data = $this->getValidSuratMasukData(['no_agenda' => '1']);
        $this->actingAs($this->user)->post(route('surat-masuk.store'), $data);

        $response = $this->actingAs($this->user)
            ->get('/surat-masuk/search?search=Test');

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                '*' => ['id', 'no_agenda', 'no_surat', 'perihal', 'pengirim', 'status_disposisi']
            ],
            'total'
        ]);
    }

    public function test_surat_masuk_search_filters_by_status()
    {
        $data = $this->getValidSuratMasukData(['no_agenda' => '1', 'status_disposisi' => 'selesai']);
        $this->actingAs($this->user)->post(route('surat-masuk.store'), $data);

        $data2 = $this->getValidSuratMasukData(['no_agenda' => '2', 'status_disposisi' => 'belum']);
        $this->actingAs($this->user)->post(route('surat-masuk.store'), $data2);

        $response = $this->actingAs($this->user)
            ->get('/surat-masuk/search?status=selesai');

        $response->assertOk();
        $data = $response->json();
        $this->assertEquals(1, $data['total']);
        $this->assertEquals('selesai', $data['data'][0]['status_disposisi']);
    }

    public function test_surat_masuk_search_filters_by_bidang()
    {
        $bidang2 = \App\Models\Bidang::factory()->create(['kode_bidang' => 'BB', 'nama_bidang' => 'Bidang Dua']);

        $data = $this->getValidSuratMasukData(['no_agenda' => '1', 'bidang_tujuan_id' => $this->bidang->id]);
        $this->actingAs($this->user)->post(route('surat-masuk.store'), $data);

        $data2 = $this->getValidSuratMasukData(['no_agenda' => '2', 'bidang_tujuan_id' => $bidang2->id]);
        $this->actingAs($this->user)->post(route('surat-masuk.store'), $data2);

        $response = $this->actingAs($this->user)
            ->get('/surat-masuk/search?bidang_id=' . $this->bidang->id);

        $response->assertOk();
        $data = $response->json();
        $this->assertEquals(1, $data['total']);
    }

    public function test_surat_keluar_search_endpoint_requires_auth()
    {
        $response = $this->get('/surat-keluar/search');
        $response->assertRedirect('/login');
    }

    public function test_surat_keluar_search_returns_json()
    {
        $data = $this->getValidSuratKeluarData(['no_agenda' => '1']);
        $this->actingAs($this->user)->post(route('surat-keluar.store'), $data);

        $response = $this->actingAs($this->user)
            ->get('/surat-keluar/search?search=Test');

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                '*' => ['id', 'no_agenda', 'no_surat', 'perihal', 'tujuan', 'status']
            ],
            'total'
        ]);
    }

    public function test_surat_keluar_search_filters_by_jenis()
    {
        $data = $this->getValidSuratKeluarData(['no_agenda' => '1', 'jenis_surat' => 'SPT']);
        $this->actingAs($this->user)->post(route('surat-keluar.store'), $data);

        $data2 = $this->getValidSuratKeluarData(['no_agenda' => '2', 'jenis_surat' => 'SK']);
        $this->actingAs($this->user)->post(route('surat-keluar.store'), $data2);

        $response = $this->actingAs($this->user)
            ->get('/surat-keluar/search?jenis_surat_filter=SPT');

        $response->assertOk();
        $data = $response->json();
        $this->assertEquals(1, $data['total']);
        $this->assertEquals('SPT', $data['data'][0]['jenis_surat']);
    }

    public function test_surat_keluar_search_filters_by_status()
    {
        $data = $this->getValidSuratKeluarData(['no_agenda' => '1', 'status' => 'selesai']);
        $this->actingAs($this->user)->post(route('surat-keluar.store'), $data);

        $data2 = $this->getValidSuratKeluarData(['no_agenda' => '2', 'status' => 'draft']);
        $this->actingAs($this->user)->post(route('surat-keluar.store'), $data2);

        $response = $this->actingAs($this->user)
            ->get('/surat-keluar/search?status=selesai');

        $response->assertOk();
        $data = $response->json();
        $this->assertEquals(1, $data['total']);
        $this->assertEquals('selesai', $data['data'][0]['status']);
    }
}