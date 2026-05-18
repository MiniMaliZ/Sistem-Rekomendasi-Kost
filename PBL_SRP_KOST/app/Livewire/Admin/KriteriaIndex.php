<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Kriteria;
use App\Models\User;

class KriteriaIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public bool $showModal = false;
    public bool $showDeleteModal = false;
    public ?int $editingId = null;
    public ?int $deletingId = null;

    // Form fields
    public string $nama_kriteria = '';
    public string $id_user = '';

    protected function rules(): array
    {
        return [
            'nama_kriteria' => 'required|string|max:100',
            'id_user'       => 'required|exists:user,id_user',
        ];
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function openCreate(): void
    {
        $this->reset(['nama_kriteria', 'id_user']);
        $this->editingId = null;
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $kriteria = Kriteria::findOrFail($id);
        $this->editingId = $id;
        $this->nama_kriteria = $kriteria->nama_kriteria;
        $this->id_user = (string) $kriteria->id_user;
        $this->showModal = true;
    }

    public function save(): void
    {
        $data = $this->validate();

        if ($this->editingId) {
            Kriteria::findOrFail($this->editingId)->update($data);
            session()->flash('success', 'Kriteria berhasil diperbarui.');
        } else {
            Kriteria::create($data);
            session()->flash('success', 'Kriteria berhasil ditambahkan.');
        }

        $this->showModal = false;
        $this->resetPage();
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        if ($this->deletingId) {
            Kriteria::findOrFail($this->deletingId)->delete();
            session()->flash('success', 'Kriteria berhasil dihapus.');
        }
        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    public function render()
    {
        $kriterias = Kriteria::with('user')
            ->when($this->search, fn($q) => $q->where('nama_kriteria', 'like', "%{$this->search}%"))
            ->orderBy('id_kriteria', 'desc')
            ->paginate(10);

        $users = User::orderBy('nama')->get(['id_user', 'nama']);

        return view('livewire.admin.kriteria-index', compact('kriterias', 'users'))
            ->layout('components.layouts.admin', [
                'title' => 'Kriteria SPK',
                'breadcrumb' => ['Kriteria SPK' => route('admin.kriteria')],
            ]);
    }
}
