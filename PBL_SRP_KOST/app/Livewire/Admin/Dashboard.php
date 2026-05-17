<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Kost;
use App\Models\User;
use App\Models\Kriteria;
use App\Models\HasilRekomendasi;
use App\Models\Feedback;

class Dashboard extends Component
{
    public int $totalKost = 0;
    public int $totalUser = 0;
    public int $totalKriteria = 0;
    public int $totalRekomendasi = 0;
    public float $avgRating = 0;

    public array $kostByTipe = [];
    public array $recentFeedback = [];
    public array $recentRekomendasi = [];

    public function mount(): void
    {
        $this->totalKost = Kost::count();
        $this->totalUser = User::where('role', 'user')->count();
        $this->totalKriteria = Kriteria::count();
        $this->totalRekomendasi = HasilRekomendasi::count();
        $this->avgRating = round(Feedback::avg('rating') ?? 0, 1);

        // Kost by tipe for chart
        $this->kostByTipe = [
            'Putra' => Kost::where('tipe_kos', 'putra')->count(),
            'Putri' => Kost::where('tipe_kos', 'putri')->count(),
            'Campur' => Kost::where('tipe_kos', 'campur')->count(),
        ];

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
}
