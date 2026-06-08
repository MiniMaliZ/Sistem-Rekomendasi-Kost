<?php

namespace Tests\Unit;

use App\Models\Kost;
use App\Services\HybridAhpSawService;
use Illuminate\Support\Collection;
use Tests\TestCase;

class HybridAhpSawServiceTest extends TestCase
{
    public function test_ahp_weights_are_consistent_and_sum_to_one(): void
    {
        $result = app(HybridAhpSawService::class)->calculateAhp();

        $this->assertTrue($result['is_consistent']);
        $this->assertLessThan(0.1, $result['cr']);
        $this->assertEqualsWithDelta(1.0, array_sum($result['weights']), 0.0001);
    }

    public function test_recommendation_ranks_filtered_kost_with_ahp_saw_score(): void
    {
        $kosts = new Collection([
            (new Kost())->forceFill([
                'id_kost' => 1,
                'nama_kost' => 'Kost Hemat Dekat Kampus',
                'harga' => 700000,
                'tipe_kos' => 'Kos Putri',
                'sepesifikasi_tipe_kamar' => '3 x 3 meter, Termasuk listrik',
                'fasilitas_kamar' => 'Kasur, Lemari / Storage',
                'fasilitas_kamar_mandi' => 'K. Mandi Dalam',
                'fasilitas_umum' => 'WiFi, Dapur, CCTV',
                'fasilitas_parkir' => 'Parkir Motor',
                'tempat_terdekat' => 'Universitas Brawijaya (500 m)',
            ]),
            (new Kost())->forceFill([
                'id_kost' => 2,
                'nama_kost' => 'Kost Mahal Jauh',
                'harga' => 1800000,
                'tipe_kos' => 'Kos Putri',
                'sepesifikasi_tipe_kamar' => '2 x 2 meter, Tidak termasuk listrik',
                'fasilitas_kamar' => 'Kasur',
                'fasilitas_kamar_mandi' => 'K. Mandi Luar',
                'fasilitas_umum' => 'Dapur',
                'fasilitas_parkir' => 'Parkir Motor',
                'tempat_terdekat' => 'Universitas Brawijaya (4 km)',
            ]),
        ]);

        $result = app(HybridAhpSawService::class)->recommend(
            $kosts,
            [
                'budget_max' => 1500000,
                'max_distance' => 2,
                'tipe_kos' => 'Kos Putri',
                'facilities' => ['wifi', 'kamar_mandi_dalam'],
                'require_all_facilities' => false,
            ],
            'default',
            10
        );

        $this->assertCount(1, $result['rows']);
        $this->assertSame('Kost Hemat Dekat Kampus', $result['rows'][0]['nama_kost']);
        $this->assertSame(1, $result['rows'][0]['rank']);
        $this->assertGreaterThan(0, $result['rows'][0]['score']);
    }
}
