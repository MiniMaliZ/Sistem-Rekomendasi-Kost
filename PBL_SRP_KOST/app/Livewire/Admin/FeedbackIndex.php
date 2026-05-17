<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Feedback;

class FeedbackIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterRating = '';

    public function updatedSearch(): void { $this->resetPage(); }
    public function updatedFilterRating(): void { $this->resetPage(); }

    public function render()
    {
        $feedbacks = Feedback::with(['user', 'kost'])
            ->when($this->search, fn($q) => $q->whereHas('user', fn($u) => $u->where('nama', 'like', "%{$this->search}%"))
                ->orWhereHas('kost', fn($k) => $k->where('nama_kost', 'like', "%{$this->search}%")))
            ->when($this->filterRating, fn($q) => $q->where('rating', $this->filterRating))
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('livewire.admin.feedback-index', compact('feedbacks'))
            ->layout('components.layouts.admin', [
                'title' => 'Feedback & Ulasan',
                'breadcrumb' => ['Feedback & Ulasan' => route('admin.feedback')],
            ]);
    }
}
