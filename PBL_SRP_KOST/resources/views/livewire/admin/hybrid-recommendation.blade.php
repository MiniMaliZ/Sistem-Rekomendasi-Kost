<div class="hybrid-page">
    <section class="hybrid-hero">
        <div>
            <p class="hybrid-eyebrow">Admin rekomendasi</p>
            <h2>Hybrid AHP-SAW</h2>
            <p class="hybrid-copy">Laravel memanggil subsistem FastAPI untuk menghitung AHP dan ranking SAW.</p>
        </div>
        <div class="hybrid-metrics">
            <div class="hybrid-metric {{ $apiStatus === 'online' ? 'is-good' : 'is-warning' }}">
                <span>{{ $apiStatus === 'online' ? 'ON' : 'OFF' }}</span>
                <small>FastAPI</small>
            </div>
            <div class="hybrid-metric">
                <span>{{ $result['summary']['total_alternatives'] }}</span>
                <small>Data kost</small>
            </div>
            <div class="hybrid-metric">
                <span>{{ $result['summary']['filtered_alternatives'] }}</span>
                <small>Lolos filter</small>
            </div>
            <div class="hybrid-metric {{ $result['ahp']['is_consistent'] ? 'is-good' : 'is-warning' }}">
                <span>{{ number_format($result['ahp']['cr'], 4) }}</span>
                <small>CR AHP</small>
            </div>
        </div>
    </section>

    @if($apiStatus !== 'online')
        <section class="api-warning">
            <x-solar-danger-circle-linear class="w-5 h-5" />
            <div>
                <strong>FastAPI rekomendasi belum aktif</strong>
                <span>Jalankan FastAPI di {{ $recommendationApiUrl }}. Detail: {{ $apiError }}</span>
            </div>
        </section>
    @endif

    <form wire:submit.prevent="applyPreferences" class="hybrid-form">
    <section class="hybrid-workspace">
        <div class="hybrid-panel preference-panel">
            <div class="panel-heading">
                <div class="panel-icon"><x-solar-filter-linear class="w-5 h-5" /></div>
                <div>
                    <h3>Preferensi Kost</h3>
                    <p>Isi preferensi terlebih dahulu, lalu terapkan rekomendasi.</p>
                </div>
            </div>

            <div class="deferred-note {{ $hasPendingChanges ? 'is-dirty' : '' }}" wire:dirty.class="is-dirty">
                <strong wire:dirty.remove>{{ $hasPendingChanges ? 'Preferensi draft belum diterapkan' : 'Mode submit aktif' }}</strong>
                <strong wire:dirty>Preferensi berubah</strong>
                <span>Klik Terapkan Rekomendasi untuk menghitung ulang hasil AHP-SAW.</span>
            </div>

            <div class="field-grid">
                <label class="field-control">
                    <span>Anggaran maksimal / bulan</span>
                    <div class="input-prefix">
                        <b>Rp</b>
                        <input wire:model="draftBudgetMax" type="number" min="0" step="50000">
                    </div>
                </label>

                <label class="field-control">
                    <span>Jarak kampus maksimal dari kos</span>
                    <div class="input-prefix">
                        <x-solar-map-point-linear class="w-4 h-4" />
                        <input wire:model="draftMaxDistance" type="number" min="0" step="0.1">
                        <b>km</b>
                    </div>
                </label>
            </div>

            <div class="preference-block">
                <div class="block-title">
                    <x-solar-users-group-two-rounded-linear class="w-4 h-4" />
                    <span>Tipe Kost</span>
                </div>
                <div class="segmented-options">
                    @foreach(['' => 'Semua', 'Kos Putra' => 'Putra', 'Kos Putri' => 'Putri', 'Kos Campur' => 'Campur'] as $value => $label)
                        <label class="segmented-choice">
                            <input type="radio" wire:model="draftTipeKos" value="{{ $value }}" @checked($draftTipeKos === $value)>
                            <span>{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="preference-block">
                <div class="block-title">
                    <x-solar-list-linear class="w-4 h-4" />
                    <span>Fasilitas</span>
                </div>
                <div class="facility-grid">
                    @foreach($facilityOptions as $key => $option)
                        @php($isSelected = in_array($key, $draftSelectedFacilities, true))
                        <label class="facility-choice">
                            <input type="checkbox" wire:model="draftSelectedFacilities" value="{{ $key }}" @checked($isSelected)>
                            <span class="choice-dot">
                                <x-solar-check-circle-linear class="w-4 h-4" />
                            </span>
                            <span>{{ $option['label'] }}</span>
                        </label>
                    @endforeach
                </div>

                <label class="toggle-row">
                    <input wire:model="draftRequireAllFacilities" type="checkbox">
                    <span>Wajib cocok semua fasilitas yang dipilih</span>
                </label>
            </div>

            <div class="field-grid compact">
                <label class="field-control">
                    <span>Jumlah hasil</span>
                    <select wire:model="draftTopN">
                        <option value="5">Top 5</option>
                        <option value="10">Top 10</option>
                        <option value="20">Top 20</option>
                    </select>
                </label>
                <button type="button" wire:click="resetPreferences" class="ghost-action">Reset Form</button>
                <button type="submit" class="primary-action" wire:loading.attr="disabled" wire:target="applyPreferences">
                    <x-solar-check-circle-linear class="w-4 h-4" />
                    <span wire:loading.remove wire:target="applyPreferences">Terapkan Rekomendasi</span>
                    <span wire:loading wire:target="applyPreferences">Menghitung...</span>
                </button>
            </div>
        </div>

        <div class="hybrid-panel ahp-panel">
            <div class="panel-heading">
                <div class="panel-icon"><x-solar-star-linear class="w-5 h-5" /></div>
                <div>
                    <h3>Bobot AHP</h3>
                    <p>Pairwise comparison dan uji konsistensi.</p>
                </div>
            </div>

            <div class="segmented-options scenario-options">
                @foreach($scenarios as $key => $label)
                    <label class="segmented-choice">
                        <input type="radio" wire:model="draftScenario" value="{{ $key }}" @checked($draftScenario === $key)>
                        <span>{{ $label }}</span>
                    </label>
                @endforeach
            </div>

            <div class="consistency-strip {{ $result['ahp']['is_consistent'] ? 'is-good' : 'is-warning' }}">
                <div>
                    <strong>{{ $result['ahp']['is_consistent'] ? 'Konsisten' : 'Perlu revisi' }}</strong>
                    <span>CR {{ number_format($result['ahp']['cr'], 4) }} / batas 0.1000</span>
                </div>
                <x-solar-check-circle-linear class="w-6 h-6" />
            </div>

            <div class="weight-list">
                @foreach($result['ahp']['criteria'] as $criterion)
                    @php($weight = $result['ahp']['weight_map'][$criterion['key']] ?? 0)
                    <div class="weight-row">
                        <div class="weight-label">
                            <span>{{ $criterion['name'] }}</span>
                            <strong>{{ number_format($weight * 100, 2) }}%</strong>
                        </div>
                        <div class="weight-track">
                            <span style="width: {{ max(3, $weight * 100) }}%;"></span>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="ahp-detail-grid">
                <div>
                    <span>Lambda max</span>
                    <strong>{{ number_format($result['ahp']['lambda_max'], 4) }}</strong>
                </div>
                <div>
                    <span>CI</span>
                    <strong>{{ number_format($result['ahp']['ci'], 4) }}</strong>
                </div>
                <div>
                    <span>RI</span>
                    <strong>{{ number_format($result['ahp']['ri'], 2) }}</strong>
                </div>
            </div>
        </div>
    </section>
    </form>

    <section class="hybrid-results">
        <div class="results-heading">
            <div>
                <h3>Hasil Ranking SAW</h3>
                <p>Skor akhir memakai bobot AHP aktif.</p>
            </div>
            <div class="score-pill">Max {{ number_format($result['summary']['max_score'], 4) }}</div>
        </div>

        @if(count($result['rows']) === 0)
            <div class="empty-state">
                <x-solar-danger-circle-linear class="w-8 h-8" />
                <span>Tidak ada kost yang cocok dengan filter saat ini.</span>
            </div>
        @else
            <div class="results-table-wrap">
                <table class="results-table">
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Kost</th>
                            <th>Skor</th>
                            <th>Harga</th>
                            <th>Jarak</th>
                            <th>Fasilitas</th>
                            <th>Kontribusi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($result['rows'] as $row)
                            <tr>
                                <td>
                                    <span class="rank-badge">#{{ $row['rank'] }}</span>
                                </td>
                                <td>
                                    <div class="kost-cell">
                                        <div class="kost-photo">
                                            @if($row['foto_bangunan_url'])
                                                <img src="{{ $row['foto_bangunan_url'] }}" alt="Foto {{ $row['nama_kost'] }}" loading="lazy">
                                            @else
                                                <x-solar-buildings-linear class="w-5 h-5" />
                                            @endif
                                        </div>
                                        <div>
                                            <strong>{{ $row['nama_kost'] }}</strong>
                                            <span>{{ $row['tipe_kos'] }} - {{ $row['spesifikasi_tipe_kamar'] ?? '-' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="score-cell">
                                        <strong>{{ number_format($row['score'], 4) }}</strong>
                                        <span>{{ number_format($row['score_percent'], 1) }}%</span>
                                    </div>
                                </td>
                                <td>Rp {{ number_format($row['harga'], 0, ',', '.') }}</td>
                                <td>
                                    {{ $row['jarak_kampus_asli'] !== null ? number_format($row['jarak_kampus_asli'], 2) . ' km' : '-' }}
                                </td>
                                <td>
                                    <span class="facility-count">{{ $row['matched_facility_count'] }}/{{ max(count($selectedFacilities), 1) }}</span>
                                </td>
                                <td>
                                    <div class="contribution-bars">
                                        @foreach($result['ahp']['criteria'] as $criterion)
                                            @php($contribution = $row['contributions'][$criterion['key']] ?? 0)
                                            <span title="{{ $criterion['name'] }}: {{ number_format($contribution, 4) }}" style="height: {{ max(8, $contribution * 160) }}px;"></span>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>

    <style>
        .hybrid-page {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        .hybrid-form {
            display: block;
        }

        .hybrid-hero,
        .hybrid-panel,
        .hybrid-results,
        .api-warning {
            background: #ffffff;
            border: 1px solid rgba(173, 156, 138, 0.2);
            border-radius: 1rem;
            box-shadow: 0 4px 12px rgba(63, 36, 25, 0.05);
        }

        .hybrid-hero {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            padding: 1.5rem;
            align-items: center;
        }

        .hybrid-eyebrow {
            color: #2f7d62;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.25rem;
        }

        .hybrid-hero h2,
        .hybrid-panel h3,
        .hybrid-results h3 {
            color: #1e1e1e;
            font-weight: 800;
        }

        .hybrid-hero h2 {
            font-size: 1.75rem;
        }

        .hybrid-copy,
        .panel-heading p,
        .results-heading p {
            color: #7c929e;
            font-size: 0.8rem;
            margin-top: 0.25rem;
        }

        .hybrid-metrics {
            display: grid;
            grid-template-columns: repeat(4, minmax(100px, 1fr));
            gap: 0.75rem;
        }

        .hybrid-metric {
            border: 1px solid #f0ebe1;
            border-radius: 0.75rem;
            padding: 0.8rem 1rem;
            min-width: 7rem;
            background: #fcfaf8;
        }

        .hybrid-metric span {
            display: block;
            color: #3f2419;
            font-size: 1.35rem;
            font-weight: 800;
            line-height: 1;
        }

        .hybrid-metric small {
            color: #7c929e;
            font-size: 0.72rem;
        }

        .hybrid-metric.is-good {
            background: #edf8f3;
            border-color: #cceadb;
        }

        .hybrid-metric.is-warning {
            background: #fff7ed;
            border-color: #fed7aa;
        }

        .api-warning {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            padding: 1rem 1.25rem;
            background: #fff7ed;
            border-color: #fed7aa;
            color: #9a3412;
        }

        .api-warning strong,
        .api-warning span {
            display: block;
        }

        .api-warning span {
            font-size: 0.78rem;
            margin-top: 0.15rem;
            color: #b45309;
        }

        .deferred-note {
            border: 1px solid #f0ebe1;
            border-radius: 0.75rem;
            background: #fcfaf8;
            color: #5d4d34;
            margin-bottom: 1rem;
            padding: 0.8rem 0.9rem;
        }

        .deferred-note.is-dirty {
            background: #fff7ed;
            border-color: #fed7aa;
            color: #9a3412;
        }

        .deferred-note strong,
        .deferred-note span {
            display: block;
        }

        .deferred-note strong {
            font-size: 0.78rem;
            font-weight: 800;
        }

        .deferred-note span {
            font-size: 0.74rem;
            margin-top: 0.15rem;
        }

        .hybrid-workspace {
            display: grid;
            grid-template-columns: minmax(0, 1.25fr) minmax(340px, 0.75fr);
            gap: 1.25rem;
        }

        .hybrid-panel,
        .hybrid-results {
            padding: 1.25rem;
        }

        .panel-heading,
        .results-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .panel-heading {
            justify-content: flex-start;
        }

        .panel-icon {
            width: 42px;
            height: 42px;
            border-radius: 0.8rem;
            background: #f0ebe1;
            color: #3f2419;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .field-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.75rem;
        }

        .field-grid.compact {
            align-items: end;
            grid-template-columns: minmax(150px, 1fr) auto auto;
            margin-top: 1rem;
        }

        .field-control span,
        .block-title {
            color: #3f2419;
            display: block;
            font-size: 0.8rem;
            font-weight: 700;
            margin-bottom: 0.4rem;
        }

        .input-prefix,
        .field-control select {
            min-height: 42px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            border: 1px solid #f0ebe1;
            border-radius: 0.65rem;
            background: #fcfaf8;
            padding: 0 0.75rem;
            color: #5d4d34;
        }

        .input-prefix input,
        .field-control select {
            width: 100%;
            border: 0;
            outline: 0;
            background: transparent;
            color: #3f2419;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
        }

        .preference-block {
            margin-top: 1rem;
        }

        .block-title {
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .segmented-options {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 0.5rem;
        }

        .scenario-options {
            grid-template-columns: repeat(3, 1fr);
        }

        .segmented-choice,
        .ghost-action,
        .primary-action {
            min-height: 40px;
            border: 1px solid #f0ebe1;
            border-radius: 0.65rem;
            background: #ffffff;
            color: #5d4d34;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            gap: 0.4rem;
            padding: 0 0.8rem;
            transition: 0.2s ease;
        }

        .segmented-choice {
            position: relative;
        }

        .segmented-choice input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .segmented-choice:has(input:checked),
        .segmented-choice:hover {
            border-color: #3f2419;
            background: #3f2419;
            color: #ffffff;
        }

        .primary-action {
            background: #3f2419;
            border-color: #3f2419;
            color: #ffffff;
            min-width: 13rem;
        }

        .primary-action:disabled {
            cursor: wait;
            opacity: 0.7;
        }

        .facility-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0.5rem;
        }

        .facility-choice {
            min-height: 42px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            border: 1px solid #f0ebe1;
            border-radius: 0.65rem;
            background: #ffffff;
            color: #3f2419;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            font-size: 0.76rem;
            font-weight: 600;
            padding: 0 0.75rem;
            position: relative;
            text-align: left;
        }

        .facility-choice input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .facility-choice:has(input:checked) {
            border-color: #2f7d62;
            background: #f2fbf7;
            color: #1d5f49;
        }

        .choice-dot {
            width: 1rem;
            height: 1rem;
            border: 1px solid #d9d4cc;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .choice-dot svg {
            opacity: 0;
        }

        .facility-choice:has(input:checked) .choice-dot {
            border-color: #2f7d62;
            color: #2f7d62;
        }

        .facility-choice:has(input:checked) .choice-dot svg {
            opacity: 1;
        }

        .toggle-row {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 0.75rem;
            color: #5d4d34;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .consistency-strip {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            border-radius: 0.75rem;
            padding: 0.85rem 1rem;
            margin: 1rem 0;
        }

        .consistency-strip.is-good {
            background: #edf8f3;
            color: #1d5f49;
        }

        .consistency-strip.is-warning {
            background: #fff7ed;
            color: #9a3412;
        }

        .consistency-strip strong,
        .consistency-strip span {
            display: block;
        }

        .consistency-strip span {
            font-size: 0.75rem;
            margin-top: 0.2rem;
        }

        .weight-list {
            display: flex;
            flex-direction: column;
            gap: 0.8rem;
        }

        .weight-label {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            color: #3f2419;
            font-size: 0.78rem;
            font-weight: 700;
            margin-bottom: 0.3rem;
        }

        .weight-track {
            height: 8px;
            border-radius: 999px;
            background: #f0ebe1;
            overflow: hidden;
        }

        .weight-track span {
            display: block;
            height: 100%;
            border-radius: inherit;
            background: linear-gradient(90deg, #2f7d62, #3f2419);
        }

        .ahp-detail-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .ahp-detail-grid div,
        .score-pill {
            border: 1px solid #f0ebe1;
            border-radius: 0.65rem;
            background: #fcfaf8;
            padding: 0.65rem;
        }

        .ahp-detail-grid span,
        .score-pill {
            color: #7c929e;
            font-size: 0.72rem;
            font-weight: 700;
        }

        .ahp-detail-grid strong {
            display: block;
            color: #3f2419;
            margin-top: 0.2rem;
        }

        .results-table-wrap {
            overflow-x: auto;
        }

        .results-table {
            width: 100%;
            min-width: 980px;
            border-collapse: collapse;
        }

        .results-table th {
            background: #fcfaf8;
            color: #ad9c8a;
            font-size: 0.72rem;
            letter-spacing: 0.05em;
            padding: 0.8rem;
            text-align: left;
            text-transform: uppercase;
        }

        .results-table td {
            border-top: 1px solid #f6f2ed;
            color: #5d4d34;
            font-size: 0.82rem;
            padding: 0.85rem 0.8rem;
            vertical-align: middle;
        }

        .rank-badge,
        .facility-count {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            background: #3f2419;
            color: #ffffff;
            min-width: 2.4rem;
            height: 1.8rem;
            font-weight: 800;
        }

        .facility-count {
            background: #edf8f3;
            color: #1d5f49;
        }

        .kost-cell {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            min-width: 320px;
        }

        .kost-cell strong,
        .score-cell strong {
            display: block;
            color: #3f2419;
            font-weight: 800;
        }

        .kost-cell span,
        .score-cell span {
            display: block;
            color: #7c929e;
            font-size: 0.72rem;
            margin-top: 0.15rem;
        }

        .kost-photo {
            width: 52px;
            height: 42px;
            border-radius: 0.65rem;
            background: #f0ebe1;
            color: #ad9c8a;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            flex-shrink: 0;
            border: 1px solid #efe7dc;
        }

        .kost-photo img {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: cover;
        }

        .contribution-bars {
            display: flex;
            align-items: end;
            gap: 3px;
            height: 32px;
        }

        .contribution-bars span {
            width: 8px;
            min-height: 8px;
            border-radius: 999px 999px 0 0;
            background: #2f7d62;
        }

        .contribution-bars span:nth-child(even) {
            background: #84644d;
        }

        .empty-state {
            min-height: 160px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 0.75rem;
            color: #ad9c8a;
            font-weight: 700;
        }

        @media (max-width: 1180px) {
            .hybrid-hero,
            .hybrid-workspace {
                grid-template-columns: 1fr;
            }

            .hybrid-hero {
                align-items: stretch;
                flex-direction: column;
            }
        }

        @media (max-width: 820px) {
            .field-grid,
            .facility-grid,
            .hybrid-metrics,
            .segmented-options,
            .scenario-options {
                grid-template-columns: 1fr;
            }
        }
    </style>
</div>
