<?php

namespace Tests\Feature;

use App\Models\Bidang;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use App\Models\User;
use App\Services\DashboardKearsipanService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Tests\TestCase;

class DashboardExportTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->tataUsaha()->create();
    }

    public function test_export_excel_memuat_judul_periode_dan_hanya_data_terfilter(): void
    {
        $bidangA = $this->buatBidang('Bidang A');
        $bidangB = $this->buatBidang('Bidang B');

        $this->buatSuratMasuk($bidangA, '1', '2026-01-10');
        $this->buatSuratMasuk($bidangB, '2', '2026-02-10');
        $this->buatSuratMasuk($bidangA, '3', '2027-01-10');

        $this->buatSuratKeluar($bidangA, '1', '2026-03-10');
        $this->buatSuratKeluar($bidangB, '2', '2026-04-10');

        $response = $this->actingAs($this->user)->get(route('dashboard.export.excel', [
            'start_date' => '2026-01-01',
            'end_date' => '2026-12-31',
            'bidang_id' => $bidangA->id,
        ]));

        $response->assertOk()->assertDownload();

        $spreadsheet = IOFactory::load($response->getFile()->getPathname());

        $suratMasuk = $spreadsheet->getSheetByName('Surat Masuk');
        $this->assertNotNull($suratMasuk);
        $this->assertSame('REKAP SURAT DINAS PERHUBUNGAN PROVINSI LAMPUNG', $suratMasuk->getCell('A1')->getValue());
        $this->assertStringContainsString('Periode:', (string) $suratMasuk->getCell('A2')->getValue());
        $this->assertStringContainsString('2026', (string) $suratMasuk->getCell('A2')->getValue());
        $this->assertSame('No Agenda', $suratMasuk->getCell('A3')->getValue());
        $this->assertTrue($suratMasuk->getStyle('A3')->getFont()->getBold());

        // Header di baris 3 + satu surat bidang A pada rentang 2026.
        $this->assertSame(4, $suratMasuk->getHighestRow());
        $this->assertSame('1', (string) $suratMasuk->getCell('A4')->getValue());

        $spt = $spreadsheet->getSheetByName('SPT');
        $this->assertNotNull($spt);
        $this->assertSame(4, $spt->getHighestRow());
        $this->assertSame('1', (string) $spt->getCell('A4')->getValue());

        // Surat bidang B dan surat di luar rentang tidak ikut terekspor.
        $this->assertSame('Instansi Bidang A', $suratMasuk->getCell('F4')->getValue());
    }

    public function test_export_pdf_memuat_kop_judul_ttd_dan_nomor_halaman(): void
    {
        $bidang = $this->buatBidang('Bidang A');
        $this->buatSuratMasuk($bidang, '1', '2026-01-10');
        $this->buatSuratKeluar($bidang, '1', '2026-02-10');
        $this->buatSuratKeluar($this->buatBidang('Bidang B'), '2', '2026-02-11');

        $response = $this->actingAs($this->user)->get(route('dashboard.export.pdf', [
            'start_date' => '2026-01-01',
            'end_date' => '2026-12-31',
            'bidang_id' => $bidang->id,
        ]));

        $response->assertOk()->assertDownload();
        $this->assertStringStartsWith('%PDF', $response->getContent());
        $this->assertGreaterThan(1000, strlen($response->getContent()));

        $html = view('kearsipan.rekap-pdf', [
            'title' => 'Rekap Surat Periode Tahun 2026',
            'periodeStr' => 'Tahun 2026',
            'bidang' => 'Bidang A',
            'penandatangan' => ['nama' => 'Kepala Dinas Uji', 'nip' => '199001012020011001'],
            'data' => [
                'Surat Masuk' => ['type' => 'masuk', 'items' => collect()],
                'SPT' => ['type' => 'keluar', 'items' => collect()],
            ],
        ])->render();

        $this->assertStringContainsString('Pemerintah Provinsi Lampung', $html);
        $this->assertStringContainsString('Dinas Perhubungan', $html);
        $this->assertStringContainsString('Rekap Surat Periode Tahun 2026', $html);
        $this->assertStringContainsString('Bidang A', $html);
        $this->assertStringContainsString('Kepala Dinas Perhubungan', $html);
        $this->assertStringContainsString('Kepala Dinas Uji', $html);
        $this->assertStringContainsString('NIP. 199001012020011001', $html);
        $this->assertStringContainsString('Halaman 1 dari 2', $html);
        $this->assertStringContainsString('Halaman 2 dari 2', $html);
    }

    public function test_data_export_menghormati_filter_bidang(): void
    {
        $bidangA = $this->buatBidang('Bidang A');
        $bidangB = $this->buatBidang('Bidang B');

        $this->buatSuratMasuk($bidangA, '1', '2026-01-10');
        $this->buatSuratMasuk($bidangB, '2', '2026-01-11');
        $this->buatSuratKeluar($bidangA, '1', '2026-01-12');
        $this->buatSuratKeluar($bidangB, '2', '2026-01-13');

        $service = app(DashboardKearsipanService::class);

        $masukA = $service->getSuratMasukForExport('2026-01-01', '2026-12-31', $bidangA->id);
        $this->assertCount(1, $masukA['Surat Masuk']['items']);

        $keluarA = $service->getSuratKeluarForExport('2026-01-01', '2026-12-31', $bidangA->id);
        $this->assertCount(1, $keluarA['SPT']['items']);

        $keluarTanpaFilter = $service->getSuratKeluarForExport('2026-01-01', '2026-12-31');
        $this->assertCount(2, $keluarTanpaFilter['SPT']['items']);
    }

    public function test_export_menolak_bidang_yang_tidak_ada(): void
    {
        $this->actingAs($this->user)
            ->from(route('dashboard'))
            ->get(route('dashboard.export.excel', ['bidang_id' => 99999]))
            ->assertRedirect(route('dashboard'))
            ->assertSessionHasErrors('bidang_id');
    }

    private function buatBidang(string $nama): Bidang
    {
        return Bidang::create([
            'nama_bidang' => $nama,
            'kode_bidang' => strtoupper(substr(md5($nama), 0, 5)),
        ]);
    }

    private function buatSuratMasuk(Bidang $bidang, string $noAgenda, string $tanggal): SuratMasuk
    {
        return SuratMasuk::create([
            'no_agenda' => $noAgenda,
            'tahun' => (int) substr($tanggal, 0, 4),
            'jenis_surat' => 'Surat Masuk',
            'no_surat' => '001/TEST/'.$noAgenda,
            'tanggal_surat' => $tanggal,
            'tanggal_terima' => $tanggal,
            'pengirim' => 'Instansi '.$bidang->nama_bidang,
            'perihal' => 'Perihal Test',
            'bidang_tujuan_id' => $bidang->id,
            'status_disposisi' => 'belum',
            'diinput_oleh' => $this->user->id,
        ]);
    }

    private function buatSuratKeluar(Bidang $bidang, string $noAgenda, string $tanggal): SuratKeluar
    {
        return SuratKeluar::create([
            'no_agenda' => $noAgenda,
            'tahun' => (int) substr($tanggal, 0, 4),
            'jenis_surat' => 'SPT',
            'no_surat' => '800/1/TEST/'.$noAgenda,
            'tanggal_surat' => $tanggal,
            'tanggal_terima' => $tanggal,
            'tujuan' => 'Instansi Tujuan',
            'perihal' => 'Perihal Test',
            'bidang_id' => $bidang->id,
            'dibuat_oleh' => $this->user->id,
        ]);
    }
}
