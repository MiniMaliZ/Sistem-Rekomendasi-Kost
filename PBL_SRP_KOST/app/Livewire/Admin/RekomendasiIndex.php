<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\HasilRekomendasi;

class RekomendasiIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatedSearch(): void { $this->resetPage(); }

    public function render()
    {
        $rekomendasiList = HasilRekomendasi::with(['user', 'kost'])
            ->when($this->search, fn($q) => $q->whereHas('user', fn($u) => $u->where('nama', 'like', "%{$this->search}%"))
                ->orWhereHas('kost', fn($k) => $k->where('nama_kost', 'like', "%{$this->search}%")))
            ->orderByDesc('created_at')
            ->orderBy('rangking')
            ->paginate(15);

        return view('livewire.admin.rekomendasi-index', compact('rekomendasiList'))
            ->layout('components.layouts.admin', [
                'title' => 'Hasil Rekomendasi',
                'breadcrumb' => ['Hasil Rekomendasi' => route('admin.rekomendasi')],
            ]);
    }
}
