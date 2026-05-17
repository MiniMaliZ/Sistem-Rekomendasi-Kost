<div style="display:flex; flex-direction:column; gap:1.5rem;">
    <div>
        <h1 style="margin:0; font-size:1.5rem; font-weight:800; color:#5D4D34;">Riwayat Aktivitas</h1>
        <p style="margin:0.25rem 0 0; font-size:0.875rem; color:#7C929E;">Log aktivitas pengguna dalam sistem</p>
    </div>

    <div style="display:flex; gap:0.75rem; flex-wrap:wrap;">
        <div style="flex:1; min-width:240px; position:relative;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#7C929E" stroke-width="2"
                 style="position:absolute; left:0.75rem; top:50%; transform:translateY(-50%); pointer-events:none;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input wire:model.live.debounce.300ms="search" id="search-riwayat" type="text"
                   placeholder="Cari nama pengguna atau kost..."
                   style="width:100%; box-sizing:border-box; padding:0.5rem 0.875rem 0.5rem 2.5rem; background-color:#CDC6BA; border:1px solid #AD9C8A; border-radius:0.625rem; font-size:0.875rem; font-family:'Poppins',sans-serif; color:#5D4D34; outline:none;">
        </div>
        <select wire:model.live="filterAksi" id="filter-aksi"
                style="padding:0.5rem 0.875rem; background-color:#CDC6BA; border:1px solid #AD9C8A; border-radius:0.625rem; font-size:0.875rem; font-family:'Poppins',sans-serif; color:#5D4D34; outline:none; cursor:pointer;">
            <option value="">Semua Aksi</option>
            <option value="lihat">Lihat</option>
            <option value="cari">Cari</option>
            <option value="rekomendasikan">Rekomendasikan</option>
        </select>
    </div>

    <div class="admin-table" style="overflow-x:auto;">
        <table style="width:100%; min-width:600px; border-collapse:collapse;">
            <thead>
                <tr style="background-color:#5D4D34;">
                    <th style="padding:0.75rem 1rem; text-align:left; font-size:0.7rem; font-weight:700; text-transform:uppercase; color:#DBD3C6;">#</th>
                    <th style="padding:0.75rem 1rem; text-align:left; font-size:0.7rem; font-weight:700; text-transform:uppercase; color:#DBD3C6;">Pengguna</th>
                    <th style="padding:0.75rem 1rem; text-align:left; font-size:0.7rem; font-weight:700; text-transform:uppercase; color:#DBD3C6;">Kost</th>
                    <th style="padding:0.75rem 1rem; text-align:left; font-size:0.7rem; font-weight:700; text-transform:uppercase; color:#DBD3C6;">Aksi</th>
                    <th style="padding:0.75rem 1rem; text-align:left; font-size:0.7rem; font-weight:700; text-transform:uppercase; color:#DBD3C6;">Waktu</th>
                </tr>
            </thead>
            <tbody>
                @forelse($riwayats as $r)
                    <tr style="border-bottom:1px solid rgba(173,156,138,0.2);">
                        <td style="padding:0.75rem 1rem; font-size:0.875rem; color:#7C929E;">{{ $r->id_riwayat }}</td>
                        <td style="padding:0.75rem 1rem; font-size:0.875rem; font-weight:600; color:#5D4D34;">{{ $r->user->nama ?? '-' }}</td>
                        <td style="padding:0.75rem 1rem; font-size:0.875rem; color:#5D4D34;">{{ $r->kost->nama_kost ?? '-' }}</td>
                        <td style="padding:0.75rem 1rem;">
                            @php
                                $aksiColor = match($r->aksi) { 'lihat' => '#7C929E', 'cari' => '#AD9C8A', 'rekomendasikan' => '#5D9A7C', default => '#7C929E' };
                            @endphp
                            <span style="display:inline-flex; align-items:center; gap:0.375rem; padding:0.2rem 0.65rem; border-radius:9999px; font-size:0.75rem; font-weight:600; background-color:{{ $aksiColor }}20; color:{{ $aksiColor }};">
                                {{ ucfirst($r->aksi ?? '-') }}
                            </span>
                        </td>
                        <td style="padding:0.75rem 1rem; font-size:0.875rem; color:#7C929E; white-space:nowrap;">{{ $r->created_at?->format('d M Y, H:i') ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding:3rem 1rem; text-align:center; font-size:0.875rem; color:#7C929E;">Belum ada riwayat aktivitas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>{{ $riwayats->links() }}</div>
</div>
