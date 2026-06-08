<?php

namespace App\Livewire\Admin;

use App\Models\Kost;
use App\Services\HybridAhpSawService;
use App\Services\HybridRecommendationApiClient;
use Livewire\Component;
use RuntimeException;

class HybridRecommendation extends Component
{
    public string $draftBudgetMax = '1500000';
    public string $draftMaxDistance = '3';
    public string $draftTipeKos = '';
    public string $draftScenario = 'default';
    public bool $draftRequireAllFacilities = false;
    public string $draftTopN = '10';
    public array $draftSelectedFacilities = [
        'wifi',
        'kamar_mandi_dalam',
        'parkir_motor',
        'dapur',
    ];

    public string $budgetMax = '1500000';
    public string $maxDistance = '3';
    public string $tipeKos = '';
    public string $scenario = 'default';
    public bool $requireAllFacilities = false;
    public int $topN = 10;
    public ?string $apiError = null;
    public string $apiStatus = 'unknown';
    public array $selectedFacilities = [
        'wifi',
        'kamar_mandi_dalam',
        'parkir_motor',
        'dapur',
    ];
    public array $result = [];

    public function mount(): void
    {
        $this->applyPreferences();
    }

    public function resetPreferences(): void
    {
        $this->draftBudgetMax = '1500000';
        $this->draftMaxDistance = '3';
        $this->draftTipeKos = '';
        $this->draftScenario = 'default';
        $this->draftRequireAllFacilities = false;
        $this->draftTopN = '10';
        $this->draftSelectedFacilities = [
            'wifi',
            'kamar_mandi_dalam',
            'parkir_motor',
            'dapur',
        ];
    }

    public function applyPreferences(): void
    {
        $this->syncAppliedPreferences();
        $this->refreshRecommendation();
    }

    public function render()
    {
        $service = app(HybridAhpSawService::class);
        $result = $this->result ?: $this->emptyResult($service, Kost::query()->count());

        return view('livewire.admin.hybrid-recommendation', [
            'result' => $result,
            'facilityOptions' => $service->facilityOptions(),
            'scenarios' => $service->scenarios(),
            'apiStatus' => $this->apiStatus,
            'apiError' => $this->apiError,
            'recommendationApiUrl' => config('services.recommendation_api.url'),
            'hasPendingChanges' => $this->hasPendingChanges(),
        ])->layout('components.layouts.admin', [
            'title' => 'Hybrid AHP-SAW',
            'breadcrumb' => ['Hybrid AHP-SAW' => route('admin.rekomendasi-hybrid')],
        ]);
    }

    private function refreshRecommendation(): void
    {
        $service = app(HybridAhpSawService::class);
        $kosts = Kost::query()
            ->orderBy('id_kost')
            ->get();

        try {
            $apiClient = app(HybridRecommendationApiClient::class);
            $this->result = $this->prepareResultForLivewire(
                $apiClient->recommend($kosts, $this->appliedPreferences(), $this->scenario, $this->topN)
            );
            $this->apiStatus = 'online';
            $this->apiError = null;
        } catch (RuntimeException $exception) {
            $this->result = $this->emptyResult($service, $kosts->count());
            $this->apiStatus = 'offline';
            $this->apiError = $exception->getMessage();
        }
    }

    private function syncAppliedPreferences(): void
    {
        $this->budgetMax = $this->draftBudgetMax;
        $this->maxDistance = $this->draftMaxDistance;
        $this->tipeKos = $this->draftTipeKos;
        $this->scenario = $this->draftScenario;
        $this->requireAllFacilities = $this->draftRequireAllFacilities;
        $this->topN = (int) $this->draftTopN;
        $this->selectedFacilities = $this->normalizeFacilities($this->draftSelectedFacilities);
        $this->draftSelectedFacilities = $this->selectedFacilities;
    }

    private function appliedPreferences(): array
    {
        return [
            'budget_max' => $this->budgetMax,
            'max_distance' => $this->maxDistance,
            'tipe_kos' => $this->tipeKos,
            'facilities' => $this->selectedFacilities,
            'require_all_facilities' => $this->requireAllFacilities,
        ];
    }

    private function hasPendingChanges(): bool
    {
        return $this->budgetMax !== $this->draftBudgetMax
            || $this->maxDistance !== $this->draftMaxDistance
            || $this->tipeKos !== $this->draftTipeKos
            || $this->scenario !== $this->draftScenario
            || $this->requireAllFacilities !== $this->draftRequireAllFacilities
            || $this->topN !== (int) $this->draftTopN
            || $this->normalizeFacilities($this->selectedFacilities) !== $this->normalizeFacilities($this->draftSelectedFacilities);
    }

    private function normalizeFacilities(array $facilities): array
    {
        $facilities = array_values(array_unique(array_map('strval', $facilities)));
        sort($facilities);

        return $facilities;
    }

    private function prepareResultForLivewire(array $result): array
    {
        $result['rows'] = array_map(function (array $row): array {
            $kost = $row['kost'] ?? null;
            $row['spesifikasi_tipe_kamar'] = $kost instanceof Kost
                ? $kost->sepesifikasi_tipe_kamar
                : ($row['spesifikasi_tipe_kamar'] ?? '-');
            unset($row['kost']);

            return $row;
        }, $result['rows'] ?? []);

        return $result;
    }

    private function emptyResult(HybridAhpSawService $service, int $totalAlternatives): array
    {
        return [
            'ahp' => $service->calculateAhp($this->scenario),
            'rows' => [],
            'summary' => [
                'total_alternatives' => $totalAlternatives,
                'filtered_alternatives' => 0,
                'max_score' => 0.0,
                'avg_score' => 0.0,
            ],
        ];
    }
}
