<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Riwayat;

class RiwayatIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterAksi = '';

    public function updatedSearch(): void { $this->resetPage(); }
    public function updatedFilterAksi(): void { $this->resetPage(); }

    public function render()
    {
        $riwayats = Riwayat::with(['user', 'kost'])
            ->when($this->search, fn($q) => $q->whereHas('user', fn($u) => $u->where('nama', 'like', "%{$this->search}%"))
                ->orWhereHas('kost', fn($k) => $k->where('nama_kost', 'like', "%{$this->search}%")))
            ->when($this->filterAksi, fn($q) => $q->where('aksi', $this->filterAksi))
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('livewire.admin.riwayat-index', compact('riwayats'))
            ->layout('components.layouts.admin', [
                'title' => 'Riwayat Aktivitas',
                'breadcrumb' => ['Riwayat Aktivitas' => route('admin.riwayat')],
            ]);
    }
}
