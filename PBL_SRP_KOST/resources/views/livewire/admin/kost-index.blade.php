<div class="animate-fade-in" style="display:flex; flex-direction:column; gap:2rem;">

    {{-- Action & Filter Bar --}}
    <div style="display:flex; gap:0.75rem; flex-wrap:wrap; align-items:center; background: #ffffff; padding: 0.75rem 1rem; border-radius: 0.75rem; border: 1px solid rgba(173,156,138,0.2); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
        
        {{-- Search Input Group --}}
        <div style="flex:1; min-width:240px; position:relative;">
            <div style="position:absolute; left:0.875rem; top:50%; transform:translateY(-50%); color: #AD9C8A; display: flex; align-items: center;">
                <x-solar-magnifer-linear class="w-4 h-4" />
            </div>
            <input wire:model.live.debounce.300ms="search" id="search-kost" type="text"
                   placeholder="Cari berdasarkan nama, pemilik, atau lokasi..."
                   style="width:100%; box-sizing:border-box; padding:0.5rem 1rem 0.5rem 2.25rem; background-color:#fcfaf8; border:1px solid #f0ebe1; border-radius:0.5rem; font-size:0.85rem; font-family:'Poppins',sans-serif; color:#3f2419; outline:none; transition: all 0.2s ease;"
                   onfocus="this.style.borderColor='#3f2419'; this.style.backgroundColor='#ffffff'; this.style.boxShadow='0 0 0 3px rgba(63,36,25,0.05)'"
                   onblur="this.style.borderColor='#f0ebe1'; this.style.backgroundColor='#fcfaf8'; this.style.boxShadow='none'">
        </div>

        {{-- Filter Dropdown Group --}}
        <div style="position:relative; min-width:160px;">
            <div style="position:absolute; left:0.875rem; top:50%; transform:translateY(-50%); color: #AD9C8A; display: flex; align-items: center; pointer-events: none;">
                <x-solar-filter-linear class="w-4 h-4" />
            </div>
            <select wire:model.live="filterTipe" id="filter-tipe-kost"
                    style="width:100%; padding:0.5rem 1.75rem 0.5rem 2.25rem; background-color:#fcfaf8; border:1px solid #f0ebe1; border-radius:0.5rem; font-size:0.85rem; font-family:'Poppins',sans-serif; color:#3f2419; outline:none; cursor:pointer; appearance:none; transition: all 0.2s ease;"
                    onfocus="this.style.borderColor='#3f2419'; this.style.backgroundColor='#ffffff'"
                    onblur="this.style.borderColor='#f0ebe1'; this.style.backgroundColor='#fcfaf8'">
                <option value="">Semua Tipe Kos</option>
                <option value="Kos Putra">Kos Putra</option>
                <option value="Kos Putri">Kos Putri</option>
                <option value="Kos Campur">Kos Campur</option>
            </select>
            <div style="position:absolute; right:0.875rem; top:50%; transform:translateY(-50%); color: #AD9C8A; pointer-events: none; display: flex; align-items: center;">
                <x-solar-alt-arrow-down-linear class="w-3 h-3" />
            </div>
        </div>

        {{-- Add Primary Button --}}
        <button wire:click="openImportModal" class="btn-secondary" style="padding: 0.6rem 1rem; border-radius: 0.5rem; background: #fdfcfb; border: 1px solid #f0ebe1; color: #5D4D34; font-weight: 600; font-size: 0.85rem; display: flex; align-items: center; gap: 0.5rem; cursor: pointer; transition: 0.2s;">
            <x-solar-upload-square-linear class="w-4 h-4" />
            <span>Import CSV</span>
        </button>
        <button wire:click="openCreate" id="btn-tambah-kost" class="btn-primary-gradient">
            <x-solar-add-circle-linear class="w-4 h-4" />
            <span>Tambah Kost</span>
        </button>
    </div>

    {{-- Flash Message --}}
    @if(session('success'))
        <div class="animate-fade-in" style="display:flex; align-items:center; gap:0.75rem; padding:1rem 1.25rem; border-radius:1rem; background-color:#ecfdf5; border:1px solid #10b98133; color:#065f46; font-size:0.9rem; font-weight:500;">
            <x-solar-check-circle-linear class="w-6 h-6 text-emerald-500" />
            {{ session('success') }}
        </div>
    @endif

    {{-- Table Container --}}
    <div style="background: #ffffff; border-radius: 1rem; border: 1px solid rgba(173,156,138,0.2); box-shadow: 0 4px 6px -1px rgba(93,77,52,0.03); overflow: hidden;">
        <div style="overflow-x:auto;">
            <table style="width:100%; min-width:900px; border-collapse:collapse; table-layout: fixed;">
                <thead>
                    @php
                        $sortableHeaders = [
                            ['field' => 'id_kost', 'label' => 'ID', 'width' => '5%', 'align' => 'left', 'justify' => 'flex-start'],
                            ['field' => 'nama_kost', 'label' => 'Informasi Kost', 'width' => '35%', 'align' => 'left', 'justify' => 'flex-start'],
                            ['field' => 'harga', 'label' => 'Harga Sewa', 'width' => '20%', 'align' => 'left', 'justify' => 'flex-start'],
                            ['field' => 'sepesifikasi_tipe_kamar', 'label' => 'Spesifikasi', 'width' => '15%', 'align' => 'left', 'justify' => 'flex-start'],
                            ['field' => 'fasilitas_kamar', 'label' => 'Fasilitas', 'width' => '15%', 'align' => 'center', 'justify' => 'center'],
                        ];
                    @endphp
                    <tr style="background-color:#fcfaf8; border-bottom: 2px solid #f0ebe1;">
                        @foreach($sortableHeaders as $header)
                            <th aria-sort="{{ $sortField === $header['field'] ? ($sortDirection === 'asc' ? 'ascending' : 'descending') : 'none' }}" style="width: {{ $header['width'] }}; padding:0.875rem 1.25rem; text-align:{{ $header['align'] }}; font-size:0.75rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; color:#AD9C8A;">
                                <button type="button" wire:click="sortBy('{{ $header['field'] }}')" class="sortable-table-header {{ $sortField === $header['field'] ? 'is-active' : '' }}" style="justify-content:{{ $header['justify'] }};" title="Urutkan {{ $header['label'] }}" aria-label="Urutkan {{ $header['label'] }}">
                                    <span>{{ $header['label'] }}</span>
                                    <span class="sort-indicator" aria-hidden="true">
                                        @if($sortField === $header['field'])
                                            @if($sortDirection === 'asc')
                                                <x-solar-alt-arrow-up-linear class="w-3.5 h-3.5" />
                                            @else
                                                <x-solar-alt-arrow-down-linear class="w-3.5 h-3.5" />
                                            @endif
                                        @else
                                            <x-solar-sort-vertical-linear class="w-3.5 h-3.5" />
                                        @endif
                                    </span>
                                </button>
                            </th>
                        @endforeach
                        <th style="width: 10%; padding:0.875rem 1.25rem; text-align:center; font-size:0.75rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; color:#AD9C8A;">Kelola</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kosts as $kost)
                        <tr style="border-bottom:1px solid #fcfaf8; transition: background 0.2s;" onmouseover="this.style.background='#fdfcfb'" onmouseout="this.style.background='transparent'">
                            <td style="padding:0.875rem 1.25rem; font-size:0.85rem; color:#AD9C8A; font-weight: 500;">#{{ $kost->id_kost }}</td>
                            <td style="padding:0.875rem 1.25rem;">
                                <div style="display:flex; align-items:center; gap:0.75rem;">
                                    <div style="width:40px; height:40px; border-radius:0.5rem; overflow:hidden; background-color:#f0ebe1; flex-shrink:0; border: 1px solid #f0ebe1;">
                                        <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; color:#AD9C8A;">
                                            <x-solar-buildings-linear class="w-6 h-6" />
                                        </div>
                                    </div>
                                    <div style="display:flex; flex-direction:column; gap:0.125rem;">
                                        <p style="margin:0; font-size:0.875rem; font-weight:700; color:#3f2419;">{{ $kost->nama_kost }}</p>
                                    </div>
                                </div>
                            </td>
                            <td style="padding:0.875rem 1.25rem;">
                                <div style="display:flex; flex-direction:column;">
                                    <span style="font-size:0.95rem; font-weight:800; color:#3f2419;">Rp {{ number_format($kost->harga, 0, ',', '.') }}</span>
                                    <span style="font-size:0.7rem; color:#AD9C8A;">per bulan</span>
                                </div>
                            </td>
                            <td style="padding:0.875rem 1.25rem;">
                                <div style="display:flex; flex-direction:column; gap:0.375rem;">
                                    @php
                                        $tipeKosLower = strtolower($kost->tipe_kos);
                                        $tipeConfig = match(true) { 
                                            str_contains($tipeKosLower, 'putra') => ['bg' => '#eff6ff', 'text' => '#1e40af', 'icon' => 'solar-men-linear'], 
                                            str_contains($tipeKosLower, 'putri') => ['bg' => '#fdf2f8', 'text' => '#9d174d', 'icon' => 'solar-women-linear'], 
                                            str_contains($tipeKosLower, 'campur') => ['bg' => '#f0fdf4', 'text' => '#166534', 'icon' => 'solar-users-group-two-rounded-linear'],
                                            default => ['bg' => '#f8fafc', 'text' => '#475569', 'icon' => 'solar-home-linear']
                                        };
                                    @endphp
                                    <div style="display:inline-flex; align-items:center; gap:0.25rem; padding:0.125rem 0.5rem; border-radius:0.25rem; font-size:0.65rem; font-weight:700; background-color:{{ $tipeConfig['bg'] }}; color:{{ $tipeConfig['text'] }}; text-transform:uppercase; align-self: flex-start;">
                                        @if(str_contains($tipeKosLower, 'putra')) <x-solar-men-linear class="w-3 h-3" />
                                        @elseif(str_contains($tipeKosLower, 'putri')) <x-solar-women-linear class="w-3 h-3" />
                                        @else <x-solar-users-group-two-rounded-linear class="w-3 h-3" /> @endif
                                        {{ $kost->tipe_kos }}
                                    </div>
                                    <div style="display:flex; align-items:center; gap:0.25rem; color:#7C929E; font-size:0.75rem;">
                                        <x-solar-ruler-linear class="w-3.5 h-3.5" />
                                        <span>{{ $kost->sepesifikasi_tipe_kamar ?? '-' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td style="padding:0.875rem 1.25rem; text-align:center;">
                                <button wire:click="openFasilitas({{ $kost->id_kost }})" style="padding:0.375rem 0.75rem; border-radius:0.5rem; font-size:0.75rem; font-weight:600; font-family:'Poppins',sans-serif; border:1px solid #f0ebe1; background:#ffffff; color:#5D4D34; cursor:pointer; display:inline-flex; align-items:center; gap:0.25rem; transition:0.2s;" onmouseover="this.style.borderColor='#3f2419'; this.style.color='#3f2419'; this.style.background='#fdfcfb';" onmouseout="this.style.borderColor='#f0ebe1'; this.style.color='#5D4D34'; this.style.background='#ffffff';">
                                    <x-solar-list-linear class="w-3.5 h-3.5" />
                                    Lihat
                                </button>
                            </td>
                            <td style="padding:0.875rem 1.25rem;">
                                <div style="display:flex; align-items:center; justify-content:center; gap:0.5rem;">
                                    <button wire:click="openEdit({{ $kost->id_kost }})" class="action-btn action-edit" title="Edit Data">
                                        <x-solar-pen-new-square-linear class="w-4 h-4" />
                                    </button>
                                    <button wire:click="confirmDelete({{ $kost->id_kost }})" class="action-btn action-delete" title="Hapus Data">
                                        <x-solar-trash-bin-trash-linear class="w-4 h-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="padding:3rem 1.5rem; text-align:center;">
                                <div style="display:flex; flex-direction:column; align-items:center; gap:0.75rem; color:#AD9C8A;">
                                    <x-solar-case-minimalistic-linear class="w-12 h-12 opacity-20" />
                                    <p style="font-size:0.875rem; font-weight:500;">
                                        {{ $search || $filterTipe ? 'Pencarian tidak menemukan hasil yang cocok.' : 'Belum ada data kost yang terdaftar.' }}
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination Container --}}
        <div style="padding: 1rem 1.25rem; background-color: #fcfaf8; border-top: 1.5px solid #f0ebe1;">
            <div class="custom-pagination">
                {{ $kosts->links() }}
            </div>
        </div>
    </div>

    {{-- CSS Styles --}}
    <style>
        .sortable-table-header {
            width: 100%;
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0;
            border: 0;
            background: transparent;
            color: inherit;
            font: inherit;
            letter-spacing: inherit;
            text-transform: inherit;
            cursor: pointer;
        }
        .sortable-table-header:hover,
        .sortable-table-header.is-active {
            color: #3f2419;
        }
        .sort-indicator {
            width: 1rem;
            height: 1rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #AD9C8A;
            flex-shrink: 0;
        }
        .sortable-table-header:hover .sort-indicator,
        .sortable-table-header.is-active .sort-indicator {
            color: #3f2419;
        }

        .btn-primary-gradient {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            background: linear-gradient(135deg, #3f2419 0%, #5D4D34 100%);
            color: #ffffff;
            border: none;
            padding: 0.6rem 1.25rem;
            border-radius: 0.5rem;
            font-size: 0.85rem;
            font-weight: 700;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 10px rgba(63,36,25,0.15);
        }
        .btn-primary-gradient:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(63,36,25,0.25);
            background: linear-gradient(135deg, #2a1811 0%, #3f2419 100%);
        }
        .btn-primary-gradient:active { transform: translateY(0); }

        .action-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 0.5rem;
            border: 1px solid #f0ebe1;
            background: #ffffff;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .action-edit { color: #AD9C8A; }
        .action-edit:hover { border-color: #3f2419; color: #3f2419; background: #fdfcfb; transform: scale(1.05); }
        .action-delete { color: #f87171; }
        .action-delete:hover { border-color: #ef4444; color: #ef4444; background: #fef2f2; transform: scale(1.05); }

        /* Pagination Refinement */
        .custom-pagination nav { display: flex; align-items: center; justify-content: space-between; width: 100%; }
        .custom-pagination nav p { margin: 0; font-size: 0.8rem; color: #7C929E; }

        /* Outer wrapper for desktop buttons */
        .custom-pagination nav .shadow-sm { box-shadow: none; display: flex; gap: 0.375rem; align-items: center; }

        /* Individual button targets (ignoring the z-0 wrapper) */
        .custom-pagination nav span.relative:not(.z-0),
        .custom-pagination nav a.relative {
            padding: 0;
            border-radius: 0.5rem !important; /* Override Laravel's left/right only rounding */
            font-size: 0.8rem;
            font-weight: 600;
            color: #5D4D34;
            background: #ffffff;
            border: 1px solid #f0ebe1;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2.25rem;
            height: 2.25rem;
            text-decoration: none;
            margin-left: 0 !important; /* Remove Tailwind's negative margin (-ml-px) */
            box-sizing: border-box;
        }

        .custom-pagination nav a.relative:hover { 
            border-color: #3f2419; 
            color: #3f2419; 
            background: #fdfcfb; 
            transform: scale(1.05); /* Slight enlargement on hover only */
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        /* Active State - Force it back to normal size so it doesn't stay enlarged */
        .custom-pagination span[aria-current="page"] .relative { 
            background: #3f2419 !important; 
            color: #ffffff !important; 
            border-color: #3f2419 !important; 
            transform: scale(1) !important;
            box-shadow: none !important;
        }

        .custom-pagination span[aria-disabled="true"] .relative { 
            color: #AD9C8A; 
            background: #fcfaf8; 
            cursor: not-allowed; 
            border-color: #f0ebe1; 
            transform: none !important;
        }        
        /* Tailwind utility classes fallback for Laravel Pagination */
        .hidden { display: none; }
        @media (min-width: 640px) {
            .sm\:hidden { display: none; }
            .sm\:flex { display: flex; }
            .sm\:flex-1 { flex: 1 1 0%; }
            .sm\:items-center { align-items: center; }
            .sm\:justify-between { justify-content: space-between; }
        }
    </style>

    {{-- ===== CREATE / EDIT MODAL ===== --}}
    @if($showModal)
        <div style="position:fixed; inset:0; z-index:100; display:flex; align-items:center; justify-content:center; padding:1rem; backdrop-filter: blur(4px); background-color:rgba(63,36,25,0.4);">
            <div class="animate-fade-in" style="width:100%; max-width:640px; background-color:#ffffff; border-radius:1rem; box-shadow:0 20px 40px rgba(0,0,0,0.15); max-height:92vh; overflow:hidden; display:flex; flex-direction:column;">
                
                {{-- Modal Header --}}
                <div style="display:flex; align-items:center; justify-content:space-between; padding:1.25rem 1.5rem; border-bottom:1px solid #f0ebe1; background:#fcfaf8;">
                    <div style="display:flex; align-items:center; gap:0.75rem;">
                        <div style="width:36px; height:36px; border-radius:0.75rem; background:#3f2419; display:flex; align-items:center; justify-content:center; color:#ffffff;">
                            @if($editingId) <x-solar-pen-new-square-linear class="w-5 h-5" /> @else <x-solar-add-circle-linear class="w-5 h-5" /> @endif
                        </div>
                        <div>
                            <h3 style="margin:0; font-size:1.1rem; font-weight:700; color:#3f2419;">{{ $editingId ? 'Edit Data Kost' : 'Tambah Kost Baru' }}</h3>
                            <p style="margin:0.125rem 0 0; font-size:0.75rem; color:#AD9C8A;">Lengkapi formulir di bawah ini dengan benar.</p>
                        </div>
                    </div>
                    <button wire:click="$set('showModal', false)" style="width:32px; height:32px; border-radius:0.5rem; border:none; background:#f0ebe1; color:#3f2419; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:0.2s;" onmouseover="this.style.background='#e2d9cc'" onmouseout="this.style.background='#f0ebe1'">
                        <x-solar-close-circle-linear class="w-5 h-5" />
                    </button>
                </div>

                <form wire:submit="save" style="display:flex; flex-direction:column; overflow:hidden;">
                    <div style="padding:1.5rem; overflow-y:auto; flex:1; display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                        
                        <div style="grid-column:1/-1;">
                            <label style="display:flex; align-items:center; gap:0.375rem; font-size:0.8rem; font-weight:600; color:#3f2419; margin-bottom:0.375rem;">
                                <x-solar-buildings-linear class="w-3.5 h-3.5 text-amber-700" /> Nama Kost *
                            </label>
                            <input wire:model="nama_kost" type="text" placeholder="Contoh: Kost Eksklusif Dago..."
                                   style="width:100%; box-sizing:border-box; padding:0.6rem 0.875rem; background-color:#fcfaf8; border:1px solid #f0ebe1; border-radius:0.5rem; font-size:0.875rem; font-family:'Poppins',sans-serif; color:#3f2419; outline:none; transition:0.2s;"
                                   onfocus="this.style.borderColor='#3f2419'; this.style.backgroundColor='#ffffff'">
                            @error('nama_kost') <p style="font-size:0.7rem; color:#ef4444; margin:0.25rem 0 0;">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label style="display:flex; align-items:center; gap:0.375rem; font-size:0.8rem; font-weight:600; color:#3f2419; margin-bottom:0.375rem;">
                                <x-solar-wad-of-money-linear class="w-3.5 h-3.5 text-amber-700" /> Harga / Bulan *
                            </label>
                            <div style="position:relative;">
                                <span style="position:absolute; left:0.875rem; top:50%; transform:translateY(-50%); font-weight:600; font-size:0.875rem; color:#AD9C8A;">Rp</span>
                                <input wire:model="harga" type="number" placeholder="0"
                                       style="width:100%; box-sizing:border-box; padding:0.6rem 0.875rem 0.6rem 2.25rem; background-color:#fcfaf8; border:1px solid #f0ebe1; border-radius:0.5rem; font-size:0.875rem; font-weight:600; font-family:'Poppins',sans-serif; color:#3f2419; outline:none; transition:0.2s;"
                                       onfocus="this.style.borderColor='#3f2419'">
                            </div>
                            @error('harga') <p style="font-size:0.7rem; color:#ef4444; margin:0.25rem 0 0;">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label style="display:flex; align-items:center; gap:0.375rem; font-size:0.8rem; font-weight:600; color:#3f2419; margin-bottom:0.375rem;">
                                <x-solar-users-group-two-rounded-linear class="w-3.5 h-3.5 text-amber-700" /> Tipe Kos *
                            </label>
                            <select wire:model="tipe_kos" style="width:100%; box-sizing:border-box; padding:0.6rem 0.875rem; background-color:#fcfaf8; border:1px solid #f0ebe1; border-radius:0.5rem; font-size:0.875rem; font-family:'Poppins',sans-serif; color:#3f2419; outline:none; cursor:pointer;">
                                <option value="Kos Putri">Kos Putri</option>
                                <option value="Kos Putra">Kos Putra</option>
                                <option value="Kos Campur">Kos Campur</option>
                            </select>
                        </div>

                        <div style="grid-column:1/-1;">
                            <label style="display:flex; align-items:center; gap:0.375rem; font-size:0.8rem; font-weight:600; color:#3f2419; margin-bottom:0.375rem;">
                                <x-solar-ruler-linear class="w-3.5 h-3.5 text-amber-700" /> Spesifikasi Tipe Kamar
                            </label>
                            <input wire:model="sepesifikasi_tipe_kamar" type="text" placeholder="Contoh: 3x4 meter, Termasuk Listrik..."
                                   style="width:100%; box-sizing:border-box; padding:0.6rem 0.875rem; background-color:#fcfaf8; border:1px solid #f0ebe1; border-radius:0.5rem; font-size:0.875rem; font-family:'Poppins',sans-serif; color:#3f2419; outline:none;"
                                   onfocus="this.style.borderColor='#3f2419'">
                        </div>

                        <div style="grid-column:1/-1;">
                            <label style="display:flex; align-items:center; gap:0.375rem; font-size:0.8rem; font-weight:600; color:#3f2419; margin-bottom:0.375rem;">
                                <x-solar-star-linear class="w-3.5 h-3.5 text-amber-700" /> Fasilitas Kamar
                            </label>
                            <textarea wire:model="fasilitas_kamar" rows="2" placeholder="Sebutkan fasilitas di dalam kamar..."
                                      style="width:100%; box-sizing:border-box; padding:0.6rem 0.875rem; background-color:#fcfaf8; border:1px solid #f0ebe1; border-radius:0.5rem; font-size:0.875rem; font-family:'Poppins',sans-serif; color:#3f2419; outline:none; resize:none; transition:0.2s;"
                                      onfocus="this.style.borderColor='#3f2419'"></textarea>
                        </div>

                        <div>
                            <label style="display:flex; align-items:center; gap:0.375rem; font-size:0.8rem; font-weight:600; color:#3f2419; margin-bottom:0.375rem;">
                                <x-solar-bath-linear class="w-3.5 h-3.5 text-amber-700" /> Fasilitas Kamar Mandi
                            </label>
                            <input wire:model="fasilitas_kamar_mandi" type="text" placeholder="Contoh: K. Mandi Dalam..."
                                   style="width:100%; box-sizing:border-box; padding:0.6rem 0.875rem; background-color:#fcfaf8; border:1px solid #f0ebe1; border-radius:0.5rem; font-size:0.875rem; font-family:'Poppins',sans-serif; color:#3f2419; outline:none;"
                                   onfocus="this.style.borderColor='#3f2419'">
                        </div>

                        <div>
                            <label style="display:flex; align-items:center; gap:0.375rem; font-size:0.8rem; font-weight:600; color:#3f2419; margin-bottom:0.375rem;">
                                <x-solar-sofa-linear class="w-3.5 h-3.5 text-amber-700" /> Fasilitas Umum
                            </label>
                            <input wire:model="fasilitas_umum" type="text" placeholder="Contoh: WiFi, Dapur..."
                                   style="width:100%; box-sizing:border-box; padding:0.6rem 0.875rem; background-color:#fcfaf8; border:1px solid #f0ebe1; border-radius:0.5rem; font-size:0.875rem; font-family:'Poppins',sans-serif; color:#3f2419; outline:none;"
                                   onfocus="this.style.borderColor='#3f2419'">
                        </div>

                        <div>
                            <label style="display:flex; align-items:center; gap:0.375rem; font-size:0.8rem; font-weight:600; color:#3f2419; margin-bottom:0.375rem;">
                                <x-solar-routing-2-linear class="w-3.5 h-3.5 text-amber-700" /> Fasilitas Parkir
                            </label>
                            <input wire:model="fasilitas_parkir" type="text" placeholder="Contoh: Parkir Motor..."
                                   style="width:100%; box-sizing:border-box; padding:0.6rem 0.875rem; background-color:#fcfaf8; border:1px solid #f0ebe1; border-radius:0.5rem; font-size:0.875rem; font-family:'Poppins',sans-serif; color:#3f2419; outline:none;"
                                   onfocus="this.style.borderColor='#3f2419'">
                        </div>

                        <div>
                            <label style="display:flex; align-items:center; gap:0.375rem; font-size:0.8rem; font-weight:600; color:#3f2419; margin-bottom:0.375rem;">
                                <x-solar-map-point-linear class="w-3.5 h-3.5 text-amber-700" /> Tempat Terdekat
                            </label>
                            <input wire:model="tempat_terdekat" type="text" placeholder="Contoh: Kampus, Minimarket..."
                                   style="width:100%; box-sizing:border-box; padding:0.6rem 0.875rem; background-color:#fcfaf8; border:1px solid #f0ebe1; border-radius:0.5rem; font-size:0.875rem; font-family:'Poppins',sans-serif; color:#3f2419; outline:none;"
                                   onfocus="this.style.borderColor='#3f2419'">
                        </div>

                        <div style="grid-column:1/-1;">
                            <label style="display:flex; align-items:center; gap:0.375rem; font-size:0.8rem; font-weight:600; color:#3f2419; margin-bottom:0.375rem;">
                                <x-solar-document-text-linear class="w-3.5 h-3.5 text-amber-700" /> Peraturan Kos
                            </label>
                            <textarea wire:model="peraturan_kos" rows="2" placeholder="Sebutkan peraturan kos..."
                                      style="width:100%; box-sizing:border-box; padding:0.6rem 0.875rem; background-color:#fcfaf8; border:1px solid #f0ebe1; border-radius:0.5rem; font-size:0.875rem; font-family:'Poppins',sans-serif; color:#3f2419; outline:none; resize:none; transition:0.2s;"
                                      onfocus="this.style.borderColor='#3f2419'"></textarea>
                        </div>

                        <div style="grid-column:1/-1;">
                            <label style="display:flex; align-items:center; gap:0.375rem; font-size:0.8rem; font-weight:600; color:#3f2419; margin-bottom:0.375rem;">
                                <x-solar-link-linear class="w-3.5 h-3.5 text-amber-700" /> Link Original
                            </label>
                            <input wire:model="link_original" type="url" placeholder="Paste URL sumber..."
                                   style="width:100%; box-sizing:border-box; padding:0.6rem 0.875rem; background-color:#fcfaf8; border:1px solid #f0ebe1; border-radius:0.5rem; font-size:0.875rem; font-family:'Poppins',sans-serif; color:#3f2419; outline:none;"
                                   onfocus="this.style.borderColor='#3f2419'">
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div style="padding:1rem 1.5rem; background:#fcfaf8; border-top:1px solid #f0ebe1; display:flex; justify-content:flex-end; gap:0.75rem;">
                        <button type="button" wire:click="$set('showModal', false)"
                                style="padding:0.6rem 1.25rem; border-radius:0.5rem; font-size:0.85rem; font-weight:600; font-family:'Poppins',sans-serif; border:1px solid #f0ebe1; background:#ffffff; color:#3f2419; cursor:pointer; transition:0.2s;"
                                onmouseover="this.style.borderColor='#3f2419'" onmouseout="this.style.borderColor='#f0ebe1'">
                            Batal
                        </button>
                        <button type="submit" class="btn-primary-gradient">
                            <x-solar-diskette-linear class="w-4 h-4" />
                            <span>{{ $editingId ? 'Simpan' : 'Tambah Kost' }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- ===== DELETE CONFIRM MODAL ===== --}}
    @if($showDeleteModal)
        <div style="position:fixed; inset:0; z-index:110; display:flex; align-items:center; justify-content:center; padding:1rem; backdrop-filter: blur(4px); background-color:rgba(63,36,25,0.4);">
            <div class="animate-fade-in" style="width:100%; max-width:400px; background-color:#ffffff; border-radius:1rem; padding:2rem; text-align:center; box-shadow:0 20px 40px rgba(0,0,0,0.15);">
                <div style="width:64px; height:64px; border-radius:1rem; background-color:#fef2f2; display:flex; align-items:center; justify-content:center; margin:0 auto 1.25rem; color:#ef4444;">
                    <x-solar-danger-triangle-linear class="w-8 h-8" />
                </div>
                <h3 style="margin:0 0 0.5rem; font-size:1.25rem; font-weight:800; color:#3f2419;">Konfirmasi Hapus</h3>
                <p style="margin:0 0 1.5rem; font-size:0.9rem; line-height:1.5; color:#7C929E;">Data properti ini akan dihapus secara permanen dari sistem. Lanjutkan?</p>
                <div style="display:flex; gap:0.75rem; justify-content:center;">
                    <button wire:click="$set('showDeleteModal', false)"
                            style="flex:1; padding:0.75rem; border-radius:0.5rem; font-size:0.875rem; font-weight:600; font-family:'Poppins',sans-serif; border:1px solid #f0ebe1; background:#ffffff; color:#3f2419; cursor:pointer;">
                        Batal
                    </button>
                    <button wire:click="delete" style="flex:1; padding:0.75rem; border-radius:0.5rem; font-size:0.875rem; font-weight:600; font-family:'Poppins',sans-serif; border:none; background:#ef4444; color:#ffffff; cursor:pointer; box-shadow: 0 4px 10px rgba(239,68,68,0.2);">
                        Ya, Hapus
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- ===== FASILITAS MODAL ===== --}}
    @if($showFasilitasModal && $selectedKostFasilitas)
        <div style="position:fixed; inset:0; z-index:120; display:flex; align-items:center; justify-content:center; padding:1rem; backdrop-filter: blur(4px); background-color:rgba(63,36,25,0.4);">
            <div class="animate-fade-in" style="width:100%; max-width:640px; background-color:#ffffff; border-radius:1rem; box-shadow:0 20px 40px rgba(0,0,0,0.15); max-height:92vh; overflow:hidden; display:flex; flex-direction:column;">
                
                {{-- Modal Header --}}
                <div style="display:flex; align-items:center; justify-content:space-between; padding:1.25rem 1.5rem; border-bottom:1px solid #f0ebe1; background:#fcfaf8;">
                    <div style="display:flex; align-items:center; gap:0.75rem;">
                        <div style="width:36px; height:36px; border-radius:0.75rem; background:#3f2419; display:flex; align-items:center; justify-content:center; color:#ffffff;">
                            <x-solar-list-linear class="w-5 h-5" />
                        </div>
                        <div>
                            <h3 style="margin:0; font-size:1.1rem; font-weight:700; color:#3f2419;">Detail Fasilitas</h3>
                            <p style="margin:0.125rem 0 0; font-size:0.75rem; color:#AD9C8A;">{{ $selectedKostFasilitas->nama_kost }}</p>
                        </div>
                    </div>
                    <button wire:click="closeFasilitas" style="width:32px; height:32px; border-radius:0.5rem; border:none; background:#f0ebe1; color:#3f2419; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:0.2s;" onmouseover="this.style.background='#e2d9cc'" onmouseout="this.style.background='#f0ebe1'">
                        <x-solar-close-circle-linear class="w-5 h-5" />
                    </button>
                </div>

                {{-- Modal Body - Fasilitas Table --}}
                <div style="padding:1.5rem; overflow-y:auto; flex:1;">
                    <table style="width:100%; border-collapse:collapse; text-align:left;">
                        <tbody>
                            <tr style="border-bottom:1px solid #f0ebe1;">
                                <th style="padding:0.75rem; font-size:0.85rem; color:#3f2419; width:40%; background:#fcfaf8;">Spesifikasi Tipe Kamar</th>
                                <td style="padding:0.75rem; font-size:0.85rem; color:#5D4D34;">{{ $selectedKostFasilitas->sepesifikasi_tipe_kamar ?? '-' }}</td>
                            </tr>
                            <tr style="border-bottom:1px solid #f0ebe1;">
                                <th style="padding:0.75rem; font-size:0.85rem; color:#3f2419; background:#fcfaf8;">Fasilitas Kamar</th>
                                <td style="padding:0.75rem; font-size:0.85rem; color:#5D4D34;">{{ $selectedKostFasilitas->fasilitas_kamar ?? '-' }}</td>
                            </tr>
                            <tr style="border-bottom:1px solid #f0ebe1;">
                                <th style="padding:0.75rem; font-size:0.85rem; color:#3f2419; background:#fcfaf8;">Fasilitas Kamar Mandi</th>
                                <td style="padding:0.75rem; font-size:0.85rem; color:#5D4D34;">{{ $selectedKostFasilitas->fasilitas_kamar_mandi ?? '-' }}</td>
                            </tr>
                            <tr style="border-bottom:1px solid #f0ebe1;">
                                <th style="padding:0.75rem; font-size:0.85rem; color:#3f2419; background:#fcfaf8;">Fasilitas Umum</th>
                                <td style="padding:0.75rem; font-size:0.85rem; color:#5D4D34;">{{ $selectedKostFasilitas->fasilitas_umum ?? '-' }}</td>
                            </tr>
                            <tr style="border-bottom:1px solid #f0ebe1;">
                                <th style="padding:0.75rem; font-size:0.85rem; color:#3f2419; background:#fcfaf8;">Fasilitas Parkir</th>
                                <td style="padding:0.75rem; font-size:0.85rem; color:#5D4D34;">{{ $selectedKostFasilitas->fasilitas_parkir ?? '-' }}</td>
                            </tr>
                            <tr style="border-bottom:1px solid #f0ebe1;">
                                <th style="padding:0.75rem; font-size:0.85rem; color:#3f2419; background:#fcfaf8;">Tempat Terdekat</th>
                                <td style="padding:0.75rem; font-size:0.85rem; color:#5D4D34;">{{ $selectedKostFasilitas->tempat_terdekat ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th style="padding:0.75rem; font-size:0.85rem; color:#3f2419; background:#fcfaf8;">Peraturan Kos</th>
                                <td style="padding:0.75rem; font-size:0.85rem; color:#5D4D34; white-space:pre-wrap;">{{ $selectedKostFasilitas->peraturan_kos ?? '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Modal Footer --}}
                <div style="padding:1rem 1.5rem; background:#fcfaf8; border-top:1px solid #f0ebe1; display:flex; justify-content:flex-end;">
                    <button type="button" wire:click="closeFasilitas"
                            style="padding:0.6rem 1.25rem; border-radius:0.5rem; font-size:0.85rem; font-weight:600; font-family:'Poppins',sans-serif; border:1px solid #f0ebe1; background:#ffffff; color:#3f2419; cursor:pointer; transition:0.2s;"
                            onmouseover="this.style.borderColor='#3f2419'" onmouseout="this.style.borderColor='#f0ebe1'">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- ===== IMPORT CSV MODAL ===== --}}
    @if($showImportModal)
        <div style="position:fixed; inset:0; z-index:110; display:flex; align-items:center; justify-content:center; padding:1rem; backdrop-filter: blur(4px); background-color:rgba(63,36,25,0.4);">
            <div class="animate-fade-in" style="width:100%; max-width:480px; background-color:#ffffff; border-radius:1rem; box-shadow:0 20px 40px rgba(0,0,0,0.15); display:flex; flex-direction:column;">
                
                {{-- Modal Header --}}
                <div style="display:flex; align-items:center; justify-content:space-between; padding:1.25rem 1.5rem; border-bottom:1px solid #f0ebe1; background:#fcfaf8;">
                    <div style="display:flex; align-items:center; gap:0.75rem;">
                        <div style="width:36px; height:36px; border-radius:0.75rem; background:#3f2419; display:flex; align-items:center; justify-content:center; color:#ffffff;">
                            <x-solar-upload-square-linear class="w-5 h-5" />
                        </div>
                        <div>
                            <h3 style="margin:0; font-size:1.1rem; font-weight:700; color:#3f2419;">Import Data Kost (CSV)</h3>
                        </div>
                    </div>
                    <button wire:click="closeImportModal" style="width:32px; height:32px; border-radius:0.5rem; border:none; background:#f0ebe1; color:#3f2419; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:0.2s;" onmouseover="this.style.background='#e2d9cc'" onmouseout="this.style.background='#f0ebe1'">
                        <x-solar-close-circle-linear class="w-5 h-5" />
                    </button>
                </div>

                <form wire:submit="importCSV" style="display:flex; flex-direction:column; overflow:hidden;">
                    <div style="padding:1.5rem; display:flex; flex-direction:column; gap:1rem;">
                        <div>
                            <label style="display:block; font-size:0.8rem; font-weight:600; color:#3f2419; margin-bottom:0.375rem;">Upload File CSV *</label>
                            <input wire:model="importFile" type="file" accept=".csv"
                                   style="width:100%; box-sizing:border-box; padding:0.6rem; border:1px dashed #f0ebe1; border-radius:0.5rem; background:#fcfaf8; color:#5D4D34; font-size:0.85rem;" required>
                            <div wire:loading wire:target="importFile" style="font-size:0.75rem; color:#10b981; margin-top:0.25rem;">Mengunggah file...</div>
                            @error('importFile') <span style="font-size:0.7rem; color:#ef4444; display:block; margin-top:0.25rem;">{{ $message }}</span> @enderror
                        </div>
                        <div style="background-color:#fffbeb; padding:0.75rem; border-radius:0.5rem; border:1px solid #fef3c7;">
                            <p style="font-size:0.75rem; color:#92400e; margin:0; line-height:1.5; font-weight:500;">
                                <x-solar-danger-circle-linear class="w-4 h-4 inline-block align-middle mr-1" />
                                Pastikan urutan kolom sesuai format berikut:
                            </p>
                            <p style="font-size:0.7rem; color:#92400e; margin:0.25rem 0 0; line-height:1.4;">
                                ID_Kost, Nama Kost, Harga, Tipe Kost, Spesifikasi Tipe Kamar, Fasilitas Kamar, Fasilitas Kamar Mandi, Fasilitas Umum, Fasilitas Parkir, Tempat Terdekat, Peraturan Kos, Link Original.
                            </p>
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div style="padding:1rem 1.5rem; background:#fcfaf8; border-top:1px solid #f0ebe1; display:flex; justify-content:flex-end; gap:0.75rem;">
                        <button type="button" wire:click="closeImportModal"
                                style="padding:0.6rem 1.25rem; border-radius:0.5rem; font-size:0.85rem; font-weight:600; font-family:'Poppins',sans-serif; border:1px solid #f0ebe1; background:#ffffff; color:#3f2419; cursor:pointer; transition:0.2s;"
                                onmouseover="this.style.background='#fdfcfb'; this.style.borderColor='#3f2419'" onmouseout="this.style.background='#ffffff'; this.style.borderColor='#f0ebe1'">
                            Batal
                        </button>
                        <button type="submit" class="btn-primary-gradient" wire:loading.attr="disabled" wire:target="importCSV">
                            <span wire:loading.remove wire:target="importCSV">
                                <x-solar-upload-square-linear class="w-4 h-4 inline-block mr-1" /> Import
                            </span>
                            <span wire:loading wire:target="importCSV">Memproses...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

</div>
