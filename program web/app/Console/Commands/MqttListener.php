<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;
use App\Models\SensorLog;
use App\Models\ActuatorLog;

class MqttListener extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // Signature perintah
    protected $signature = 'mqtt:listen';

    /**
     * The console command description.
     *
     * @var string
     */
    // Deskripsi perintah
    protected $description = 'Berjalan di background untuk subscribe MQTT dan simpan ke MySQL';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // --- KONFIGURASI MQTT ---
        $server   = '10.89.124.94';
        $port     = 1883;
        $clientId = 'Laravel-Listener-' . uniqid();
        $username = 'AdminMQTT';
        $password = 'pwd123';

        $this->info("Menghubungkan ke Broker MQTT di {$server}...");

        try {
            $mqtt = new MqttClient($server, $port, $clientId);

            // Setting koneksi (Username & Password)
            $connectionSettings = (new ConnectionSettings)
                ->setUsername($username)
                ->setPassword($password)
                ->setKeepAliveInterval(60);

            $mqtt->connect($connectionSettings);
            $this->info("Berhasil terhubung! Menunggu data...");

            // --- 1. SUBSCRIBE DATA SENSOR ---
            $mqtt->subscribe('sistem_monitoring_control_automation/sensor', function ($topic, $message) {
                
                // Tampilkan di terminal
                $this->line("[SENSOR] Data diterima: " . $message);

                // Decode JSON dari ESP32
                $data = json_decode($message, true);

                // Simpan ke Tabel sensor_logs
                if ($data) {
                    SensorLog::create([
                        'temperature'   => $data['suhu'] ?? 0,
                        'humidity'      => $data['kelembaban'] ?? 0,
                        'pressure'      => $data['tekanan'] ?? 0,
                        'altitude'      => $data['ketinggian'] ?? 0,
                        'door_distance' => $data['jarak_pintu'] ?? 0,
                    ]);
                    $this->info("-> Tersimpan ke Database (SensorLog).");
                }
            }, 0);

            // --- 2. SUBSCRIBE STATUS PINTU ---
            $mqtt->subscribe('sistem_monitoring_control_automation/door/status', function ($topic, $message) {
                
                // Decode JSON (Isinya: {"door_state": "OPEN"})
                $data = json_decode($message, true);
                $status = $data['door_state'] ?? 'UNKNOWN';

                $this->line("[PINTU] Status berubah: " . $status);

                // Simpan ke Tabel actuator_logs
                ActuatorLog::create([
                    'device_name'    => 'Pintu Servo',
                    'status'         => $status,
                    'trigger_source' => 'Sistem/Otomatis',
                ]);
                $this->info("-> Tersimpan ke Database (ActuatorLog).");
            }, 0);

            // --- 3. SUBSCRIBE STATUS KIPAS ---
            $mqtt->subscribe('sistem_monitoring_control_automation/fan/status', function ($topic, $message) {
                
                // Decode JSON (Isinya: {"fan_state": "ON"})
                $data = json_decode($message, true);
                $status = $data['fan_state'] ?? 'UNKNOWN';

                $this->line("[KIPAS] Status berubah: " . $status);

                // Simpan ke Tabel actuator_logs
                ActuatorLog::create([
                    'device_name'    => 'Kipas Angin',
                    'status'         => $status,
                    'trigger_source' => 'Sistem/Otomatis',
                ]);
                $this->info("-> Tersimpan ke Database (ActuatorLog).");
            }, 0);

            // --- 4. SUBSCRIBE REQUEST CONFIG (Permintaan dari ESP32) ---
            $mqtt->subscribe('sistem_monitoring_control_automation/request_config', function ($topic, $message) use ($mqtt) {
                
                $this->line("[SYSTEM] ESP32 Meminta Konfigurasi...");

                // 1. Ambil data dari Database MySQL
                $config = \App\Models\Configuration::first();
                
                if ($config) {
                    $dataKirim = [
                        'batas_suhu' => (float) $config->fan_temp_threshold,
                        'batas_jarak' => (float) $config->door_dist_threshold
                    ];

                    // 2. Kirim Balik ke ESP32
                    $mqtt->publish(
                        'sistem_monitoring_control_automation/config',
                        json_encode($dataKirim),
                        0,
                        true 
                    );
                    
                    $this->line("-> Konfigurasi dikirim ke Alat: Suhu " . $config->fan_temp_threshold);
                }
            }, 0);

            // Loop agar script tidak mati
            $mqtt->loop(true);

        } catch (\Exception $e) {
            $this->error("Terjadi Error: " . $e->getMessage());
        }
    }
}
