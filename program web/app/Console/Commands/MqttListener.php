<?php

namespace App\Console\Commands;

use Exception;
use App\Models\SensorLog;
use App\Models\ActuatorLog;
use App\Models\Configuration;
use PhpMqtt\Client\MqttClient;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use PhpMqtt\Client\ConnectionSettings;

class MqttListener extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'mqtt:listen';

    /**
     * The console command description.
     */
    protected $description = 'Berjalan di background untuk subscribe MQTT dan simpan ke MySQL';

    // --- DAFTAR TOPIK MQTT ---
    const TOPIC_SENSOR         = 'sistem_monitoring_control_automation/sensor';
    const TOPIC_DOOR_STATUS    = 'sistem_monitoring_control_automation/door/status';
    const TOPIC_FAN_STATUS     = 'sistem_monitoring_control_automation/fan/status';
    const TOPIC_REQUEST_CONFIG = 'sistem_monitoring_control_automation/request_config';
    const TOPIC_SEND_CONFIG    = 'sistem_monitoring_control_automation/config';

    public function handle()
    {
        // 1. Ambil Konfigurasi dari .env
        $server   = env('MQTT_HOST', '127.0.0.1');
        $port     = env('MQTT_PORT', 1883);
        $username = env('MQTT_USERNAME');
        $password = env('MQTT_PASSWORD');
        $clientId = 'Laravel-Listener-' . uniqid();

        $this->info("Menghubungkan ke Broker MQTT di {$server}...");

        try {
            $mqtt = new MqttClient($server, $port, $clientId);

            // 2. Setting Koneksi
            $connectionSettings = (new ConnectionSettings)
                ->setUsername($username)
                ->setPassword($password)
                ->setKeepAliveInterval(60)
                ->setLastWillTopic('laravel/status')
                ->setLastWillMessage('Offline')
                ->setLastWillQualityOfService(1);

            $mqtt->connect($connectionSettings);
            $this->info("✅ Berhasil terhubung! Menunggu data...");

            // --- SUBSCRIBE TOPIK ---

            // A. Sensor Data
            $mqtt->subscribe(self::TOPIC_SENSOR, function ($topic, $message) {
                $this->processSensorData($message);
            }, 0);

            // B. Status Pintu
            $mqtt->subscribe(self::TOPIC_DOOR_STATUS, function ($topic, $message) {
                $this->processActuatorStatus($message, 'Pintu Servo', 'door_state');
            }, 0);

            // C. Status Kipas
            $mqtt->subscribe(self::TOPIC_FAN_STATUS, function ($topic, $message) {
                $this->processActuatorStatus($message, 'Kipas Angin', 'fan_state');
            }, 0);

            // D. Request Config
            $mqtt->subscribe(self::TOPIC_REQUEST_CONFIG, function ($topic, $message) use ($mqtt) {
                $this->handleConfigRequest($mqtt);
            }, 0);

            // Loop forever
            $mqtt->loop(true);

        } catch (Exception $e) {
            $this->error("❌ Terjadi Error: " . $e->getMessage());
            // Opsional: Log error ke file log Laravel
            Log::error("MQTT Error: " . $e->getMessage());
        }
    }

    /**
     * Proses data sensor dan simpan ke database
     */
    private function processSensorData($message)
    {
        $this->line("[SENSOR] Data diterima: " . $message);
        $data = json_decode($message, true);

        if (!$data) {
            $this->warn("-> Data JSON invalid.");
            return;
        }

        try {
            SensorLog::create([
                'temperature'   => $data['suhu'] ?? 0,
                'humidity'      => $data['kelembaban'] ?? 0,
                'pressure'      => $data['tekanan'] ?? 0,
                'altitude'      => $data['ketinggian'] ?? 0,
                'door_distance' => $data['jarak_pintu'] ?? 0,
            ]);
            $this->info("-> Tersimpan (SensorLog).");
        } catch (Exception $e) {
            $this->error("-> Gagal simpan DB: " . $e->getMessage());
        }
    }

    /**
     * Proses status aktuator (Pintu/Kipas) agar lebih dinamis
     */
    private function processActuatorStatus($message, $deviceName, $jsonKey)
    {
        $data = json_decode($message, true);
        $status = $data[$jsonKey] ?? 'UNKNOWN';

        $this->line("[{$deviceName}] Status: " . $status);

        try {
            ActuatorLog::create([
                'device_name'    => $deviceName,
                'status'         => $status,
                'trigger_source' => 'Sistem/Otomatis',
            ]);
            $this->info("-> Tersimpan (ActuatorLog).");
        } catch (Exception $e) {
            $this->error("-> Gagal simpan DB: " . $e->getMessage());
        }
    }

    /**
     * Handle permintaan konfigurasi dari ESP32
     */
    private function handleConfigRequest($mqtt)
    {
        $this->line("[SYSTEM] ESP32 Meminta Konfigurasi...");

        $config = Configuration::first();

        if ($config) {
            $payload = json_encode([
                'batas_suhu'  => (float) $config->fan_temp_threshold,
                'batas_jarak' => (float) $config->door_dist_threshold
            ]);

            $mqtt->publish(self::TOPIC_SEND_CONFIG, $payload, 0, true);
            $this->line("-> Config dikirim: " . $payload);
        } else {
            $this->warn("-> Config belum diset di Database.");
        }
    }
}