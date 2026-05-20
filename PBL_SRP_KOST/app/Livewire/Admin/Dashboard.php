<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Kost;
use App\Models\User;
use App\Models\Kriteria;
use App\Models\HasilRekomendasi;
use App\Models\Feedback;
use Illuminate\Support\Facades\DB;

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
            'Putra' => Kost::where('tipe_kos', 'putra')->count(),
            'Putri' => Kost::where('tipe_kos', 'putri')->count(),
            'Campur' => Kost::where('tipe_kos', 'campur')->count(),
        ];

        // Kost by kota (simplistic extraction based on last word after comma)
        $kosts = Kost::all();
        $kotaCounts = [
            'SBY' => 0, 'MLG' => 0, 'JAKSEL' => 0, 'BDG' => 0, 'YGY' => 0, 'BGR' => 0, 'BALI' => 0
        ];
        foreach ($kosts as $k) {
            $alamat = strtolower($k->alamat);
            if (str_contains($alamat, 'bandung') || str_contains($alamat, 'bdg')) $kotaCounts['BDG']++;
            elseif (str_contains($alamat, 'surabaya') || str_contains($alamat, 'sby')) $kotaCounts['SBY']++;
            elseif (str_contains($alamat, 'malang') || str_contains($alamat, 'mlg')) $kotaCounts['MLG']++;
            elseif (str_contains($alamat, 'jakarta') || str_contains($alamat, 'jaksel')) $kotaCounts['JAKSEL']++;
            elseif (str_contains($alamat, 'yogya') || str_contains($alamat, 'ygy')) $kotaCounts['YGY']++;
            elseif (str_contains($alamat, 'bogor') || str_contains($alamat, 'bgr')) $kotaCounts['BGR']++;
            elseif (str_contains($alamat, 'bali')) $kotaCounts['BALI']++;
            else {
                // Try to infer from last comma
                $parts = explode(',', $k->alamat);
                $city = strtoupper(trim(end($parts)));
                if(strlen($city) > 0 && strlen($city) < 15) {
                    if(!isset($kotaCounts[$city])) $kotaCounts[$city] = 0;
                    $kotaCounts[$city]++;
                }
            }
        }
        $this->kostByKota = $kotaCounts;

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
}
