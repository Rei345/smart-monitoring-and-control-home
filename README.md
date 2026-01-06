# 🏠 Smart Monitoring & Control Home (IoT)

Sistem Smart Home terintegrasi yang menggabungkan efisiensi **Internet of Things (IoT)** dengan kemudahan pengelolaan via **Web Dashboard**. Proyek ini memungkinkan pemantauan sensor lingkungan secara real-time dan pengendalian perangkat elektronik (pintu & kipas) melalui protokol MQTT dan framework Laravel.


## 📸 Tampilan Sistem

| Dashboard Sebelum Login | Dashboard Utama (Setelah Login) |
| :---: | :---: |
| ![Login](images/Dashboard_Sebelum_Login.png) | ![Dashboard1](images/Dashboard_Setelah_Login1.png) |

| Dashboard Utama (Setelah Login) | Rangkaian Hardware |
| :---: | :---: |
| ![Dashboard2](images/Dashboard_Setelah_Login2.png) | ![Rangkaian](images/Rangkaian.png) |


## 🌟 Fitur Utama
- **Hybrid Control Mode:** Perpindahan fleksibel antara mode **Otomatis** (berdasarkan sensor) dan mode **Manual** (via Web Dashboard).
- **Real-time Monitoring:** Visualisasi data sensor (Suhu, Kelembaban, Tekanan Udara, Ketinggian, dan Jarak) yang diperbarui secara instan.
- **Time-Based Automation:** Sinkronisasi waktu dengan **NTP Server** untuk pengaturan jadwal buka/tutup pintu otomatis yang presisi.
- **Intelligent Actuator:** - Kontrol presisi **Servo Motor** untuk akses pintu.
  - Kontrol kecepatan dinamis pada **Stepper Motor** untuk sistem kipas angin.
- **Data Logging:** Penyimpanan riwayat aktivitas sensor ke database MySQL untuk keperluan analisis data.

## 🛠️ Tech Stack
### Hardware
- **Microcontroller:** ESP32
- **Sensors:** DHT11 (Humidity), BMP280 (Temperature & Pressure), Ultrasonic HC-SR04 (Distance).
- **Actuators:** MG995 Servo Motor, 28BYJ-48 Stepper Motor + ULN2003 Driver.
- **Protocol:** MQTT (Mosquitto Broker) & HTTP.

### Software (Web & Backend)
- **Framework:** Laravel 12
- **Database:** MySQL
- **Real-time Communication:** MQTT Client for Laravel
- **Frontend:** Bootstrap & JavaScript (AJAX)
- **Firmware:** C++ (Arduino IDE)

## 📁 Struktur Repositori
- `program web/` : Source code aplikasi Dashboard Laravel.
- `monitoring.cpp` : Firmware ESP32 (Arduino Sketch).

## 🚀 Panduan Instalasi

### 1. Persiapan Hardware
1. Rakit komponen sesuai dengan pin yang terdefinisi di file `monitoring.cpp`.
2. Pastikan **MQTT Broker (Mosquitto)** sudah terinstal dan konfigurasi IP serta User sudah dilakukan dan berjalan degan baik di server/PC lokal Anda.

### 2. Setup Backend (Laravel)
```bash
# 1. Masuk ke direktori web
cd "program web"

# 2. Install library PHP pendukung
composer install

# 3. Konfigurasi Environment
cp .env.example .env

# 4. Generate Application Key
php artisan key:generate

# 5. Konfigurasi Database di .env
# Buka file .env dan sesuaikan bagian:
# DB_DATABASE=nama_database
# DB_USERNAME=root
# DB_PASSWORD=

# 6. Jalankan migrasi database
php artisan migrate

# 7. Jalankan server lokal
php artisan serve