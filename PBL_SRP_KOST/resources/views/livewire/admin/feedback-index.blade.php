<div style="display:flex; flex-direction:column; gap:1.5rem;">
    <div>
        <h1 style="margin:0; font-size:1.5rem; font-weight:800; color:#5D4D34;">Feedback & Ulasan</h1>
        <p style="margin:0.25rem 0 0; font-size:0.875rem; color:#7C929E;">Ulasan pengguna terhadap kost</p>
    </div>

    <div style="display:flex; gap:0.75rem; flex-wrap:wrap;">
        <div style="flex:1; min-width:240px; position:relative;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#7C929E" stroke-width="2"
                 style="position:absolute; left:0.75rem; top:50%; transform:translateY(-50%); pointer-events:none;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input wire:model.live.debounce.300ms="search" id="search-feedback" type="text"
                   placeholder="Cari nama pengguna atau kost..."
                   style="width:100%; box-sizing:border-box; padding:0.5rem 0.875rem 0.5rem 2.5rem; background-color:#CDC6BA; border:1px solid #AD9C8A; border-radius:0.625rem; font-size:0.875rem; font-family:'Poppins',sans-serif; color:#5D4D34; outline:none;">
        </div>
        <select wire:model.live="filterRating" id="filter-rating"
                style="padding:0.5rem 0.875rem; background-color:#CDC6BA; border:1px solid #AD9C8A; border-radius:0.625rem; font-size:0.875rem; font-family:'Poppins',sans-serif; color:#5D4D34; outline:none; cursor:pointer;">
            <option value="">Semua Rating</option>
            @for($i = 5; $i >= 1; $i--)
                <option value="{{ $i }}">{{ $i }} Bintang</option>
            @endfor
        </select>
    </div>

    <div class="admin-table" style="overflow-x:auto;">
        <table style="width:100%; min-width:650px; border-collapse:collapse;">
            <thead>
                <tr style="background-color:#5D4D34;">
                    <th style="padding:0.75rem 1rem; text-align:left; font-size:0.7rem; font-weight:700; text-transform:uppercase; color:#DBD3C6;">Pengguna</th>
                    <th style="padding:0.75rem 1rem; text-align:left; font-size:0.7rem; font-weight:700; text-transform:uppercase; color:#DBD3C6;">Kost</th>
                    <th style="padding:0.75rem 1rem; text-align:left; font-size:0.7rem; font-weight:700; text-transform:uppercase; color:#DBD3C6;">Rating</th>
                    <th style="padding:0.75rem 1rem; text-align:left; font-size:0.7rem; font-weight:700; text-transform:uppercase; color:#DBD3C6;">Komentar</th>
                    <th style="padding:0.75rem 1rem; text-align:left; font-size:0.7rem; font-weight:700; text-transform:uppercase; color:#DBD3C6;">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($feedbacks as $fb)
                    <tr style="border-bottom:1px solid rgba(173,156,138,0.2);">
                        <td style="padding:0.75rem 1rem; font-size:0.875rem; font-weight:600; color:#5D4D34;">{{ $fb->user->nama ?? '-' }}</td>
                        <td style="padding:0.75rem 1rem; font-size:0.875rem; color:#5D4D34;">{{ $fb->kost->nama_kost ?? '-' }}</td>
                        <td style="padding:0.75rem 1rem;">
                            <div style="display:flex; gap:2px; align-items:center;">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                         fill="{{ $i <= $fb->rating ? '#C4935A' : 'none' }}"
                                         viewBox="0 0 24 24"
                                         stroke="{{ $i <= $fb->rating ? '#C4935A' : '#AD9C8A' }}" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.562.562 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>
                                    </svg>
                                @endfor
                                <span style="margin-left:0.25rem; font-size:0.75rem; color:#7C929E;">({{ $fb->rating }}/5)</span>
                            </div>
                        </td>
                        <td style="padding:0.75rem 1rem; font-size:0.875rem; color:#7C929E; max-width:200px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $fb->komentar ?? '-' }}</td>
                        <td style="padding:0.75rem 1rem; font-size:0.875rem; color:#7C929E; white-space:nowrap;">{{ $fb->created_at?->format('d M Y') ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding:3rem 1rem; text-align:center; font-size:0.875rem; color:#7C929E;">Belum ada feedback dari pengguna.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>{{ $feedbacks->links() }}</div>
</div>
