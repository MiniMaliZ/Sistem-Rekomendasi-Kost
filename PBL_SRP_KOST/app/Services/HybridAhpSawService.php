<?php

namespace App\Services;

use App\Models\Kost;
use Illuminate\Support\Collection;

class HybridAhpSawService
{
    private const RI_TABLE = [
        1 => 0.00,
        2 => 0.00,
        3 => 0.58,
        4 => 0.90,
        5 => 1.12,
        6 => 1.24,
        7 => 1.32,
        8 => 1.41,
        9 => 1.45,
        10 => 1.49,
    ];

    public function criteria(): array
    {
        return [
            ['key' => 'harga', 'name' => 'Harga', 'type' => 'cost'],
            ['key' => 'jarak_kampus', 'name' => 'Jarak Kampus', 'type' => 'cost'],
            ['key' => 'luas_kamar', 'name' => 'Luas Kamar', 'type' => 'benefit'],
            ['key' => 'kecocokan_fasilitas', 'name' => 'Kecocokan Fasilitas', 'type' => 'benefit'],
            ['key' => 'listrik_termasuk', 'name' => 'Listrik Termasuk', 'type' => 'benefit'],
        ];
    }

    public function scenarios(): array
    {
        return [
            'default' => 'Seimbang',
            'hemat' => 'Hemat',
            'fasilitas' => 'Fasilitas',
        ];
    }

    public function facilityOptions(): array
    {
        return [
            'wifi' => ['label' => 'WiFi / Internet', 'keywords' => ['wifi', 'wi-fi', 'internet']],
            'ac' => ['label' => 'AC', 'keywords' => ['ac']],
            'kamar_mandi_dalam' => ['label' => 'Kamar mandi dalam', 'keywords' => ['k. mandi dalam', 'kamar mandi dalam']],
            'parkir_motor' => ['label' => 'Parkir motor', 'keywords' => ['parkir motor', 'motor & sepeda']],
            'parkir_mobil' => ['label' => 'Parkir mobil', 'keywords' => ['parkir mobil']],
            'dapur' => ['label' => 'Dapur', 'keywords' => ['dapur']],
            'laundry' => ['label' => 'Laundry / mesin cuci', 'keywords' => ['laundry', 'mesin cuci']],
            'kulkas' => ['label' => 'Kulkas', 'keywords' => ['kulkas']],
            'kasur' => ['label' => 'Kasur', 'keywords' => ['kasur']],
            'lemari' => ['label' => 'Lemari', 'keywords' => ['lemari', 'storage']],
            'akses_24_jam' => ['label' => 'Akses 24 jam', 'keywords' => ['akses 24 jam']],
        ];
    }

    public function calculateAhp(string $scenario = 'default'): array
    {
        $criteria = $this->criteria();
        $matrix = $this->pairwiseMatrix($scenario);
        $size = count($matrix);
        $columnSums = array_fill(0, $size, 0.0);

        foreach ($matrix as $row) {
            foreach ($row as $index => $value) {
                $columnSums[$index] += $value;
            }
        }

        $normalizedMatrix = [];
        $weights = [];
        foreach ($matrix as $rowIndex => $row) {
            $normalizedMatrix[$rowIndex] = [];
            $rowTotal = 0.0;
            foreach ($row as $columnIndex => $value) {
                $normalizedValue = $columnSums[$columnIndex] > 0
                    ? $value / $columnSums[$columnIndex]
                    : 0.0;
                $normalizedMatrix[$rowIndex][$columnIndex] = $normalizedValue;
                $rowTotal += $normalizedValue;
            }
            $weights[$rowIndex] = $rowTotal / $size;
        }

        $weightedSumVector = [];
        foreach ($matrix as $rowIndex => $row) {
            $weightedSumVector[$rowIndex] = 0.0;
            foreach ($row as $columnIndex => $value) {
                $weightedSumVector[$rowIndex] += $value * $weights[$columnIndex];
            }
        }

        $lambdaValues = [];
        foreach ($weightedSumVector as $index => $value) {
            $lambdaValues[] = $weights[$index] > 0 ? $value / $weights[$index] : 0.0;
        }

        $lambdaMax = array_sum($lambdaValues) / max(count($lambdaValues), 1);
        $ci = $size > 1 ? ($lambdaMax - $size) / ($size - 1) : 0.0;
        $ri = self::RI_TABLE[$size] ?? 1.49;
        $cr = $ri > 0 ? $ci / $ri : 0.0;

        $weightMap = [];
        foreach ($criteria as $index => $criterion) {
            $weightMap[$criterion['key']] = $weights[$index] ?? 0.0;
        }

        return [
            'criteria' => $criteria,
            'matrix' => $matrix,
            'normalized_matrix' => $normalizedMatrix,
            'weights' => $weights,
            'weight_map' => $weightMap,
            'lambda_max' => $lambdaMax,
            'ci' => $ci,
            'ri' => $ri,
            'cr' => $cr,
            'is_consistent' => $cr < 0.1,
        ];
    }

