<?php

namespace Tests\Unit;

use App\Models\Kost;
use App\Services\HybridRecommendationApiClient;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class HybridRecommendationApiClientTest extends TestCase
{
    public function test_client_posts_kost_payload_and_attaches_models_to_rows(): void
    {
        config()->set('services.recommendation_api.url', 'http://127.0.0.1:8001');

        Http::fake([
            '127.0.0.1:8001/recommend' => Http::response([
                'ahp' => [
                    'criteria' => [],
                    'weight_map' => [],
                    'cr' => 0.01,
                    'is_consistent' => true,
                ],
                'rows' => [
                    [
                        'id_kost' => 7,
                        'rank' => 1,
                        'score' => 0.9,
                        'score_percent' => 100,
                    ],
                ],
                'summary' => [
                    'total_alternatives' => 1,
                    'filtered_alternatives' => 1,
                    'max_score' => 0.9,
                    'avg_score' => 0.9,
                ],
            ]),
        ]);

        $kost = (new Kost())->forceFill([
            'id_kost' => 7,
            'nama_kost' => 'Kost Test Lowokwaru Malang',
            'harga' => 1000000,
            'tipe_kos' => 'Kos Putri',
            'sepesifikasi_tipe_kamar' => '3 x 3 meter, Termasuk listrik',
        ]);

        $result = app(HybridRecommendationApiClient::class)->recommend(
            collect([$kost]),
            ['budget_max' => 1500000],
            'default',
            10
        );

        Http::assertSent(fn ($request): bool => $request->url() === 'http://127.0.0.1:8001/recommend'
            && $request['scenario'] === 'default'
            && $request['limit'] === 10
            && $request['alternatives'][0]['id_kost'] === 7);

        $this->assertSame($kost, $result['rows'][0]['kost']);
        $this->assertSame(1, $result['rows'][0]['rank']);
    }
}
