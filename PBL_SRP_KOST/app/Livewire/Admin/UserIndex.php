<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterRole = '';
    public bool $showModal = false;
    public bool $showDeleteModal = false;
    public ?int $editingId = null;
    public ?int $deletingId = null;

    // Form fields
    public string $nama = '';
    public string $email = '';
    public string $password = '';
    public string $foto_url = '';
    public string $role = 'user';

    protected function rules(): array
    {
        $passwordRule = $this->editingId ? 'nullable|string|min:6' : 'required|string|min:6';
        return [
            'nama'     => 'required|string|max:100',
            'email'    => 'required|email|max:150|unique:user,email,' . ($this->editingId ?? 'NULL') . ',id_user',
            'password' => $passwordRule,
            'foto_url' => 'nullable|url|max:255',
            'role'     => 'required|in:admin,user',
        ];
    }

    public function updatedSearch(): void { $this->resetPage(); }
    public function updatedFilterRole(): void { $this->resetPage(); }

    public function openCreate(): void
    {
        $this->reset(['nama', 'email', 'password', 'foto_url']);
        $this->role = 'user';
        $this->editingId = null;
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $user = User::findOrFail($id);
        $this->editingId = $id;
        $this->nama = $user->nama;
        $this->email = $user->email;
        $this->password = '';
        $this->foto_url = $user->foto_url ?? '';
        $this->role = $user->role;
        $this->showModal = true;
    }

    public function save(): void
    {
        $data = $this->validate();

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        if ($this->editingId) {
            User::findOrFail($this->editingId)->update($data);
            session()->flash('success', 'Data pengguna berhasil diperbarui.');
        } else {
            User::create($data);
            session()->flash('success', 'Pengguna baru berhasil ditambahkan.');
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
            User::findOrFail($this->deletingId)->delete();
            session()->flash('success', 'Pengguna berhasil dihapus.');
        }
        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    public function render()
    {
        $users = User::query()
            ->when($this->search, fn($q) => $q->where('nama', 'like', "%{$this->search}%")
                ->orWhere('email', 'like', "%{$this->search}%"))
            ->when($this->filterRole, fn($q) => $q->where('role', $this->filterRole))
            ->orderBy('id_user', 'desc')
            ->paginate(10);

        return view('livewire.admin.user-index', compact('users'))
            ->layout('components.layouts.admin', [
                'title' => 'Pengguna',
                'breadcrumb' => ['Pengguna' => route('admin.users')],
            ]);
    }
}
