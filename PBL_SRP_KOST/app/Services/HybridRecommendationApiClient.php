<?php

namespace App\Services;

use App\Models\Kost;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class HybridRecommendationApiClient
{
    public function recommend(Collection $kosts, array $preferences = [], string $scenario = 'default', int $limit = 10): array
    {
        $baseUrl = rtrim((string) config('services.recommendation_api.url'), '/');
        $timeout = (int) config('services.recommendation_api.timeout', 15);

        if ($baseUrl === '') {
            throw new RuntimeException('RECOMMENDATION_API_URL belum diisi.');
        }

        $payload = [
            'alternatives' => $kosts
                ->map(fn (Kost $kost): array => $this->serializeKost($kost))
                ->values()
                ->all(),
            'preferences' => $preferences,
            'scenario' => $scenario,
            'limit' => $limit,
        ];

        try {
            $response = Http::timeout($timeout)->post($baseUrl . '/recommend', $payload);
        } catch (ConnectionException $exception) {
            throw new RuntimeException('FastAPI rekomendasi tidak bisa dihubungi: ' . $exception->getMessage());
        }

        if ($response->failed()) {
            throw new RuntimeException(sprintf(
                'FastAPI rekomendasi gagal: HTTP %s - %s',
                $response->status(),
                $response->body()
            ));
        }

        $result = $response->json();
        if (! is_array($result) || ! isset($result['ahp'], $result['rows'], $result['summary'])) {
            throw new RuntimeException('Response FastAPI rekomendasi tidak sesuai format.');
        }

        return $this->attachKostModels($result, $kosts);
    }

    public function health(): array
    {
        $baseUrl = rtrim((string) config('services.recommendation_api.url'), '/');
        $timeout = (int) config('services.recommendation_api.timeout', 15);

        try {
            $response = Http::timeout($timeout)->get($baseUrl . '/health');
        } catch (ConnectionException $exception) {
            return [
                'ok' => false,
                'message' => 'FastAPI tidak bisa dihubungi: ' . $exception->getMessage(),
            ];
        }

        return [
            'ok' => $response->ok(),
            'message' => $response->ok()
                ? 'FastAPI aktif'
                : sprintf('FastAPI mengembalikan HTTP %s', $response->status()),
        ];
    }

    private function serializeKost(Kost $kost): array
    {
        return [
            'id_kost' => (int) $kost->id_kost,
            'nama_kost' => (string) $kost->nama_kost,
            'harga' => (float) $kost->harga,
            'tipe_kos' => (string) $kost->tipe_kos,
            'sepesifikasi_tipe_kamar' => $kost->sepesifikasi_tipe_kamar,
            'fasilitas_kamar' => $kost->fasilitas_kamar,
            'fasilitas_kamar_mandi' => $kost->fasilitas_kamar_mandi,
            'fasilitas_umum' => $kost->fasilitas_umum,
            'fasilitas_parkir' => $kost->fasilitas_parkir,
            'tempat_terdekat' => $kost->tempat_terdekat,
            'peraturan_kos' => $kost->peraturan_kos,
            'link_original' => $kost->link_original,
        ];
    }

    private function attachKostModels(array $result, Collection $kosts): array
    {
        $kostById = $kosts->keyBy('id_kost');

        $result['rows'] = collect($result['rows'])
            ->map(function (array $row) use ($kostById): array {
                $row['kost'] = $kostById->get($row['id_kost']);

                return $row;
            })
            ->values()
            ->all();

        return $result;
    }
}
