<?php

namespace App\Http\Controllers;

use App\Models\ActuatorLog;
use Illuminate\Http\Request;
use App\Models\Configuration;

class DashboardController extends Controller
{
    public function index(){
        // 1. Ambil 10 Riwayat Aktivitas Terakhir (Log Pintu/Kipas)
        // Urutkan dari yang terbaru (latest)
        $logs = ActuatorLog::latest()->take(10)->get();

        // 2. Ambil Konfigurasi Terakhir (Untuk mengisi form input otomatis)
        $config = Configuration::first();

        // 3. Kirim data ke View 'dashboard'
        return view('tampilan', compact('logs', 'config'));
    }

    // Di dalam DashboardController.php

    public function updateSchedule(Request $request)
    {
        $request->validate([
            'open_time' => 'required',
            'close_time' => 'required',
        ]);

        // Simpan ke DB
        $config = Configuration::first();
        if (!$config) $config = new Configuration();
        $config->schedule_open = $request->open_time;
        $config->schedule_close = $request->close_time;
        $config->save();

        try {
            $mqttData = [
                'open' => $request->open_time,
                'close' => $request->close_time
            ];
            
            // Ganti 127.0.0.1 menjadi IP Laptop yang terdaftar di Mosquitto
            $server   = '10.89.124.94'; 
            $port     = 1883;
            
            // Gunakan Client ID Unik
            $clientId = 'Laravel-Schedule-Sender-' . uniqid();

            $mqtt = new \PhpMqtt\Client\MqttClient($server, $port, $clientId);
            
            // Masukkan Username & Password (Wajib karena allow_anonymous false)
            $connectionSettings = (new \PhpMqtt\Client\ConnectionSettings)
                ->setUsername('AdminMQTT')
                ->setPassword('pwd123');

            $mqtt->connect($connectionSettings, true);
            
            $mqtt->publish(
                'sistem_monitoring_control_automation/pintu/jadwal', 
                json_encode($mqttData), 
                0, 
                true
            );
            $mqtt->disconnect();
            
            return redirect()->back()->with('success', 'Jadwal berhasil diperbarui!');
        } catch (\Exception $e) {
            // Tampilkan error jika gagal
            return redirect()->back()->with('error', 'Error MQTT: ' . $e->getMessage());
        }
    }

    // LAKUKAN HAL YANG SAMA UNTUK FUNGSI updateConfig JUGA!
    public function updateConfig(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'fan_temp_threshold' => 'required|numeric',
            'door_dist_threshold' => 'required|numeric',
        ]);

        // 2. Simpan ke Database (MySQL)
        $config = Configuration::first(); 
        if (!$config) {
            $config = new Configuration();
        }
        
        $config->fan_temp_threshold = $request->fan_temp_threshold;
        $config->door_dist_threshold = $request->door_dist_threshold;
        $config->save();

        // 3. Definisikan Data yang mau dikirim
        $mqttData = [
            'batas_suhu' => (float) $request->fan_temp_threshold,
            'batas_jarak' => (float) $request->door_dist_threshold
        ];

        // 4. Kirim ke ESP32 via MQTT
        try {
            $server   = '10.89.124.94';
            $port     = 1883;
            $clientId = 'Laravel-Config-Sender-' . uniqid();

            $mqtt = new \PhpMqtt\Client\MqttClient($server, $port, $clientId);
            
            $connectionSettings = (new \PhpMqtt\Client\ConnectionSettings)
                ->setUsername('AdminMQTT')
                ->setPassword('pwd123');

            $mqtt->connect($connectionSettings, true);
            
            // Publish ke topik config
            $mqtt->publish(
                'sistem_monitoring_control_automation/config', 
                json_encode($mqttData), // <-- Variabel ini sekarang sudah ada isinya
                0, 
                true // Retain message
            );
            $mqtt->disconnect();
            
            return redirect()->back()->with('success', 'Konfigurasi berhasil disimpan & dikirim ke alat!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal terhubung ke MQTT: ' . $e->getMessage());
        }
    }
}
