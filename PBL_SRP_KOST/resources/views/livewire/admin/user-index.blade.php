<div style="display:flex; flex-direction:column; gap:1.5rem;">

    <div style="display:flex; align-items:flex-start; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
        <div>
            <h1 style="margin:0; font-size:1.5rem; font-weight:800; color:#5D4D34;">Manajemen Pengguna</h1>
            <p style="margin:0.25rem 0 0; font-size:0.875rem; color:#7C929E;">Kelola akun pengguna dan role</p>
        </div>
        <button wire:click="openCreate" id="btn-tambah-user" class="btn-primary-custom">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="flex-shrink:0;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Pengguna
        </button>
    </div>

    @if(session('success'))
        <div style="display:flex; align-items:center; gap:0.5rem; padding:0.75rem 1rem; border-radius:0.625rem; background-color:rgba(93,154,124,0.15); border:1px solid rgba(93,154,124,0.3); color:#3d7a5d; font-size:0.875rem; font-weight:500;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <div style="display:flex; gap:0.75rem; flex-wrap:wrap;">
        <div style="flex:1; min-width:240px; position:relative;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#7C929E" stroke-width="2"
                 style="position:absolute; left:0.75rem; top:50%; transform:translateY(-50%); pointer-events:none;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input wire:model.live.debounce.300ms="search" id="search-user" type="text"
                   placeholder="Cari nama atau email..."
                   style="width:100%; box-sizing:border-box; padding:0.5rem 0.875rem 0.5rem 2.5rem; background-color:#CDC6BA; border:1px solid #AD9C8A; border-radius:0.625rem; font-size:0.875rem; font-family:'Poppins',sans-serif; color:#5D4D34; outline:none;">
        </div>
        <select wire:model.live="filterRole" id="filter-role"
                style="padding:0.5rem 0.875rem; background-color:#CDC6BA; border:1px solid #AD9C8A; border-radius:0.625rem; font-size:0.875rem; font-family:'Poppins',sans-serif; color:#5D4D34; outline:none; cursor:pointer;">
            <option value="">Semua Role</option>
            <option value="admin">Admin</option>
            <option value="user">User</option>
        </select>
    </div>

    <div class="admin-table" style="overflow-x:auto;">
        <table style="width:100%; min-width:600px; border-collapse:collapse;">
            <thead>
                <tr style="background-color:#5D4D34;">
                    <th style="padding:0.75rem 1rem; text-align:left; font-size:0.7rem; font-weight:700; text-transform:uppercase; color:#DBD3C6;">#</th>
                    <th style="padding:0.75rem 1rem; text-align:left; font-size:0.7rem; font-weight:700; text-transform:uppercase; color:#DBD3C6;">Pengguna</th>
                    <th style="padding:0.75rem 1rem; text-align:left; font-size:0.7rem; font-weight:700; text-transform:uppercase; color:#DBD3C6;">Email</th>
                    <th style="padding:0.75rem 1rem; text-align:left; font-size:0.7rem; font-weight:700; text-transform:uppercase; color:#DBD3C6;">Role</th>
                    <th style="padding:0.75rem 1rem; text-align:left; font-size:0.7rem; font-weight:700; text-transform:uppercase; color:#DBD3C6;">Bergabung</th>
                    <th style="padding:0.75rem 1rem; text-align:center; font-size:0.7rem; font-weight:700; text-transform:uppercase; color:#DBD3C6;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr style="border-bottom:1px solid rgba(173,156,138,0.2);">
                        <td style="padding:0.75rem 1rem; font-size:0.875rem; color:#7C929E;">{{ $user->id_user }}</td>
                        <td style="padding:0.75rem 1rem;">
                            <div style="display:flex; align-items:center; gap:0.75rem;">
                                @if($user->foto_url)
                                    <img src="{{ $user->foto_url }}"
                                         style="width:36px; height:36px; border-radius:50%; object-fit:cover; flex-shrink:0;"
                                         onerror="this.style.display='none'">
                                @else
                                    <div style="width:36px; height:36px; border-radius:50%; background-color:{{ $user->role === 'admin' ? '#5D4D34' : '#7C929E' }}; display:flex; align-items:center; justify-content:center; color:white; font-weight:700; font-size:0.875rem; flex-shrink:0;">
                                        {{ strtoupper(substr($user->nama, 0, 1)) }}
                                    </div>
                                @endif
                                <span style="font-size:0.875rem; font-weight:600; color:#5D4D34;">{{ $user->nama }}</span>
                            </div>
                        </td>
                        <td style="padding:0.75rem 1rem; font-size:0.875rem; color:#7C929E;">{{ $user->email }}</td>
                        <td style="padding:0.75rem 1rem;">
                            @php $adminColor = $user->role === 'admin' ? '#5D4D34' : '#7C929E'; @endphp
                            <span style="display:inline-block; padding:0.2rem 0.65rem; border-radius:9999px; font-size:0.7rem; font-weight:700; text-transform:uppercase; background-color:{{ $adminColor }}20; color:{{ $adminColor }};">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td style="padding:0.75rem 1rem; font-size:0.875rem; color:#7C929E;">
                            {{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}
                        </td>
                        <td style="padding:0.75rem 1rem;">
                            <div style="display:flex; align-items:center; justify-content:center; gap:0.5rem;">
                                <button wire:click="openEdit({{ $user->id_user }})"
                                        style="display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; border:none; background:transparent; cursor:pointer;"
                                        title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#7C929E" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/>
                                    </svg>
                                </button>
                                <button wire:click="confirmDelete({{ $user->id_user }})"
                                        style="display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; border:none; background:transparent; cursor:pointer;"
                                        title="Hapus">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#C45A5A" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding:3rem 1rem; text-align:center; font-size:0.875rem; color:#7C929E;">Belum ada data pengguna.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>{{ $users->links() }}</div>

    {{-- Create/Edit Modal --}}
    @if($showModal)
        <div style="position:fixed; inset:0; z-index:100; display:flex; align-items:center; justify-content:center; padding:1rem; background-color:rgba(0,0,0,0.5);">
            <div style="width:100%; max-width:480px; background-color:#DBD3C6; border:1px solid #AD9C8A; border-radius:1rem; box-shadow:0 25px 50px rgba(93,77,52,0.25);">
                <div style="display:flex; align-items:center; justify-content:space-between; padding:1.25rem 1.5rem; border-bottom:1px solid rgba(173,156,138,0.3);">
                    <h3 style="margin:0; font-size:1.1rem; font-weight:700; color:#5D4D34;">{{ $editingId ? 'Edit Pengguna' : 'Tambah Pengguna' }}</h3>
                    <button wire:click="$set('showModal', false)" style="display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; border:none; background:transparent; cursor:pointer;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#7C929E" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <form wire:submit="save" style="padding:1.25rem 1.5rem; display:flex; flex-direction:column; gap:1rem;">
                    <div>
                        <label style="display:block; font-size:0.8rem; font-weight:600; color:#5D4D34; margin-bottom:0.375rem;">Nama Lengkap *</label>
                        <input wire:model="nama" id="input-nama-user" type="text" placeholder="Nama lengkap"
                               style="width:100%; box-sizing:border-box; padding:0.5rem 0.875rem; background-color:#CDC6BA; border:1px solid #AD9C8A; border-radius:0.625rem; font-size:0.875rem; font-family:'Poppins',sans-serif; color:#5D4D34; outline:none;">
                        @error('nama') <p style="font-size:0.75rem; color:#C45A5A; margin:0.25rem 0 0;">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label style="display:block; font-size:0.8rem; font-weight:600; color:#5D4D34; margin-bottom:0.375rem;">Email *</label>
                        <input wire:model="email" id="input-email-user" type="email" placeholder="email@example.com"
                               style="width:100%; box-sizing:border-box; padding:0.5rem 0.875rem; background-color:#CDC6BA; border:1px solid #AD9C8A; border-radius:0.625rem; font-size:0.875rem; font-family:'Poppins',sans-serif; color:#5D4D34; outline:none;">
                        @error('email') <p style="font-size:0.75rem; color:#C45A5A; margin:0.25rem 0 0;">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label style="display:block; font-size:0.8rem; font-weight:600; color:#5D4D34; margin-bottom:0.375rem;">
                            Password {{ $editingId ? '(kosongkan jika tidak diubah)' : '*' }}
                        </label>
                        <input wire:model="password" id="input-password-user" type="password" placeholder="Minimal 6 karakter"
                               style="width:100%; box-sizing:border-box; padding:0.5rem 0.875rem; background-color:#CDC6BA; border:1px solid #AD9C8A; border-radius:0.625rem; font-size:0.875rem; font-family:'Poppins',sans-serif; color:#5D4D34; outline:none;">
                        @error('password') <p style="font-size:0.75rem; color:#C45A5A; margin:0.25rem 0 0;">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label style="display:block; font-size:0.8rem; font-weight:600; color:#5D4D34; margin-bottom:0.375rem;">Role *</label>
                        <select wire:model="role" id="input-role-user"
                                style="width:100%; box-sizing:border-box; padding:0.5rem 0.875rem; background-color:#CDC6BA; border:1px solid #AD9C8A; border-radius:0.625rem; font-size:0.875rem; font-family:'Poppins',sans-serif; color:#5D4D34; outline:none;">
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div>
                        <label style="display:block; font-size:0.8rem; font-weight:600; color:#5D4D34; margin-bottom:0.375rem;">URL Foto</label>
                        <input wire:model="foto_url" id="input-foto-user" type="url" placeholder="https://..."
                               style="width:100%; box-sizing:border-box; padding:0.5rem 0.875rem; background-color:#CDC6BA; border:1px solid #AD9C8A; border-radius:0.625rem; font-size:0.875rem; font-family:'Poppins',sans-serif; color:#5D4D34; outline:none;">
                    </div>
                    <div style="display:flex; justify-content:flex-end; gap:0.75rem; padding-top:0.75rem; border-top:1px solid rgba(173,156,138,0.3);">
                        <button type="button" wire:click="$set('showModal', false)"
                                style="padding:0.5rem 1.25rem; border-radius:0.625rem; font-size:0.875rem; font-weight:600; font-family:'Poppins',sans-serif; border:1px solid #AD9C8A; background:transparent; color:#5D4D34; cursor:pointer;">Batal</button>
                        <button type="submit" id="btn-save-user" class="btn-primary-custom">{{ $editingId ? 'Simpan' : 'Tambah' }}</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if($showDeleteModal)
        <div style="position:fixed; inset:0; z-index:100; display:flex; align-items:center; justify-content:center; padding:1rem; background-color:rgba(0,0,0,0.5);">
            <div style="width:100%; max-width:380px; background-color:#DBD3C6; border:1px solid #AD9C8A; border-radius:1rem; padding:2rem; text-align:center;">
                <div style="width:56px; height:56px; border-radius:50%; background-color:rgba(196,90,90,0.15); display:flex; align-items:center; justify-content:center; margin:0 auto 1rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="#C45A5A" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                    </svg>
                </div>
                <h3 style="margin:0 0 0.5rem; font-size:1.1rem; font-weight:700; color:#5D4D34;">Hapus Pengguna?</h3>
                <p style="margin:0 0 1.5rem; font-size:0.875rem; color:#7C929E;">Akun ini akan dihapus beserta semua data terkait.</p>
                <div style="display:flex; gap:0.75rem; justify-content:center;">
                    <button wire:click="$set('showDeleteModal', false)"
                            style="padding:0.5rem 1.25rem; border-radius:0.625rem; font-size:0.875rem; font-weight:600; font-family:'Poppins',sans-serif; border:1px solid #AD9C8A; background:transparent; color:#5D4D34; cursor:pointer;">Batal</button>
                    <button wire:click="delete" id="btn-confirm-delete-user" class="btn-danger-custom">Ya, Hapus</button>
                </div>
            </div>
        </div>
    @endif

</div>
