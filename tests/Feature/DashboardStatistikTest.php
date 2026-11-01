<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardStatistikTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function dashboard_statistik_endpoint_returns_correct_json_format()
    {
        $response = $this->get('/dashboard/statistik');

        $response->assertStatus(200);
        $data = $response->json();

        // Wajib ada 4 key utama
        $this->assertArrayHasKey('total_masuk', $data);
        $this->assertArrayHasKey('total_keluar', $data);
        $this->assertArrayHasKey('per_bulan', $data);
        $this->assertArrayHasKey('per_bidang', $data);

        // total_masuk dan total_keluar harus number > 0
        $this->assertIsInt($data['total_masuk']);
        $this->assertIsInt($data['total_keluar']);
        $this->assertGreaterThan(0, $data['total_masuk']);
        $this->assertGreaterThan(0, $data['total_keluar']);

        // per_bulan harus array dengan 12 item
        $this->assertIsArray($data['per_bulan']);
        $this->assertCount(12, $data['per_bulan']);

        // Setiap item per_bulan harus memiliki bulan, masuk, keluar
        foreach ($data['per_bulan'] as $item) {
            $this->assertArrayHasKey('bulan', $item);
            $this->assertArrayHasKey('masuk', $item);
            $this->assertArrayHasKey('keluar', $item);
            $this->assertIsString($item['bulan']);
            $this->assertIsInt($item['masuk']);
            $this->assertIsInt($item['keluar']);
        }

        // per_bidang harus array dengan data
        $this->assertIsArray($data['per_bidang']);
        $this->assertGreaterThan(0, count($data['per_bidang']));

        // Setiap item per_bidang harus memiliki bidang dan count
        foreach ($data['per_bidang'] as $item) {
            $this->assertArrayHasKey('bidang', $item);
            $this->assertArrayHasKey('count', $item);
            $this->assertIsString($item['bidang']);
            $this->assertIsInt($item['count']);
            $this->assertGreaterThan(0, $item['count']);
        }
    }

    /** @test */
    public function dashboard_statistik_endpoint_akses_tanpa_login_boleh()
    {
        $response = $this->get('/dashboard/statistik');

        $response->assertStatus(200);
    }

    /** @test */
    public function dashboard_statistik_data_consistent()
    {
        $response = $this->get('/dashboard/statistik');
        $data = $response->json();

        // total_masuk harus sama dengan jumlah masuk dari per_bulan
        $totalDariPerBulan = array_sum(array_column($data['per_bulan'], 'masuk'));
        $this->assertEquals($data['total_masuk'], $totalDariPerBulan);

        // total_keluar harus sama dengan jumlah keluar dari per_bulan
        $totalKeluarDariPerBulan = array_sum(array_column($data['per_bulan'], 'keluar'));
        $this->assertEquals($data['total_keluar'], $totalKeluarDariPerBulan);
    }
}