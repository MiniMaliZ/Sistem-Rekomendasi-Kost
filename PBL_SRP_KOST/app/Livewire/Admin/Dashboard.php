<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Kost;
use App\Models\User;
use App\Models\HasilRekomendasi;
use App\Models\Feedback;

class Dashboard extends Component
{
    public int $totalKost = 0;
    public int $totalUser = 0;
    public int $totalRekomendasi = 0;
    public float $avgRating = 0;

    public array $kostByTipe = [];
    public array $kostByKota = [];
    public array $kostByHarga = [];
    public array $recentFeedback = [];
    public array $recentRekomendasi = [];

    public function mount(): void
    {
        $this->totalKost = Kost::count();
        $this->totalUser = User::where('role', 'user')->count();
        $this->totalRekomendasi = HasilRekomendasi::count();
        $this->avgRating = round(Feedback::avg('rating') ?? 0, 1);

        // Kost by tipe for chart
        $this->kostByTipe = [
            'Putra' => Kost::where('tipe_kos', 'like', '%Putra%')->count(),
            'Putri' => Kost::where('tipe_kos', 'like', '%Putri%')->count(),
            'Campur' => Kost::where('tipe_kos', 'like', '%Campur%')->count(),
        ];

        // Kost by Malang area, inferred from current Mamikos-style fields.
        $kosts = Kost::all();
        $areaCounts = [
            'Lowokwaru' => 0,
            'Sukun' => 0,
            'Blimbing' => 0,
            'Klojen' => 0,
            'Kedungkandang' => 0,
            'Malang' => 0,
        ];

        foreach ($kosts as $k) {
            $areaCounts[$this->inferAreaFromKost($k)]++;
        }
        $this->kostByKota = $areaCounts;

        // Kost by harga
        $hargaCounts = [
            '< 500 rb' => Kost::where('harga', '<', 500000)->count(),
            '500rb - 1jt' => Kost::whereBetween('harga', [500000, 1000000])->count(),
            '1jt - 1,5jt' => Kost::whereBetween('harga', [1000001, 1500000])->count(),
            '1,5jt - 2jt' => Kost::whereBetween('harga', [1500001, 2000000])->count(),
            '2jt - 2,5jt' => Kost::whereBetween('harga', [2000001, 2500000])->count(),
            '2,5jt - 3jt' => Kost::whereBetween('harga', [2500001, 3000000])->count(),
            '> 3jt' => Kost::where('harga', '>', 3000000)->count(),
        ];
        $this->kostByHarga = $hargaCounts;

        // Recent feedback
        $this->recentFeedback = Feedback::with(['user', 'kost'])
            ->latest('created_at')
            ->take(5)
            ->get()
            ->toArray();

        // Recent rekomendasi
        $this->recentRekomendasi = HasilRekomendasi::with(['user', 'kost'])
            ->latest('created_at')
            ->take(5)
            ->get()
            ->toArray();
    }

    public function render()
    {
        return view('livewire.admin.dashboard')
            ->layout('components.layouts.admin', [
                'title' => 'Dashboard',
                'breadcrumb' => ['Dashboard' => route('admin.dashboard')],
            ]);
    }

    private function inferAreaFromKost(Kost $kost): string
    {
        $text = strtolower(implode(' ', [
            $kost->nama_kost,
            $kost->tempat_terdekat,
        ]));

        foreach (['lowokwaru', 'sukun', 'blimbing', 'klojen', 'kedungkandang'] as $area) {
            if (str_contains($text, $area)) {
                return ucfirst($area);
            }
        }

        return 'Malang';
    }
}