    public function recommend(Collection $kosts, array $preferences = [], string $scenario = 'default', int $limit = 10): array
    {
        $ahp = $this->calculateAhp($scenario);
        $selectedFacilities = array_values($preferences['facilities'] ?? []);
        $requireAllFacilities = (bool) ($preferences['require_all_facilities'] ?? false);
        $budgetMax = $this->toFloat($preferences['budget_max'] ?? null);
        $maxDistance = $this->toFloat($preferences['max_distance'] ?? null);
        $tipeKos = trim((string) ($preferences['tipe_kos'] ?? ''));

        $rows = $kosts
            ->map(fn (Kost $kost): array => $this->buildDecisionRow($kost, $selectedFacilities))
            ->filter(function (array $row) use ($budgetMax, $maxDistance, $tipeKos, $selectedFacilities, $requireAllFacilities): bool {
                if ($budgetMax > 0 && $row['harga'] > $budgetMax) {
                    return false;
                }

                if ($maxDistance > 0 && ($row['jarak_kampus_asli'] === null || $row['jarak_kampus_asli'] > $maxDistance)) {
                    return false;
                }

                if ($tipeKos !== '' && $row['tipe_kos'] !== $tipeKos) {
                    return false;
                }

                if ($requireAllFacilities && count($selectedFacilities) > 0 && $row['matched_facility_count'] < count($selectedFacilities)) {
                    return false;
                }

                return true;
            })
            ->values();

        if ($rows->isEmpty()) {
            return [
                'ahp' => $ahp,
                'rows' => [],
                'summary' => [
                    'total_alternatives' => $kosts->count(),
                    'filtered_alternatives' => 0,
                    'max_score' => 0.0,
                    'avg_score' => 0.0,
                ],
            ];
        }

        $criteria = $ahp['criteria'];
        $weightMap = $ahp['weight_map'];
        $stats = $this->criteriaStats($rows, $criteria);

        $ranked = $rows
            ->map(function (array $row) use ($criteria, $weightMap, $stats): array {
                $normalized = [];
                $contributions = [];
                $score = 0.0;

                foreach ($criteria as $criterion) {
                    $key = $criterion['key'];
                    $value = (float) $row[$key];

                    if ($criterion['type'] === 'benefit') {
                        $normalValue = $stats[$key]['max'] > 0 ? $value / $stats[$key]['max'] : 0.0;
                    } else {
                        $normalValue = $value > 0 ? $stats[$key]['min'] / $value : 0.0;
                    }

                    $weightedValue = $normalValue * ($weightMap[$key] ?? 0.0);
                    $normalized[$key] = $normalValue;
                    $contributions[$key] = $weightedValue;
                    $score += $weightedValue;
                }

                $row['normalized'] = $normalized;
                $row['contributions'] = $contributions;
                $row['score'] = $score;

                return $row;
            })
            ->sortByDesc('score')
            ->values();

        $maxScore = (float) $ranked->max('score');
        $ranked = $ranked
            ->take($limit)
            ->values()
            ->map(function (array $row, int $index) use ($maxScore): array {
                $row['rank'] = $index + 1;
                $row['score_percent'] = $maxScore > 0 ? ($row['score'] / $maxScore) * 100 : 0.0;

                return $row;
            });

        return [
            'ahp' => $ahp,
            'rows' => $ranked->all(),
            'summary' => [
                'total_alternatives' => $kosts->count(),
                'filtered_alternatives' => $rows->count(),
                'max_score' => $maxScore,
                'avg_score' => (float) $ranked->avg('score'),
            ],
        ];
    }

    private function pairwiseMatrix(string $scenario): array
    {
        return match ($scenario) {
            'hemat' => [
                [1, 3, 5, 5, 9],
                [1 / 3, 1, 3, 2, 7],
                [1 / 5, 1 / 3, 1, 1 / 2, 3],
                [1 / 5, 1 / 2, 2, 1, 4],
                [1 / 9, 1 / 7, 1 / 3, 1 / 4, 1],
            ],
            'fasilitas' => [
                [1, 1 / 2, 2, 1 / 3, 3],
                [2, 1, 3, 1 / 2, 5],
                [1 / 2, 1 / 3, 1, 1 / 4, 2],
                [3, 2, 4, 1, 7],
                [1 / 3, 1 / 5, 1 / 2, 1 / 7, 1],
            ],
            default => [
                [1, 1, 3, 2, 7],
                [1, 1, 3, 2, 7],
                [1 / 3, 1 / 3, 1, 1 / 2, 4],
                [1 / 2, 1 / 2, 2, 1, 5],
                [1 / 7, 1 / 7, 1 / 4, 1 / 5, 1],
            ],
        };
    }

