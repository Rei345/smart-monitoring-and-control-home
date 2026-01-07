<?php

namespace App\Http\Controllers;

use App\Models\ActuatorLog;
use Illuminate\Http\Request;
use App\Models\Configuration;
use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard utama.
     * Memuat log aktivitas terakhir dan konfigurasi sistem saat ini.
     */
    public function index()
    {
        $logs = ActuatorLog::latest()->take(10)->get();
        $config = Configuration::first();

        return view('tampilan', compact('logs', 'config'));
    }

    /**
     * Memperbarui jadwal operasional pintu dan sinkronisasi ke MQTT.
     */
    public function updateSchedule(Request $request)
    {
        $request->validate([
            'open_time' => 'required',
            'close_time' => 'required',
        ]);

        // Simpan konfigurasi jadwal ke database
        $config = Configuration::first();
        if (!$config) {
            $config = new Configuration();
        }
        $config->schedule_open = $request->open_time;
        $config->schedule_close = $request->close_time;
        $config->save();

        try {
            $mqttData = [
                'open' => $request->open_time,
                'close' => $request->close_time
            ];
            
            // Mengambil kredensial dari file .env
            $server   = env('MQTT_HOST', '127.0.0.1');
            $port     = env('MQTT_PORT', 1883);
            $clientId = 'Laravel-Schedule-Sender-' . uniqid();

            $mqtt = new MqttClient($server, $port, $clientId);
            
            $connectionSettings = (new ConnectionSettings)
                ->setUsername(env('MQTT_USERNAME'))
                ->setPassword(env('MQTT_PASSWORD'));

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
            return redirect()->back()->with('error', 'Error MQTT: ' . $e->getMessage());
        }
    }

    /**
     * Memperbarui parameter batas suhu dan jarak, serta sinkronisasi ke MQTT.
     */
    public function updateConfig(Request $request)
    {
        $request->validate([
            'fan_temp_threshold' => 'required|numeric',
            'door_dist_threshold' => 'required|numeric',
        ]);

        // Simpan konfigurasi threshold ke database
        $config = Configuration::first(); 
        if (!$config) {
            $config = new Configuration();
        }
        
        $config->fan_temp_threshold = $request->fan_temp_threshold;
        $config->door_dist_threshold = $request->door_dist_threshold;
        $config->save();

        // Data payload untuk dikirim ke perangkat IoT
        $mqttData = [
            'batas_suhu' => (float) $request->fan_temp_threshold,
            'batas_jarak' => (float) $request->door_dist_threshold
        ];

        try {
            // Mengambil kredensial dari file .env
            $server   = env('MQTT_HOST', '127.0.0.1');
            $port     = env('MQTT_PORT', 1883);
            $clientId = 'Laravel-Config-Sender-' . uniqid();

            $mqtt = new MqttClient($server, $port, $clientId);
            
            $connectionSettings = (new ConnectionSettings)
                ->setUsername(env('MQTT_USERNAME'))
                ->setPassword(env('MQTT_PASSWORD'));

            $mqtt->connect($connectionSettings, true);
            
            $mqtt->publish(
                'sistem_monitoring_control_automation/config', 
                json_encode($mqttData), 
                0, 
                true
            );
            $mqtt->disconnect();
            
            return redirect()->back()->with('success', 'Konfigurasi berhasil disimpan & dikirim ke alat!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal terhubung ke MQTT: ' . $e->getMessage());
        }
    }
}
