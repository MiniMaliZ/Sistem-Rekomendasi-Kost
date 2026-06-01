<?php

namespace App\Livewire\Admin;

use App\Models\Kost;
use App\Services\HybridAhpSawService;
use Livewire\Component;

class HybridRecommendation extends Component
{
    public string $budgetMax = '1500000';
    public string $maxDistance = '3';
    public string $tipeKos = '';
    public string $scenario = 'default';
    public bool $requireAllFacilities = false;
    public int $topN = 10;
    public array $selectedFacilities = [
        'wifi',
        'kamar_mandi_dalam',
        'parkir_motor',
        'dapur',
    ];

    public function toggleFacility(string $key): void
    {
        if (in_array($key, $this->selectedFacilities, true)) {
            $this->selectedFacilities = array_values(array_filter(
                $this->selectedFacilities,
                fn (string $selectedKey): bool => $selectedKey !== $key
            ));

            return;
        }

        $this->selectedFacilities[] = $key;
    }

    public function setTipeKos(string $tipeKos): void
    {
        $this->tipeKos = $tipeKos;
    }

    public function setScenario(string $scenario): void
    {
        $this->scenario = $scenario;
    }

    public function resetPreferences(): void
    {
        $this->budgetMax = '1500000';
        $this->maxDistance = '3';
        $this->tipeKos = '';
        $this->scenario = 'default';
        $this->requireAllFacilities = false;
        $this->topN = 10;
        $this->selectedFacilities = [
            'wifi',
            'kamar_mandi_dalam',
            'parkir_motor',
            'dapur',
        ];
    }

    public function render()
    {
        $service = app(HybridAhpSawService::class);
        $kosts = Kost::query()
            ->orderBy('id_kost')
            ->get();

        $result = $service->recommend(
            $kosts,
            [
                'budget_max' => $this->budgetMax,
                'max_distance' => $this->maxDistance,
                'tipe_kos' => $this->tipeKos,
                'facilities' => $this->selectedFacilities,
                'require_all_facilities' => $this->requireAllFacilities,
            ],
            $this->scenario,
            $this->topN
        );

        return view('livewire.admin.hybrid-recommendation', [
            'result' => $result,
            'facilityOptions' => $service->facilityOptions(),
            'scenarios' => $service->scenarios(),
        ])->layout('components.layouts.admin', [
            'title' => 'Hybrid AHP-SAW',
            'breadcrumb' => ['Hybrid AHP-SAW' => route('admin.rekomendasi-hybrid')],
        ]);
    }
}