    private function buildDecisionRow(Kost $kost, array $selectedFacilities): array
    {
        $distance = $this->parseCampusDistanceKm($kost->tempat_terdekat ?? '');
        $matchedFacilities = $this->matchedFacilities($kost, $selectedFacilities);
        $allFacilityMatches = $this->matchedFacilities($kost, array_keys($this->facilityOptions()));
        $selectedCount = count($selectedFacilities);

        return [
            'kost' => $kost,
            'id_kost' => $kost->id_kost,
            'nama_kost' => $kost->nama_kost,
            'harga' => (float) $kost->harga,
            'tipe_kos' => (string) $kost->tipe_kos,
            'jarak_kampus' => $distance ?? 999.0,
            'jarak_kampus_asli' => $distance,
            'luas_kamar' => $this->parseRoomSize($kost->sepesifikasi_tipe_kamar ?? ''),
            'kecocokan_fasilitas' => $selectedCount > 0
                ? count($matchedFacilities) / $selectedCount
                : max(count($allFacilityMatches), 0),
            'listrik_termasuk' => $this->includesElectricity($kost->sepesifikasi_tipe_kamar ?? '') ? 1.0 : 0.0,
            'total_facilities' => count($allFacilityMatches),
            'matched_facilities' => $matchedFacilities,
            'matched_facility_count' => count($matchedFacilities),
        ];
    }

    private function criteriaStats(Collection $rows, array $criteria): array
    {
        $stats = [];

        foreach ($criteria as $criterion) {
            $key = $criterion['key'];
            $values = $rows
                ->pluck($key)
                ->map(fn ($value): float => (float) $value)
                ->filter(fn (float $value): bool => $value > 0);

            $stats[$key] = [
                'min' => $values->isNotEmpty() ? (float) $values->min() : 0.0,
                'max' => $values->isNotEmpty() ? (float) $values->max() : 0.0,
            ];
        }

        return $stats;
    }

    private function matchedFacilities(Kost $kost, array $keys): array
    {
        $options = $this->facilityOptions();
        $text = $this->searchableText($kost);
        $matches = [];

        foreach ($keys as $key) {
            if (isset($options[$key]) && $this->textContains($text, $options[$key]['keywords'])) {
                $matches[$key] = $options[$key]['label'];
            }
        }

        return $matches;
    }

    private function searchableText(Kost $kost): string
    {
        return strtolower(implode(' ', [
            $kost->sepesifikasi_tipe_kamar,
            $kost->fasilitas_kamar,
            $kost->fasilitas_kamar_mandi,
            $kost->fasilitas_umum,
            $kost->fasilitas_parkir,
            $kost->tempat_terdekat,
            $kost->peraturan_kos,
        ]));
    }

    private function textContains(string $text, array $keywords): bool
    {
        foreach ($keywords as $keyword) {
            if (str_contains($text, strtolower($keyword))) {
                return true;
            }
        }

        return false;
    }

    private function parseRoomSize(string $value): float
    {
        if (preg_match('/(\d+(?:[.,]\d+)?)\s*x\s*(\d+(?:[.,]\d+)?)/i', $value, $matches)) {
            return (float) str_replace(',', '.', $matches[1]) * (float) str_replace(',', '.', $matches[2]);
        }

        return 0.0;
    }

    private function parseCampusDistanceKm(string $value): ?float
    {
        $segments = array_map('trim', explode('|', $value));
        $distances = [];

        foreach ($segments as $segment) {
            $lowerSegment = strtolower($segment);
            $isCampus = str_contains($lowerSegment, 'universitas')
                || str_contains($lowerSegment, 'politeknik')
                || str_contains($lowerSegment, 'kampus')
                || str_contains($lowerSegment, 'institut')
                || str_contains($lowerSegment, 'sekolah tinggi')
                || str_contains($lowerSegment, 'akademi');

            if (! $isCampus) {
                continue;
            }

            if (preg_match('/\(([\d.,]+)\s*(km|m)\)/i', $segment, $matches)) {
                $distance = (float) str_replace(',', '.', $matches[1]);
                $distances[] = strtolower($matches[2]) === 'm' ? $distance / 1000 : $distance;
            }
        }

        return count($distances) > 0 ? min($distances) : null;
    }

    private function includesElectricity(string $value): bool
    {
        $lowerValue = strtolower($value);

        return str_contains($lowerValue, 'termasuk listrik')
            && ! str_contains($lowerValue, 'tidak termasuk listrik');
    }

    private function toFloat(mixed $value): float
    {
        $clean = preg_replace('/[^0-9,.]/', '', (string) $value);
        if ($clean === '') {
            return 0.0;
        }

        if (str_contains($clean, ',') && str_contains($clean, '.')) {
            $clean = str_replace('.', '', $clean);
            $clean = str_replace(',', '.', $clean);
        } elseif (substr_count($clean, '.') > 1 || preg_match('/\.\d{3}$/', $clean)) {
            $clean = str_replace('.', '', $clean);
        } elseif (str_contains($clean, ',') && ! str_contains($clean, '.')) {
            $clean = str_replace(',', '.', $clean);
        } else {
            $clean = str_replace(',', '', $clean);
        }

        return (float) $clean;
    }
}
