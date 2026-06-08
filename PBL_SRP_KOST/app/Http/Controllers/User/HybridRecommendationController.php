<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Favorit;
use App\Models\Kost;
use App\Services\HybridAhpSawService;
use App\Services\HybridRecommendationApiClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RuntimeException;

class HybridRecommendationController extends Controller
{
    private const DEFAULT_FACILITIES = [
        'wifi',
        'kamar_mandi_dalam',
        'parkir_motor',
        'dapur',
    ];

    public function index(
        Request $request,
        HybridAhpSawService $hybridService,
        HybridRecommendationApiClient $apiClient
    ) {
        $facilityOptions = $hybridService->facilityOptions();
        $scenarios = $this->scenarioLabels($hybridService->scenarios());
        $submitted = $request->boolean('submitted');
        $form = $this->formState($request, array_keys($facilityOptions), array_keys($scenarios), $submitted);
        $recommendations = [];
        $summary = null;
        $apiError = null;

        if ($submitted) {
            try {
                $kosts = Kost::query()
                    ->with(['fotoKost', 'feedback'])
                    ->orderBy('id_kost')
                    ->get();

                $result = $apiClient->recommend(
                    $kosts,
                    [
                        'budget_max' => $form['budget_max'],
                        'max_distance' => $form['max_distance'],
                        'tipe_kos' => $form['tipe_kos'],
                        'facilities' => $form['facilities'],
                        'require_all_facilities' => $form['require_all_facilities'],
                    ],
                    $form['scenario'],
                    12
                );

                $favoriteIds = $this->favoriteIds();
                $recommendations = collect($result['rows'])
                    ->map(fn (array $row): array => $this->prepareRecommendation($row, $favoriteIds, $facilityOptions))
                    ->values()
                    ->all();
                $summary = $result['summary'];
            } catch (RuntimeException $exception) {
                $apiError = 'Sistem rekomendasi sedang belum aktif. Coba jalankan FastAPI lalu cari ulang.';
            }
        }

        return view('user.rekomendasi-kost', [
            'facilityOptions' => $facilityOptions,
            'scenarios' => $scenarios,
            'form' => $form,
            'submitted' => $submitted,
            'recommendations' => $recommendations,
            'summary' => $summary,
            'apiError' => $apiError,
        ]);
    }

    private function formState(Request $request, array $facilityKeys, array $scenarioKeys, bool $submitted): array
    {
        $facilities = $request->input('facilities', $submitted ? [] : self::DEFAULT_FACILITIES);
        $facilities = is_array($facilities) ? $facilities : [$facilities];
        $facilities = array_values(array_intersect($facilities, $facilityKeys));

        $scenario = (string) $request->input('scenario', 'default');
        if (! in_array($scenario, $scenarioKeys, true)) {
            $scenario = 'default';
        }

        $tipeKos = (string) $request->input('tipe_kos', '');
        if (! in_array($tipeKos, ['', 'Kos Putra', 'Kos Putri', 'Kos Campur'], true)) {
            $tipeKos = '';
        }

        return [
            'budget_max' => max(0, (int) $request->input('budget_max', 1500000)),
            'max_distance' => max(0, (float) $request->input('max_distance', 3)),
            'tipe_kos' => $tipeKos,
            'facilities' => $facilities,
            'require_all_facilities' => $request->boolean('require_all_facilities'),
            'scenario' => $scenario,
        ];
    }

    private function scenarioLabels(array $scenarios): array
    {
        return [
            'default' => $scenarios['default'] ?? 'Seimbang',
            'hemat' => 'Lebih hemat',
            'fasilitas' => 'Fasilitas lengkap',
        ];
    }

    private function prepareRecommendation(array $row, array $favoriteIds, array $facilityOptions): array
    {
        $kost = $row['kost'] ?? null;
        $matchedFacilities = collect($row['matched_facilities'] ?? [])
            ->map(fn (string $key): string => $facilityOptions[$key]['label'] ?? $key)
            ->values()
            ->all();

        return [
            'id' => (int) $row['id_kost'],
            'nama' => (string) $row['nama_kost'],
            'tipe' => $this->tipeLabel((string) $row['tipe_kos']),
            'tipe_raw' => (string) $row['tipe_kos'],
            'harga_format' => 'Rp. ' . number_format((float) $row['harga'], 0, ',', '.'),
            'lokasi' => $this->lokasiFromName((string) $row['nama_kost']),
            'jarak' => $row['jarak_kampus_asli'] !== null
                ? number_format((float) $row['jarak_kampus_asli'], 2) . ' km'
                : null,
            'foto' => $kost instanceof Kost && $kost->fotoKost?->foto_bangunan
                ? $kost->fotoKost->foto_bangunan_url
                : asset('images/kost1.png'),
            'rating' => $kost instanceof Kost && $kost->relationLoaded('feedback') && $kost->feedback->isNotEmpty()
                ? round((float) $kost->feedback->avg('rating'), 1)
                : 0.0,
            'ulasan' => $kost instanceof Kost && $kost->relationLoaded('feedback') ? $kost->feedback->count() : 0,
            'spesifikasi' => $kost instanceof Kost ? ($kost->sepesifikasi_tipe_kamar ?? '-') : '-',
            'matched_facilities' => $matchedFacilities,
            'matched_facility_count' => (int) ($row['matched_facility_count'] ?? 0),
            'is_favorit' => in_array((int) $row['id_kost'], $favoriteIds, true),
        ];
    }

    private function favoriteIds(): array
    {
        if (! Auth::check()) {
            return [];
        }

        return Favorit::query()
            ->where('id_user', Auth::id())
            ->pluck('id_kost')
            ->map(fn ($id): int => (int) $id)
            ->all();
    }

    private function tipeLabel(string $tipeKos): string
    {
        $lower = strtolower($tipeKos);

        return match (true) {
            str_contains($lower, 'putra') => 'PUTRA',
            str_contains($lower, 'putri') => 'PUTRI',
            str_contains($lower, 'campur') => 'CAMPUR',
            default => strtoupper($tipeKos ?: 'KOST'),
        };
    }

    private function lokasiFromName(string $namaKost): string
    {
        foreach (['Lowokwaru', 'Blimbing', 'Klojen', 'Sukun', 'Kedungkandang', 'Karang Ploso', 'Dau'] as $kecamatan) {
            if (stripos($namaKost, $kecamatan) !== false) {
                return $kecamatan . ', Malang';
            }
        }

        return 'Malang';
    }
}
