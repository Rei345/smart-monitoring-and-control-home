<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Monitoring</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #212529; }
        .card { background-color: #2c3034; border: 1px solid rgba(255,255,255,0.1); box-shadow: 0 4px 6px rgba(0,0,0,0.2); }
        .chart-container { position: relative; height: 200px; width: 100%; }
        .table-responsive { max-height: 350px; overflow-y: auto; }
        .icon-circle { width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; }
        .section-title { font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; color: #adb5bd; margin-bottom: 1rem; font-weight: 600; }
        .badge-mode { transition: all 0.3s ease; }
    </style>
</head>

<body>

    <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div id="liveToast" class="toast align-items-center text-bg-primary border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body" id="toastMessage">
                    Sistem Updated.
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg sticky-top bg-dark shadow-sm border-bottom border-secondary">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ url('/') }}">
                <i class="fas fa-network-wired me-2 text-primary"></i> IoT Smart Home
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="{{ url('/') }}">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/tentang') }}">Tentang</a></li>
                    <li class="nav-item"><a class="nav-link active fw-bold text-white" href="{{ route('dashboard') }}">Tampilan</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/metode') }}">Metode</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/kontak') }}">Kontak</a></li>
                    @auth
                        <li class="nav-item ms-lg-3 mt-2 mt-lg-0 dropdown">
                            <a class="btn btn-outline-primary btn-sm rounded-pill px-4 dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-2"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end bg-dark border-secondary">
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">@csrf
                                        <button type="submit" class="dropdown-item text-danger small"><i class="fas fa-sign-out-alt me-2"></i> Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item ms-lg-3 mt-2 mt-lg-0"><a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm rounded-pill px-4">Login</a></li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom border-secondary pb-3">
            <h3 class="fw-bold m-0"><i class="fas fa-tachometer-alt me-2 text-warning"></i>Dashboard Monitoring</h3>
            <span class="badge bg-success px-3 py-2 rounded-pill"><i class="fas fa-wifi me-1"></i> System Online</span>
        </div>

        <div class="row g-4">

            <div class="col-lg-8">
                <div class="section-title">Data Sensor Realtime</div>

                <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 g-3 mb-3">
                    <div class="col"><div class="card h-100 border-bottom border-3 border-danger"><div class="card-body p-3 d-flex align-items-center"><div class="icon-circle bg-danger bg-opacity-25 text-danger me-3"><i class="fas fa-temperature-high"></i></div><div><small class="text-secondary fw-bold">SUHU</small><h5 class="mb-0 fs-6 fw-bold" id="hitTEMP">-- °C</h5></div></div></div></div>
                    <div class="col"><div class="card h-100 border-bottom border-3 border-info"><div class="card-body p-3 d-flex align-items-center"><div class="icon-circle bg-info bg-opacity-25 text-info me-3"><i class="fas fa-tint"></i></div><div><small class="text-secondary fw-bold">KELEMBAPAN</small><h5 class="mb-0 fs-6 fw-bold" id="hitHUM">-- %</h5></div></div></div></div>
                    <div class="col"><div class="card h-100 border-bottom border-3 border-primary"><div class="card-body p-3 d-flex align-items-center"><div class="icon-circle bg-primary bg-opacity-25 text-primary me-3"><i class="fas fa-gauge"></i></div><div><small class="text-secondary fw-bold">TEKANAN</small><h5 class="mb-0 fs-6 fw-bold" id="pressure">-- hPa</h5></div></div></div></div>
                    <div class="col"><div class="card h-100 border-bottom border-3 border-warning"><div class="card-body p-3 d-flex align-items-center"><div class="icon-circle bg-warning bg-opacity-25 text-warning me-3"><i class="fas fa-mountain"></i></div><div><small class="text-secondary fw-bold">KETINGGIAN</small><h5 class="mb-0 fs-6 fw-bold" id="height">-- m</h5></div></div></div></div>
                </div>

                <div class="row row-cols-1 row-cols-md-3 g-3 mb-4">
                    <div class="col"><div class="card h-100 border-bottom border-3 border-success"><div class="card-body p-3 d-flex align-items-center"><div class="icon-circle bg-success bg-opacity-25 text-success me-3"><i class="fas fa-ruler"></i></div><div><small class="text-secondary fw-bold">JARAK OBJEK</small><h5 class="mb-0 fs-6 fw-bold" id="doorDistance">-- cm</h5></div></div></div></div>
                    <div class="col"><div class="card h-100 border-bottom border-3 border-light"><div class="card-body p-3 d-flex align-items-center"><div class="icon-circle bg-secondary bg-opacity-25 text-light me-3"><i class="fas fa-door-closed"></i></div><div><small class="text-secondary fw-bold">STATUS PINTU</small><h5 class="mb-0 fs-5 fw-bold" id="doorStatus">CLOSED</h5></div></div></div></div>
                    <div class="col"><div class="card h-100 bg-primary bg-gradient text-white shadow"><div class="card-body p-3 d-flex align-items-center"><div class="icon-circle bg-white text-primary me-3"><i class="fas fa-fan fa-spin"></i></div><div><small class="text-white-50 fw-bold">STATUS KIPAS</small><h5 class="mb-0 fs-5 fw-bold" id="fanStatus">OFF</h5></div></div></div></div>
                </div>

                <div class="section-title mt-4">Grafik Analitik</div>
                <div class="row g-3 mb-4">
                    <div class="col-md-6"><div class="card"><div class="card-header bg-transparent py-2 border-secondary"><small class="fw-bold text-danger"><i class="fas fa-chart-line me-2"></i>Grafik Suhu</small></div><div class="card-body"><div class="chart-container"><canvas id="chartTEMP"></canvas></div></div></div></div>
                    <div class="col-md-6"><div class="card"><div class="card-header bg-transparent py-2 border-secondary"><small class="fw-bold text-info"><i class="fas fa-chart-bar me-2"></i>Grafik Kelembapan</small></div><div class="card-body"><div class="chart-container"><canvas id="chartHUM"></canvas></div></div></div></div>
                    <div class="col-md-6"><div class="card"><div class="card-header bg-transparent py-2 border-secondary"><small class="fw-bold text-primary"><i class="fas fa-chart-area me-2"></i>Grafik Tekanan</small></div><div class="card-body"><div class="chart-container"><canvas id="chartPRESSURE"></canvas></div></div></div></div>
                    <div class="col-md-6"><div class="card"><div class="card-header bg-transparent py-2 border-secondary"><small class="fw-bold text-warning"><i class="fas fa-chart-pie me-2"></i>Grafik Ketinggian</small></div><div class="card-body"><div class="chart-container"><canvas id="chartHEIGHT"></canvas></div></div></div></div>
                </div>

                <div class="card">
                    <div class="card-header bg-transparent border-secondary d-flex justify-content-between align-items-center"><h6 class="mb-0 fw-bold"><i class="fas fa-history me-2"></i>Log Aktivitas</h6><button onclick="window.location.reload()" class="btn btn-sm btn-secondary py-0" style="font-size: 0.8rem;">Refresh Data</button></div>
                    <div class="card-body p-0"><div class="table-responsive"><table class="table table-dark table-striped table-hover mb-0 align-middle small"><thead><tr><th class="ps-3">Waktu</th><th>Perangkat</th><th>Status</th><th>Keterangan</th></tr></thead><tbody id="logTableBody">
                        @forelse($logs as $log)
                            <tr><td class="ps-3 text-secondary">{{ $log->created_at->format('H:i:s') }} <small class="d-block text-muted" style="font-size: 0.7rem;">{{ $log->created_at->format('d M Y') }}</small></td><td>{{ $log->device_name }}</td><td>@if(in_array($log->status, ['ON', 'OPEN', 'BUKA'])) <span class="badge bg-success-subtle text-success border border-success">{{ $log->status }}</span> @else <span class="badge bg-danger-subtle text-danger border border-danger">{{ $log->status }}</span> @endif</td><td>{{ $log->trigger_source ?? 'Sistem' }}</td></tr>
                        @empty
                            <tr id="noDataRow"><td colspan="4" class="text-center text-muted py-4">Belum ada aktivitas terbaru.</td></tr>
                        @endforelse
                    </tbody></table></div></div>
                </div>
            </div>

            <div class="col-lg-4">
                @auth
                <div class="section-title ps-1">Panel Kontrol</div>

                <div class="card mb-4">
                    <div class="card-header bg-transparent border-secondary d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fas fa-gamepad me-2"></i>Kontrol Manual</span>
                        <span id="doorModeBadge" class="badge badge-mode bg-success bg-opacity-25 text-success border border-success" style="font-size: 0.7rem;">Mode: OTOMATIS</span>
                    </div>
                    <div class="card-body">
                        <label class="small text-secondary mb-2">Pintu Servo</label>
                        <div class="d-grid gap-2 d-flex mb-2">
                            <button class="btn btn-success flex-fill" onclick="controlServo('BUKA'); updateMode('MANUAL')"><i class="fas fa-lock-open me-1"></i> Buka</button>
                            <button class="btn btn-danger flex-fill" onclick="controlServo('TUTUP'); updateMode('MANUAL')"><i class="fas fa-lock me-1"></i> Tutup</button>
                        </div>
                        <button class="btn btn-outline-warning btn-sm w-100 mb-4" onclick="controlServo('AUTO'); updateMode('AUTO')"><i class="fas fa-robot me-1"></i> Kembali ke Mode Otomatis</button>

                        <hr class="border-secondary opacity-25">
                        <label class="small text-secondary mb-2">Kipas Utama</label>
                        <div class="d-grid gap-2 d-flex mb-3">
                            <button class="btn btn-primary flex-fill" onclick="controlFan('ON')"><i class="fas fa-power-off me-1"></i> ON</button>
                            <button class="btn btn-outline-secondary flex-fill" onclick="controlFan('OFF')">OFF</button>
                        </div>
                        <label class="small text-secondary">Kecepatan Manual</label>
                        <input type="range" class="form-range" id="fanSpeedRange" min="0" max="5" value="0">
                        <div class="d-flex justify-content-between small text-muted" style="font-size: 0.75rem;"><span>0</span><span>1</span><span>2</span><span>3</span><span>4</span><span>5</span></div>
                    </div>
                </div>

                <div class="card mb-4 border-warning border-opacity-25">
                    <div class="card-header bg-warning bg-opacity-10 border-warning border-opacity-25"><span class="fw-bold text-warning"><i class="fas fa-sliders-h me-2"></i>Konfigurasi Otomatis</span></div>
                    <div class="card-body">
                        <form action="{{ route('config.update') }}" method="POST">@csrf
                            <div class="mb-3"><label class="small text-secondary mb-1">Trigger Kipas (Suhu)</label><div class="input-group input-group-sm"><input type="number" step="0.1" name="fan_temp_threshold" class="form-control bg-dark text-white border-secondary" value="{{ $config->fan_temp_threshold ?? 30 }}"><span class="input-group-text bg-secondary border-secondary text-light">°C</span></div></div>
                            <div class="mb-3"><label class="small text-secondary mb-1">Trigger Pintu (Jarak)</label><div class="input-group input-group-sm"><input type="number" step="0.1" name="door_dist_threshold" class="form-control bg-dark text-white border-secondary" value="{{ $config->door_dist_threshold ?? 15 }}"><span class="input-group-text bg-secondary border-secondary text-light">cm</span></div></div>
                            <button type="submit" class="btn btn-warning btn-sm w-100 fw-bold">Simpan Konfigurasi</button>
                        </form>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">{{ session('success') }} <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>
                @endif

                <div class="card mb-4">
                    <div class="card-header bg-transparent border-secondary"><span class="fw-bold text-info"><i class="fas fa-clock me-2"></i>Jadwal Operasional</span></div>
                    <div class="card-body">
                        <form action="{{ route('schedule.update') }}" method="POST">@csrf
                            <div class="row g-2 mb-3">
                                <div class="col-6"><label class="small text-secondary">Buka</label><input type="time" name="open_time" class="form-control bg-dark text-white border-secondary" value="{{ isset($config) ? \Carbon\Carbon::parse($config->schedule_open)->format('H:i') : '08:00' }}"></div>
                                <div class="col-6"><label class="small text-secondary">Tutup</label><input type="time" name="close_time" class="form-control bg-dark text-white border-secondary" value="{{ isset($config) ? \Carbon\Carbon::parse($config->schedule_close)->format('H:i') : '17:00' }}"></div>
                            </div>
                            <button type="submit" class="btn btn-info btn-sm w-100 text-white fw-bold">Update Jadwal</button>
                        </form>
                    </div>
                </div>

                @else
                    <div class="card border-danger border-opacity-50"><div class="card-body text-center p-5"><i class="fas fa-lock fa-3x text-danger mb-3"></i><h5 class="text-white">Akses Terbatas</h5><p class="text-secondary small">Anda harus login sebagai Admin.</p><a href="{{ route('login') }}" class="btn btn-danger rounded-pill px-4">Login</a></div></div>
                @endauth

                <div class="alert alert-dark border-secondary d-flex align-items-center p-2 small mt-3">
                    <div class="spinner-border spinner-border-sm text-success me-2" role="status"></div>
                    <div class="text-truncate text-secondary" id="messages">Menunggu koneksi MQTT...</div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/min/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-streaming@1.9.0"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/paho-mqtt/1.0.1/mqttws31.min.js" type="text/javascript"></script>

    <script>
        var host = "10.89.124.94"; 
        var port = 9001;
        var client = new Paho.MQTT.Client(host, port, "/ws", "dashboard_" + parseInt(Math.random() * 100, 10));
        var temp=0, humi=0, pressure=0, height=0;
        const BASE_TOPIC = "sistem_monitoring_control_automation";
        const TOPIC_SENSOR = BASE_TOPIC + "/sensor";
        const TOPIC_PINTU_STATUS = BASE_TOPIC + "/door/status";
        const TOPIC_KIPAS_STATUS = BASE_TOPIC + "/fan/status";
        const TOPIC_PINTU_MANUAL = BASE_TOPIC + "/pintu/manual";
        const TOPIC_KIPAS_MANUAL = BASE_TOPIC + "/kipas/manual";
        const TOPIC_PINTU_JADWAL = BASE_TOPIC + "/pintu/jadwal";
        const TOPIC_KIPAS_SPEED  = BASE_TOPIC + "/kipas/speed";

        // Fungsi Toast Notifikasi
        function showToast(message, type = 'info') {
            const toastLiveExample = document.getElementById('liveToast');
            const toastBootstrap = new bootstrap.Toast(toastLiveExample);
            document.getElementById('toastMessage').innerText = message;
            
            if(type === 'warning') toastLiveExample.classList.replace('text-bg-primary', 'text-bg-warning');
            else if(type === 'success') toastLiveExample.classList.replace('text-bg-primary', 'text-bg-success');
            else toastLiveExample.classList.replace('text-bg-warning', 'text-bg-primary');
            
            toastBootstrap.show();
        }

        function addRealtimeLog(device, status, trigger) {
            const tbody = document.getElementById('logTableBody');
            const noDataRow = document.getElementById('noDataRow');
            if(noDataRow) noDataRow.remove();
            const now = new Date();
            const timeStr = now.toLocaleTimeString('id-ID');
            const dateStr = now.toLocaleDateString('id-ID');
            let badgeClass = 'bg-danger-subtle text-danger border border-danger';
            if (['ON', 'OPEN', 'BUKA'].includes(status)) badgeClass = 'bg-success-subtle text-success border border-success';
            const newRow = `<tr><td class="ps-3 text-white">${timeStr} <small class="d-block text-muted" style="font-size: 0.7rem;">${dateStr}</small></td><td>${device}</td><td><span class="badge ${badgeClass}">${status}</span></td><td>${trigger}</td></tr>`;
            tbody.insertAdjacentHTML('afterbegin', newRow);
        }

        client.onConnectionLost = (responseObject) => { document.getElementById("messages").innerHTML = `<span class="text-danger">Putus: ${responseObject.errorMessage}</span>`; };
        client.onMessageArrived = (message) => {
            const topic = message.destinationName;
            const rawMsg = message.payloadString;
            try {
                if (topic === TOPIC_SENSOR) {
                    const payload = JSON.parse(rawMsg);
                    temp = payload.suhu; humi = payload.kelembaban; pressure = payload.tekanan; height = payload.ketinggian;
                    document.getElementById("hitTEMP").innerHTML = payload.suhu.toFixed(2) + " °C";
                    document.getElementById("hitHUM").innerHTML = payload.kelembaban.toFixed(2) + " %";
                    document.getElementById("pressure").innerHTML = payload.tekanan.toFixed(2) + " hPa";
                    document.getElementById("height").innerHTML = payload.ketinggian.toFixed(2) + " m";
                    document.getElementById("doorDistance").innerHTML = payload.jarak_pintu.toFixed(2) + " cm";
                } else if (topic === TOPIC_PINTU_STATUS) {
                    const payload = JSON.parse(rawMsg);
                    let status = payload.door_state;
                    let currentText = document.getElementById("doorStatus").innerText;
                    document.getElementById("doorStatus").innerHTML = status;
                    if (currentText !== status) addRealtimeLog("Pintu Servo", status, "Update Realtime");
                } else if (topic === TOPIC_KIPAS_STATUS) {
                    const payload = JSON.parse(rawMsg);
                    let status = payload.fan_state;
                    let displayText = status;
                    document.getElementById("fanStatus").innerHTML = displayText;
                    addRealtimeLog("Kipas Angin", status, "Update Realtime");
                }
            } catch (e) { console.error("Gagal parse JSON:", e); }
        };

        var options = { timeout: 3, keepAliveInterval: 30, onSuccess: () => { document.getElementById("messages").innerHTML = "<span class='text-success'>Terhubung</span>"; client.subscribe(BASE_TOPIC + "/#", { qos: 1 }); }, onFailure: (message) => { document.getElementById("messages").innerHTML = `<span class='text-danger'>Gagal</span>`; }, userName: "AdminMQTT", password: "pwd123" };
        client.connect(options);

        function controlServo(action) { 
            if (client.isConnected()) {
                let message = new Paho.MQTT.Message(action);
                message.destinationName = TOPIC_PINTU_MANUAL;
                client.send(message);
            }
        }
        function controlFan(action) { 
            if (client.isConnected()) {
                let message = new Paho.MQTT.Message(action);
                message.destinationName = TOPIC_KIPAS_MANUAL;
                client.send(message);
                const slider = document.getElementById('fanSpeedRange');
                if(slider) {
                    if(action === 'OFF') slider.value = 0;
                    else if (action === 'ON' && slider.value == 0) {
                        slider.value = 1;
                        let msgSpeed = new Paho.MQTT.Message("1");
                        msgSpeed.destinationName = TOPIC_KIPAS_SPEED;
                        msgSpeed.retained = true;
                        client.send(msgSpeed);
                    }
                }
            }
        }

        // FUNGSI UPDATE MODE (DENGAN TOAST, BUKAN ALERT)
        function updateMode(mode) {
            const badge = document.getElementById('doorModeBadge');
            if (mode === 'MANUAL') {
                badge.className = 'badge badge-mode bg-warning bg-opacity-25 text-warning border border-warning';
                badge.innerHTML = 'Mode: MANUAL';
                showToast("Mode Manual Aktif. Sensor dimatikan.", "warning");
            } else {
                badge.className = 'badge badge-mode bg-success bg-opacity-25 text-success border border-success';
                badge.innerHTML = 'Mode: OTOMATIS';
                showToast("Kembali ke Mode Otomatis. Sensor aktif.", "success");
            }
        }

        const fanSlider = document.getElementById('fanSpeedRange');
        if(fanSlider){ fanSlider.addEventListener('change', function() { let speedLevel = this.value; if (client.isConnected()) { let message = new Paho.MQTT.Message(speedLevel.toString()); message.destinationName = TOPIC_KIPAS_SPEED; message.retained = true; client.send(message); } }); }

        // Charts
        const chartColors = { red: 'rgb(220, 53, 69)', blue: 'rgb(13, 202, 240)', green: 'rgb(25, 135, 84)', yellow: 'rgb(255, 193, 7)' };
        const realtimeAxis = { type: 'realtime', realtime: { duration: 20000, refresh: 2000, delay: 2000 } };
        const commonOptions = { maintainAspectRatio: false, responsive: true, scales: { xAxes: [{ ...realtimeAxis, gridLines: { color: 'rgba(255,255,255,0.1)' }, ticks: { fontColor: '#adb5bd' } }], yAxes: [{ gridLines: { color: 'rgba(255,255,255,0.1)' }, ticks: { fontColor: '#adb5bd' } }] }, legend: { display: false } };
        const configTEMP = { type: 'line', data: { datasets: [{ label: 'Suhu', backgroundColor: 'rgba(220, 53, 69, 0.2)', borderColor: chartColors.red, data: [] }] }, options: { ...commonOptions, scales: { xAxes: [{ ...realtimeAxis, realtime: { ...realtimeAxis.realtime, onRefresh: onRefreshTemp }, gridLines: { color: 'rgba(255,255,255,0.1)' }, ticks: { fontColor: '#adb5bd' } }], yAxes: [{ ticks: { beginAtZero: true, fontColor: '#adb5bd' }, gridLines: { color: 'rgba(255,255,255,0.1)' } }] } } };
        const configHUM = { type: 'line', data: { datasets: [{ label: 'Kelembapan', backgroundColor: 'rgba(13, 202, 240, 0.2)', borderColor: chartColors.blue, data: [] }] }, options: { ...commonOptions, scales: { xAxes: [{ ...realtimeAxis, realtime: { ...realtimeAxis.realtime, onRefresh: onRefreshHum }, gridLines: { color: 'rgba(255,255,255,0.1)' }, ticks: { fontColor: '#adb5bd' } }], yAxes: [{ ticks: { beginAtZero: true, fontColor: '#adb5bd' }, gridLines: { color: 'rgba(255,255,255,0.1)' } }] } } };
        const configPRESSURE = { type: 'line', data: { datasets: [{ label: 'Tekanan', backgroundColor: 'rgba(13, 110, 253, 0.2)', borderColor: 'rgb(13, 110, 253)', data: [] }] }, options: { ...commonOptions, scales: { xAxes: [{ ...realtimeAxis, realtime: { ...realtimeAxis.realtime, onRefresh: onRefreshPressure }, gridLines: { color: 'rgba(255,255,255,0.1)' }, ticks: { fontColor: '#adb5bd' } }], yAxes: [{ ticks: { beginAtZero: false, fontColor: '#adb5bd' }, gridLines: { color: 'rgba(255,255,255,0.1)' } }] } } };
        const configHEIGHT = { type: 'line', data: { datasets: [{ label: 'Ketinggian', backgroundColor: 'rgba(255, 193, 7, 0.2)', borderColor: chartColors.yellow, data: [] }] }, options: { ...commonOptions, scales: { xAxes: [{ ...realtimeAxis, realtime: { ...realtimeAxis.realtime, onRefresh: onRefreshHeight }, gridLines: { color: 'rgba(255,255,255,0.1)' }, ticks: { fontColor: '#adb5bd' } }], yAxes: [{ ticks: { beginAtZero: false, fontColor: '#adb5bd' }, gridLines: { color: 'rgba(255,255,255,0.1)' } }] } } };
        function onRefreshTemp(chart) { chart.data.datasets[0].data.push({ x: Date.now(), y: temp }); }
        function onRefreshHum(chart) { chart.data.datasets[0].data.push({ x: Date.now(), y: humi }); }
        function onRefreshPressure(chart) { chart.data.datasets[0].data.push({ x: Date.now(), y: pressure }); }
        function onRefreshHeight(chart) { chart.data.datasets[0].data.push({ x: Date.now(), y: height }); }
        window.onload = function () { new Chart(document.getElementById('chartTEMP').getContext('2d'), configTEMP); new Chart(document.getElementById('chartHUM').getContext('2d'), configHUM); new Chart(document.getElementById('chartPRESSURE').getContext('2d'), configPRESSURE); new Chart(document.getElementById('chartHEIGHT').getContext('2d'), configHEIGHT); };
    </script>
</body>
</html>