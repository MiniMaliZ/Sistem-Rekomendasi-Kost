<?php

namespace Tests\Unit;

use App\Models\Kost;
use App\Services\HybridAhpSawService;
use Illuminate\Support\Collection;
use Tests\TestCase;

class HybridAhpSawServiceTest extends TestCase
{
    public function test_model_uses_five_literature_based_criteria_without_cctv(): void
    {
        $service = app(HybridAhpSawService::class);
        $criteriaKeys = array_column($service->criteria(), 'key');

        $this->assertArrayNotHasKey('cctv', $service->facilityOptions());
        $this->assertCount(5, $criteriaKeys);
        $this->assertNotContains('keamanan_cctv', $criteriaKeys);
        $this->assertSame([
            'harga',
            'jarak_kampus',
            'luas_kamar',
            'kecocokan_fasilitas',
            'listrik_termasuk',
        ], $criteriaKeys);
    }

    public function test_all_ahp_scenarios_are_consistent_and_use_five_criteria(): void
    {
        $service = app(HybridAhpSawService::class);

        foreach (array_keys($service->scenarios()) as $scenario) {
            $result = $service->calculateAhp($scenario);

            $this->assertTrue($result['is_consistent']);
            $this->assertLessThan(0.1, $result['cr']);
            $this->assertSame(1.12, $result['ri']);
            $this->assertCount(5, $result['weights']);
            $this->assertEqualsWithDelta(1.0, array_sum($result['weights']), 0.0001);
        }
    }

    public function test_scenario_priorities_match_their_labels(): void
    {
        $service = app(HybridAhpSawService::class);
        $default = $service->calculateAhp('default')['weight_map'];
        $hemat = $service->calculateAhp('hemat')['weight_map'];
        $fasilitas = $service->calculateAhp('fasilitas')['weight_map'];

        $this->assertEqualsWithDelta($default['harga'], $default['jarak_kampus'], 0.0001);
        $this->assertSame('harga', array_search(max($hemat), $hemat, true));
        $this->assertSame('kecocokan_fasilitas', array_search(max($fasilitas), $fasilitas, true));
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
