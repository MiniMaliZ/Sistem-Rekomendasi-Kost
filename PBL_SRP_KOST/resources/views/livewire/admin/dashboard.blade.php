<div class="animate-fade-in" style="display:flex; flex-direction:column; gap:1.5rem;">

    {{-- Page Header --}}
    <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
        <div>
            <h1 style="margin:0; font-size:1.5rem; font-weight:800; color:#5D4D34;">Dashboard</h1>
            <p style="margin:0.25rem 0 0; font-size:0.875rem; color:#7C929E;">Ringkasan sistem rekomendasi kost</p>
        </div>
        <div style="text-align:right;">
            <p style="margin:0; font-size:0.7rem; color:#7C929E;">Terakhir diperbarui</p>
            <p style="margin:0; font-size:0.875rem; font-weight:600; color:#5D4D34;">{{ now()->format('H:i, d M Y') }}</p>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(180px, 1fr)); gap:1rem;">

        {{-- Total Kost --}}
        <div class="stat-card">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem;">
                <div style="width:44px; height:44px; border-radius:10px; background-color:rgba(173,156,138,0.15); display:flex; align-items:center; justify-content:center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#AD9C8A" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205l3 1m1.5.5l-1.5-.5M6.75 7.364V3h-3v18m3-13.636l10.5-3.819"/>
                    </svg>
                </div>
                <span style="font-size:0.7rem; font-weight:700; padding:0.2rem 0.6rem; border-radius:9999px; background-color:rgba(173,156,138,0.15); color:#AD9C8A;">Kost</span>
            </div>
            <p style="margin:0; font-size:2rem; font-weight:800; color:#5D4D34; line-height:1;">{{ $totalKost }}</p>
            <p style="margin:0.25rem 0 0; font-size:0.8rem; color:#7C929E;">Total Data Kost</p>
        </div>

        {{-- Total User --}}
        <div class="stat-card">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem;">
                <div style="width:44px; height:44px; border-radius:10px; background-color:rgba(124,146,158,0.15); display:flex; align-items:center; justify-content:center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#7C929E" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                    </svg>
                </div>
                <span style="font-size:0.7rem; font-weight:700; padding:0.2rem 0.6rem; border-radius:9999px; background-color:rgba(124,146,158,0.15); color:#7C929E;">User</span>
            </div>
            <p style="margin:0; font-size:2rem; font-weight:800; color:#5D4D34; line-height:1;">{{ $totalUser }}</p>
            <p style="margin:0.25rem 0 0; font-size:0.8rem; color:#7C929E;">Total Pengguna</p>
        </div>

        {{-- Total Kriteria --}}
        <div class="stat-card">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem;">
                <div style="width:44px; height:44px; border-radius:10px; background-color:rgba(93,77,52,0.12); display:flex; align-items:center; justify-content:center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#5D4D34" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
                    </svg>
                </div>
                <span style="font-size:0.7rem; font-weight:700; padding:0.2rem 0.6rem; border-radius:9999px; background-color:rgba(93,77,52,0.12); color:#5D4D34;">SPK</span>
            </div>
            <p style="margin:0; font-size:2rem; font-weight:800; color:#5D4D34; line-height:1;">{{ $totalKriteria }}</p>
            <p style="margin:0.25rem 0 0; font-size:0.8rem; color:#7C929E;">Kriteria SPK</p>
        </div>

        {{-- Total Rekomendasi --}}
        <div class="stat-card">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem;">
                <div style="width:44px; height:44px; border-radius:10px; background-color:rgba(93,154,124,0.15); display:flex; align-items:center; justify-content:center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#5D9A7C" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span style="font-size:0.7rem; font-weight:700; padding:0.2rem 0.6rem; border-radius:9999px; background-color:rgba(93,154,124,0.15); color:#5D9A7C;">Rekomendasi</span>
            </div>
            <p style="margin:0; font-size:2rem; font-weight:800; color:#5D4D34; line-height:1;">{{ $totalRekomendasi }}</p>
            <p style="margin:0.25rem 0 0; font-size:0.8rem; color:#7C929E;">Hasil Rekomendasi</p>
        </div>

        {{-- Avg Rating --}}
        <div class="stat-card">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem;">
                <div style="width:44px; height:44px; border-radius:10px; background-color:rgba(196,147,90,0.15); display:flex; align-items:center; justify-content:center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#C4935A" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.562.562 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>
                    </svg>
                </div>
                <span style="font-size:0.7rem; font-weight:700; padding:0.2rem 0.6rem; border-radius:9999px; background-color:rgba(196,147,90,0.15); color:#C4935A;">Rating</span>
            </div>
            <p style="margin:0; font-size:2rem; font-weight:800; color:#5D4D34; line-height:1;">{{ $avgRating }}<span style="font-size:1rem;">/5</span></p>
            <p style="margin:0.25rem 0 0; font-size:0.8rem; color:#7C929E;">Rata-rata Rating</p>
        </div>

    </div>

    {{-- Charts + Tables Row --}}
    <div style="display:grid; grid-template-columns:1fr 2fr; gap:1.5rem;">

        {{-- Distribusi Kost --}}
        <div class="admin-table" style="padding:1.5rem;">
            <h2 style="margin:0 0 1.25rem; font-size:1rem; font-weight:700; color:#5D4D34;">Distribusi Kost per Tipe</h2>
            @php
                $totalForChart = array_sum($kostByTipe) ?: 1;
                $colors = ['Putra' => '#7C929E', 'Putri' => '#AD9C8A', 'Campur' => '#5D4D34'];
            @endphp
            <div style="display:flex; flex-direction:column; gap:1rem;">
                @foreach($kostByTipe as $tipe => $count)
                    <div>
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.375rem;">
                            <span style="font-size:0.875rem; font-weight:600; color:#5D4D34;">Kost {{ $tipe }}</span>
                            <span style="font-size:0.8rem; color:#7C929E;">{{ $count }}</span>
                        </div>
                        <div class="progress-bar-track">
                            <div class="progress-bar-fill" style="width:{{ ($count / $totalForChart) * 100 }}%; background-color:{{ $colors[$tipe] }};"></div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div style="display:flex; gap:1rem; margin-top:1.5rem; flex-wrap:wrap;">
                @foreach($kostByTipe as $tipe => $count)
                    <div style="display:flex; align-items:center; gap:0.4rem;">
                        <div style="width:10px; height:10px; border-radius:50%; background-color:{{ $colors[$tipe] }};"></div>
                        <span style="font-size:0.75rem; color:#5D4D34;">{{ $tipe }}: <strong>{{ $count }}</strong></span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Recent Rekomendasi --}}
        <div class="admin-table">
            <div style="padding:1rem 1.25rem; border-bottom:1px solid rgba(173,156,138,0.25);">
                <h2 style="margin:0; font-size:1rem; font-weight:700; color:#5D4D34;">Rekomendasi Terbaru</h2>
            </div>
            @if(count($recentRekomendasi) > 0)
                <table style="width:100%; border-collapse:collapse;">
                    <thead>
                        <tr style="background-color:#5D4D34;">
                            <th style="padding:0.75rem 1rem; text-align:left; font-size:0.7rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; color:#DBD3C6;">Pengguna</th>
                            <th style="padding:0.75rem 1rem; text-align:left; font-size:0.7rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; color:#DBD3C6;">Kost</th>
                            <th style="padding:0.75rem 1rem; text-align:left; font-size:0.7rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; color:#DBD3C6;">Skor</th>
                            <th style="padding:0.75rem 1rem; text-align:center; font-size:0.7rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; color:#DBD3C6;">Rank</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentRekomendasi as $rec)
                            <tr style="border-bottom:1px solid rgba(173,156,138,0.2);">
                                <td style="padding:0.75rem 1rem; font-size:0.875rem; color:#5D4D34; font-weight:600;">{{ $rec['user']['nama'] ?? '-' }}</td>
                                <td style="padding:0.75rem 1rem; font-size:0.875rem; color:#5D4D34;">{{ $rec['kost']['nama_kost'] ?? '-' }}</td>
                                <td style="padding:0.75rem 1rem; font-size:0.8rem; font-family:monospace; color:#7C929E;">{{ number_format($rec['skor'], 4) }}</td>
                                <td style="padding:0.75rem 1rem; text-align:center;">
                                    <span style="display:inline-flex; align-items:center; justify-content:center; width:28px; height:28px; border-radius:50%; font-size:0.75rem; font-weight:700; color:white; background-color:{{ $rec['rangking'] == 1 ? '#C4935A' : '#AD9C8A' }};">{{ $rec['rangking'] }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div style="padding:3rem 1.5rem; text-align:center;">
                    <p style="margin:0; font-size:0.875rem; color:#7C929E;">Belum ada data rekomendasi</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Recent Feedback --}}
    <div class="admin-table">
        <div style="padding:1rem 1.25rem; border-bottom:1px solid rgba(173,156,138,0.25); display:flex; align-items:center; justify-content:space-between;">
            <h2 style="margin:0; font-size:1rem; font-weight:700; color:#5D4D34;">Ulasan & Feedback Terbaru</h2>
            <a href="{{ route('admin.feedback') }}" style="font-size:0.875rem; font-weight:600; color:#AD9C8A; text-decoration:none;">Lihat Semua →</a>
        </div>
        @if(count($recentFeedback) > 0)
            <table style="width:100%; border-collapse:collapse;">
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
                    @foreach($recentFeedback as $fb)
                        <tr style="border-bottom:1px solid rgba(173,156,138,0.2);">
                            <td style="padding:0.75rem 1rem; font-size:0.875rem; font-weight:600; color:#5D4D34;">{{ $fb['user']['nama'] ?? '-' }}</td>
                            <td style="padding:0.75rem 1rem; font-size:0.875rem; color:#5D4D34;">{{ $fb['kost']['nama_kost'] ?? '-' }}</td>
                            <td style="padding:0.75rem 1rem;">
                                <div style="display:flex; gap:2px;">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="{{ $i <= $fb['rating'] ? '#C4935A' : 'none' }}" viewBox="0 0 24 24" stroke="{{ $i <= $fb['rating'] ? '#C4935A' : '#AD9C8A' }}" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.562.562 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>
                                        </svg>
                                    @endfor
                                </div>
                            </td>
                            <td style="padding:0.75rem 1rem; font-size:0.875rem; color:#7C929E; max-width:200px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $fb['komentar'] ?? '-' }}</td>
                            <td style="padding:0.75rem 1rem; font-size:0.8rem; color:#7C929E; white-space:nowrap;">{{ isset($fb['created_at']) ? \Carbon\Carbon::parse($fb['created_at'])->format('d M Y') : '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div style="padding:3rem 1.5rem; text-align:center;">
                <p style="margin:0; font-size:0.875rem; color:#7C929E;">Belum ada feedback dari pengguna</p>
            </div>
        @endif
    </div>

</div>
