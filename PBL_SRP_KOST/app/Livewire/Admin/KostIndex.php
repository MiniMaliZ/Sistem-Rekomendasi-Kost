<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Kost;

class KostIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterTipe = '';
    public bool $showModal = false;
    public bool $showDeleteModal = false;
    public ?int $editingId = null;
    public ?int $deletingId = null;

    // Form fields
    public string $nama_kost = '';
    public string $owner = '';
    public string $alamat = '';
    public string $harga = '';
    public string $ukuran_kamar = '';
    public string $tipe_kos = 'putra';
    public string $fasilitas = '';
    public string $foto_url = '';

    protected function rules(): array
    {
        return [
            'nama_kost'    => 'required|string|max:150',
            'owner'        => 'required|string|max:100',
            'alamat'       => 'required|string|max:255',
            'harga'        => 'required|numeric|min:0',
            'ukuran_kamar' => 'nullable|string|max:50',
            'tipe_kos'     => 'required|in:putra,putri,campur',
            'fasilitas'    => 'nullable|string',
            'foto_url'     => 'nullable|url|max:255',
        ];
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedFilterTipe(): void
    {
        $this->resetPage();
    }

    public function openCreate(): void
    {
        $this->reset(['nama_kost', 'owner', 'alamat', 'harga', 'ukuran_kamar', 'fasilitas', 'foto_url']);
        $this->tipe_kos = 'putra';
        $this->editingId = null;
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $kost = Kost::findOrFail($id);
        $this->editingId = $id;
        $this->nama_kost = $kost->nama_kost;
        $this->owner = $kost->owner;
        $this->alamat = $kost->alamat;
        $this->harga = (string) $kost->harga;
        $this->ukuran_kamar = $kost->ukuran_kamar ?? '';
        $this->tipe_kos = $kost->tipe_kos;
        $this->fasilitas = $kost->fasilitas ?? '';
        $this->foto_url = $kost->foto_url ?? '';
        $this->showModal = true;
    }

    public function save(): void
    {
        $data = $this->validate();

        if ($this->editingId) {
            Kost::findOrFail($this->editingId)->update($data);
            session()->flash('success', 'Data kost berhasil diperbarui.');
        } else {
            Kost::create($data);
            session()->flash('success', 'Data kost berhasil ditambahkan.');
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
            Kost::findOrFail($this->deletingId)->delete();
            session()->flash('success', 'Data kost berhasil dihapus.');
        }
        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    public function render()
    {
        $kosts = Kost::query()
            ->when($this->search, fn($q) => $q->where('nama_kost', 'like', "%{$this->search}%")
                ->orWhere('owner', 'like', "%{$this->search}%")
                ->orWhere('alamat', 'like', "%{$this->search}%"))
            ->when($this->filterTipe, fn($q) => $q->where('tipe_kos', $this->filterTipe))
            ->orderBy('id_kost', 'desc')
            ->paginate(10);

        return view('livewire.admin.kost-index', compact('kosts'))
            ->layout('components.layouts.admin', [
                'title' => 'Manajemen Kost',
                'breadcrumb' => ['Data Kost' => route('admin.kost')],
            ]);
    }
}
