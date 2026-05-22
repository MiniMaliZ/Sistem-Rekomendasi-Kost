<div>
    {{-- Cards Row --}}
    <div class="stat-cards-grid">
        {{-- Total Kost --}}
        <div class="stat-card">
            <div class="stat-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#3f2419" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                </svg>
            </div>
            <div class="stat-content">
                <p class="stat-value">{{ $totalKost }}</p>
                <p class="stat-label">Total Kost</p>
            </div>
        </div>

        {{-- Total User --}}
        <div class="stat-card">
            <div class="stat-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#3f2419" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
            </div>
            <div class="stat-content">
                <p class="stat-value">{{ $totalUser }}</p>
                <p class="stat-label">Total Pengguna</p>
            </div>
        </div>
    </div>

    {{-- Charts Top Row --}}
    <div class="charts-grid-2">
        
        {{-- Sebaran Kost per Kota --}}
        <div class="chart-card">
            <div class="chart-header">
                <div>
                    <h2 class="chart-title">Sebaran Kost per Kota</h2>
                    <p class="chart-subtitle">Distribusi kost berdasarkan kota</p>
                </div>
                <div class="chart-filter">
                    <span>Semua</span>
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#1e1e1e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </div>
            </div>
            <hr class="chart-divider">
            <div class="chart-body">
                <canvas id="kotaChart"></canvas>
            </div>
        </div>

        {{-- Kost Berdasarkan Jenis --}}
        <div class="chart-card">
            <div class="chart-header">
                <div>
                    <h2 class="chart-title">Kost Berdasarkan Jenis</h2>
                    <p class="chart-subtitle">Putra, Putri, Campur</p>
                </div>
            </div>
            <hr class="chart-divider">
            <div class="chart-body pie-layout">
                <div class="pie-wrapper">
                    <canvas id="jenisChart"></canvas>
                </div>
                <div class="pie-legend" id="jenisLegend">
                    {{-- Legend will be populated via JS --}}
                </div>
            </div>
        </div>
    </div>

    {{-- Bottom Chart --}}
    <div class="chart-card full-width">
        <div class="chart-header">
            <div>
                <h2 class="chart-title">Distribusi Harga Kost</h2>
                <p class="chart-subtitle">Range harga kost per bulan (Rp)</p>
            </div>
        </div>
        <hr class="chart-divider">
        <div class="chart-body">
            <canvas id="hargaChart"></canvas>
        </div>
    </div>

    {{-- Chart Initialization Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Data from Livewire
            const dataKota = @json($kostByKota);
            const dataJenis = @json($kostByTipe);
            const dataHarga = @json($kostByHarga);

            // Chart Colors matching Figma theme
            const colorPrimary = '#84644d';
            const colorHover = '#3f2419';
            const pieColors = ['#4A3AFF', '#FF718B', '#04CE00']; // Using figma specified colors
            const fontStyle = { family: 'Inter, sans-serif', size: 12, color: '#615e83' };

            // 1. Kota Chart (Bar)
            const ctxKota = document.getElementById('kotaChart').getContext('2d');
            new Chart(ctxKota, {
                type: 'bar',
                data: {
                    labels: Object.keys(dataKota),
                    datasets: [{
                        data: Object.values(dataKota),
                        backgroundColor: colorPrimary,
                        hoverBackgroundColor: colorHover,
                        borderRadius: { topLeft: 8, topRight: 8, bottomLeft: 0, bottomRight: 0 },
                        barPercentage: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { borderDash: [5, 5], color: '#f0f0f0', drawBorder: false },
                            ticks: { font: fontStyle, stepSize: 1 }
                        },
                        x: {
                            grid: { display: false, drawBorder: false },
                            ticks: { font: fontStyle }
                        }
                    }
                }
            });

            // 2. Jenis Chart (Pie)
            const ctxJenis = document.getElementById('jenisChart').getContext('2d');
            const totalJenis = Object.values(dataJenis).reduce((a, b) => a + b, 0) || 1;
            const jenisLabels = Object.keys(dataJenis);
            const jenisValues = Object.values(dataJenis);

            new Chart(ctxJenis, {
                type: 'pie',
                data: {
                    labels: jenisLabels,
                    datasets: [{
                        data: jenisValues,
                        backgroundColor: pieColors,
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false }, tooltip: { enabled: true } }
                }
            });

            // Build Custom Legend for Pie Chart
            const legendContainer = document.getElementById('jenisLegend');
            jenisLabels.forEach((label, index) => {
                const val = jenisValues[index];
                const pct = ((val / totalJenis) * 100).toFixed(2);
                const color = pieColors[index];
                
                const legendItem = document.createElement('div');
                legendItem.className = 'legend-item';
                legendItem.innerHTML = `
                    <div class="legend-left">
                        <span class="legend-dot" style="background-color: ${color};"></span>
                        <span class="legend-label">${label}</span>
                    </div>
                    <div class="legend-right">
                        <span class="legend-pct">${pct}%</span>
                        <span class="legend-val">(${val})</span>
                    </div>
                `;
                legendContainer.appendChild(legendItem);
            });

            // 3. Harga Chart (Bar)
            const ctxHarga = document.getElementById('hargaChart').getContext('2d');
            new Chart(ctxHarga, {
                type: 'bar',
                data: {
                    labels: Object.keys(dataHarga),
                    datasets: [{
                        data: Object.values(dataHarga),
                        backgroundColor: colorPrimary,
                        hoverBackgroundColor: colorHover,
                        borderRadius: { topLeft: 8, topRight: 8, bottomLeft: 0, bottomRight: 0 },
                        barPercentage: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { borderDash: [5, 5], color: '#f0f0f0', drawBorder: false },
                            ticks: { font: fontStyle, stepSize: 2 }
                        },
                        x: {
                            grid: { display: false, drawBorder: false },
                            ticks: { font: fontStyle }
                        }
                    }
                }
            });
        });
    </script>
</div>