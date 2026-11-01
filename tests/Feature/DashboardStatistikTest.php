<?php

namespace Tests\Feature;

use App\Models\Bidang;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardStatistikTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->tataUsaha()->create();
    }

    public function test_total_surat_benar_untuk_rentang_tanggal(): void
    {
        $bidang = $this->buatBidang('Bidang A');

        // Dalam rentang 2026
        $this->buatSuratMasuk($bidang, ['no_agenda' => '1', 'tanggal_surat' => '2026-01-10']);
        $this->buatSuratMasuk($bidang, ['no_agenda' => '2', 'tanggal_surat' => '2026-03-15']);
        $this->buatSuratKeluar($bidang, ['no_agenda' => '1', 'tanggal_surat' => '2026-02-05']);

        // Di luar rentang 2026
        $this->buatSuratMasuk($bidang, ['no_agenda' => '3', 'tanggal_surat' => '2025-12-31']);
        $this->buatSuratKeluar($bidang, ['no_agenda' => '2', 'tanggal_surat' => '2027-01-01']);

        $response = $this->actingAs($this->user)->getJson(
            route('dashboard.statistik', [
                'start' => '2026-01-01',
                'end' => '2026-12-31',
            ])
        );

        $response->assertOk()
            ->assertJsonPath('total_masuk', 2)
            ->assertJsonPath('total_keluar', 1)
            ->assertJsonCount(12, 'per_bulan');

        $perBulan = collect($response->json('per_bulan'));

        $this->assertSame(1, $perBulan->firstWhere('bulan', '2026-01')['masuk']);
        $this->assertSame(1, $perBulan->firstWhere('bulan', '2026-02')['keluar']);
        $this->assertSame(1, $perBulan->firstWhere('bulan', '2026-03')['masuk']);
        $this->assertSame(0, $perBulan->firstWhere('bulan', '2026-04')['masuk']);
    }

    public function test_filter_bidang_mempersempit_hasil(): void
    {
        $bidangA = $this->buatBidang('Bidang A');
        $bidangB = $this->buatBidang('Bidang B');

        $this->buatSuratMasuk($bidangA, ['no_agenda' => '1', 'tanggal_surat' => '2026-04-01']);
        $this->buatSuratMasuk($bidangA, ['no_agenda' => '2', 'tanggal_surat' => '2026-04-02']);
        $this->buatSuratMasuk($bidangB, ['no_agenda' => '3', 'tanggal_surat' => '2026-04-03']);
        $this->buatSuratKeluar($bidangA, ['no_agenda' => '1', 'tanggal_surat' => '2026-04-04']);
        $this->buatSuratKeluar($bidangB, ['no_agenda' => '2', 'tanggal_surat' => '2026-04-05']);

        $response = $this->actingAs($this->user)->getJson(
            route('dashboard.statistik', [
                'start' => '2026-01-01',
                'end' => '2026-12-31',
                'bidang_id' => $bidangA->id,
            ])
        );

        $response->assertOk()
            ->assertJsonPath('total_masuk', 2)
            ->assertJsonPath('total_keluar', 1);

        $perBidang = collect($response->json('per_bidang'));

        $this->assertCount(1, $perBidang);
        $this->assertSame($bidangA->id, $perBidang->first()['bidang_id']);
        $this->assertSame(2, $perBidang->first()['masuk']);
        $this->assertSame(1, $perBidang->first()['keluar']);
    }

    public function test_user_belum_login_dapat_302_ke_login(): void
    {
        $this->get(route('dashboard.statistik'))->assertRedirect('/login');
    }

    private function buatBidang(string $nama): Bidang
    {
        return Bidang::create([
            'nama_bidang' => $nama,
            'kode_bidang' => strtoupper(substr(md5($nama), 0, 5)),
        ]);
    }

    private function buatSuratMasuk(Bidang $bidang, array $overrides = []): SuratMasuk
    {
        $tanggal = $overrides['tanggal_surat'] ?? '2026-01-01';

        return SuratMasuk::create(array_merge([
            'no_agenda' => '1',
            'tahun' => (int) substr($tanggal, 0, 4),
            'jenis_surat' => 'Surat Masuk',
            'no_surat' => '001/TEST/'.substr($tanggal, 0, 4),
            'tanggal_surat' => $tanggal,
            'tanggal_terima' => $tanggal,
            'pengirim' => 'Instansi Test',
            'perihal' => 'Perihal Test',
            'bidang_tujuan_id' => $bidang->id,
            'status_disposisi' => 'belum',
            'diinput_oleh' => $this->user->id,
        ], $overrides));
    }

    private function buatSuratKeluar(Bidang $bidang, array $overrides = []): SuratKeluar
    {
        $tanggal = $overrides['tanggal_surat'] ?? '2026-01-01';

        return SuratKeluar::create(array_merge([
            'no_agenda' => '1',
            'tahun' => (int) substr($tanggal, 0, 4),
            'jenis_surat' => 'SPT',
            'no_surat' => '800/1/TEST/'.substr($tanggal, 0, 4),
            'tanggal_surat' => $tanggal,
            'tanggal_terima' => $tanggal,
            'tujuan' => 'Instansi Tujuan',
            'perihal' => 'Perihal Test',
            'bidang_id' => $bidang->id,
            'dibuat_oleh' => $this->user->id,
        ], $overrides));
    }
}
