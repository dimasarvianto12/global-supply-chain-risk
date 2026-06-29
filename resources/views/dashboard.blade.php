<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Global Supply Chain Risk Intelligence</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        body { background-color: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .card { border: none; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); margin-bottom: 20px; }
        #map { height: 420px; border-radius: 12px; z-index: 1; }
        .risk-badge { font-size: 1.6rem; font-weight: bold; padding: 12px 24px; border-radius: 8px; display: inline-block; }
    </style>
</head>
<body>

    <nav class="navbar navbar-dark bg-dark shadow-sm">
        <div class="container">
            <span class="navbar-brand mb-0 h1">🌐 Supply Chain Risk Intelligence Platform</span>
        </div>
    </nav>

    <div class="container my-4">
        <div class="card p-3 mb-4">
            <div class="row align-items-center">
                <div class="col-md-5">
                    <label for="countrySelect" class="form-label fw-bold text-secondary">📍 Pilih Wilayah Pemantauan:</label>
                    <select id="countrySelect" class="form-select form-select-lg">
                        </select>
                </div>
                <div class="col-md-7 text-md-end text-start mt-3 mt-md-0">
                    <span class="text-muted me-2">Status Sistem:</span>
                    <span class="badge bg-success px-3 py-2">LIVE MONITORING ACTIVE</span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card p-3">
                    <h5 class="card-title fw-bold text-dark mb-3">⚓ Peta Geospasial Pelabuhan Global</h5>
                    <div id="map"></div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card p-3 text-center h-100 d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="card-title fw-bold text-dark mb-3">📊 Total Risk Score</h5>
                        <div id="riskLevelContainer" class="my-4">
                            <span id="riskScoreLabel" class="risk-badge bg-secondary text-white">0%</span>
                            <h3 id="riskTextLabel" class="mt-2 text-muted fw-bold">-</h3>
                        </div>
                    </div>
                    <div class="text-start bg-light p-3 rounded border">
                        <h6 class="fw-bold border-bottom pb-2">🔍 Breakdown Komponen (Weighted):</h6>
                        <p class="mb-2 d-flex justify-content-between"><span>🌤️ Risiko Cuaca (30%)</span> <strong id="bWeather" class="text-primary">0</strong></p>
                        <p class="mb-2 d-flex justify-content-between"><span>📈 Risiko Inflasi (20%)</span> <strong id="bInflation" class="text-primary">0</strong></p>
                        <p class="mb-2 d-flex justify-content-between"><span>📰 Sentimen Berita (40%)</span> <strong id="bNews" class="text-primary">0</strong></p>
                        <p class="mb-0 d-flex justify-content-between"><span>💵 Volatilitas Valas (10%)</span> <strong id="bCurrency" class="text-primary">0</strong></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card p-3">
                    <h5 class="card-title fw-bold text-dark mb-3">📈 Tren Kurs Global vs USD</h5>
                    <canvas id="currencyChart" height="220"></canvas>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card p-3">
                    <h5 class="card-title fw-bold text-dark mb-3">📰 Live Feed Intelijen Berita</h5>
                    <div id="newsContainer">
                        <div class="text-center py-4 text-muted">Memuat feed berita...</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // 1. Inisialisasi Peta Dunia (Leaflet.js)
        const map = L.map('map').setView([-2.5489, 118.0149], 4); // Koordinat tengah Indonesia
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);
        let markerGroup = L.layerGroup().addTo(map);

        // 2. Inisialisasi Grafik Batang (Chart.js)
        const ctx = document.getElementById('currencyChart').getContext('2d');
        const currencyChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['IDR', 'EUR', 'CNY', 'AUD'],
                datasets: [{
                    label: 'Nilai Tukar Mata Uang Per 1 USD',
                    data: [0, 0, 0, 0],
                    backgroundColor: ['#0d6efd', '#198754', '#ffc107', '#dc3545'],
                    borderRadius: 6
                }]
            },
            options: { responsive: true, scales: { y: { beginAtZero: false } } }
        });

        // 3. Ambil Daftar Negara Saat Pertama Kali Halaman Dibuka (AJAX)
        fetch('/api/countries')
            .then(res => res.json())
            .then(response => {
                const select = document.getElementById('countrySelect');
                response.data.forEach(country => {
                    let opt = document.createElement('option');
                    opt.value = country.id;
                    opt.setAttribute('data-code', country.code);
                    opt.innerText = `${country.name} (${country.code})`;
                    select.appendChild(opt);
                });
                
                // Pemicu muat data pertama kali berdasarkan negara teratas
                if (response.data.length > 0) {
                    loadDashboardData(response.data[0].id, response.data[0].code);
                }
            });

        // Deteksi perubahan pada Dropdown Pilihan Negara
        document.getElementById('countrySelect').addEventListener('change', function() {
            const selectedOpt = this.options[this.selectedIndex];
            loadDashboardData(this.value, selectedOpt.getAttribute('data-code'));
        });

        // 4. Fungsi Utama Penarik Data Gabungan API Backend
        function loadDashboardData(countryId, countryCode) {
            // A. Ambil Data Skor Risiko Terbobot
            fetch(`/api/risk?code=${countryCode}`)
                .then(res => res.json())
                .then(res => {
                    document.getElementById('riskScoreLabel').innerText = res.total_risk_score + '%';
                    document.getElementById('riskTextLabel').innerText = res.risk_level;
                    
                    // Ganti warna indikator sesuai tingkat keparahan risiko
                    const badge = document.getElementById('riskScoreLabel');
                    if(res.risk_level === 'High Risk') badge.className = "risk-badge text-white bg-danger";
                    else if(res.risk_level === 'Medium Risk') badge.className = "risk-badge text-dark bg-warning";
                    else badge.className = "risk-badge text-white bg-success";

                    // Suntik nilai breakdown komponen ke UI
                    document.getElementById('bWeather').innerText = res.breakdown.weather_risk;
                    document.getElementById('bInflation').innerText = res.breakdown.inflation_risk;
                    document.getElementById('bNews').innerText = res.breakdown.news_risk;
                    document.getElementById('bCurrency').innerText = res.breakdown.currency_risk;
                });

            // B. Ambil Data Titik Koordinat Pelabuhan & Gambar di Peta
            fetch(`/api/ports?country_id=${countryId}`)
                .then(res => res.json())
                .then(res => {
                    markerGroup.clearLayers(); // Bersihkan pin lama di peta
                    if(res.data.length > 0) {
                        res.data.forEach(port => {
                            // Validasi koordinat tidak null
                            if(port.latitude && port.longitude) {
                                const marker = L.marker([port.latitude, port.longitude])
                                    .bindPopup(`<b>⚓ Pelabuhan: ${port.name}</b><br>Negara: ${port.country.name}`);
                                markerGroup.addLayer(marker);
                            }
                        });
                        // Arahkan kamera peta secara otomatis ke koordinat pelabuhan pertama
                        map.panTo(new L.LatLng(res.data[0].latitude, res.data[0].longitude));
                    }
                });
        }

        // C. Muat Live Feed Berita Intelijen Global
        fetch('/api/news')
            .then(res => res.json())
            .then(res => {
                const container = document.getElementById('newsContainer');
                container.innerHTML = `
                    <div class="alert alert-light border-start border-4 border-danger shadow-sm mb-2">
                        <h6 class="fw-bold mb-1">${res.data.title}</h6>
                        <p class="small text-muted mb-0">📌 Sumber Data: External Intelligence Feed</p>
                    </div>
                `;
            });

        // D. Ambil Data Nilai Kurs Dunia & Update Chart Batang
        fetch('/api/currency')
            .then(res => res.json())
            .then(res => {
                currencyChart.data.datasets[0].data = [
                    res.rates.IDR || 16450, 
                    res.rates.EUR || 0.93, 
                    res.rates.CNY || 7.26, 
                    res.rates.AUD || 1.50
                ];
                currencyChart.update(); // Segarkan tampilan grafik
            });
    </script>
</body>
</html>