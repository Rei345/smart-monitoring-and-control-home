#include <WiFi.h>
#include <PubSubClient.h>
#include <ESP32Servo.h>
#include <Adafruit_Sensor.h>
#include <Adafruit_BMP280.h>
#include "DHT.h"
#include <ArduinoJson.h>
#include <Stepper.h>
#include "time.h"

// --- WiFi & MQTT Configuration ---
const char* ssid = "vivo Y36";
const char* password = "sitompul18";
const char* brokerUser = "AdminMQTT";
const char* brokerPass = "pwd123";
const char* brokerHost = "10.98.21.94"; // Pastikan IP ini benar
const int mqttPort = 1883;

// Topik MQTT
const char* BASE_TOPIC = "sistem_monitoring_control_automation";
const char* TOPIC_SENSOR = "sistem_monitoring_control_automation/sensor";
const char* TOPIC_PINTU_MANUAL = "sistem_monitoring_control_automation/pintu/manual";
const char* TOPIC_PINTU_JADWAL = "sistem_monitoring_control_automation/pintu/jadwal";
const char* TOPIC_PINTU_STATUS = "sistem_monitoring_control_automation/door/status";
const char* TOPIC_KIPAS_MANUAL = "sistem_monitoring_control_automation/kipas/manual";
const char* TOPIC_KIPAS_STATUS = "sistem_monitoring_control_automation/fan/status";

// --- Objek ---
WiFiClient espClient;
PubSubClient client(espClient);
DHT dht(4, DHT11);
Adafruit_BMP280 bmp;
Servo myServo;
Stepper myStepper(2048, 26, 33, 25, 32); // Urutan pin: IN1, IN3, IN2, IN4

// --- Pin ---
const int servoPin = 15;
const int trigPin = 18;
const int echoPin = 5;

const int ULTRASONIC_THRESHOLD = 10;
const char* ntpServer = "pool.ntp.org";
const long gmtOffset_sec = 7 * 3600; // GMT+7

// --- Variabel Global ---
bool doorIsOpen = false;
bool fanIsOn = false;
int openHour = 8, openMinute = 0;
int closeHour = 17, closeMinute = 0;
unsigned long lastObjectDetectedTime = 0;
const long autoCloseDelay = 3000;        
String manualDoorState = "NONE";          // "NONE", "MANUAL_OPEN", "MANUAL_CLOSED"

// --- Fungsi Koneksi ---
void connectToWiFi() {
  Serial.print("Connecting to ");
  Serial.println(ssid);
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("\nWiFi Connected! IP: " + WiFi.localIP().toString());
}

// --- Fungsi untuk publish status ---
void publishDoorStatus() {
  StaticJsonDocument<32> doc;
  doc["door_state"] = doorIsOpen ? "OPEN" : "CLOSED";
  char buffer[32];
  serializeJson(doc, buffer);
  if (!client.publish(TOPIC_PINTU_STATUS, buffer)) {
      Serial.println("Gagal publish status pintu!");
  }
}

void publishFanStatus() {
  StaticJsonDocument<32> doc;
  doc["fan_state"] = fanIsOn ? "ON" : "OFF";
  char buffer[32];
  serializeJson(doc, buffer);
   if (!client.publish(TOPIC_KIPAS_STATUS, buffer)) {
      Serial.println("Gagal publish status kipas!");
  }
}

// --- Callback MQTT ---
void callback(char* topic, byte* payload, unsigned int length) {
  String msg;
  for (int i = 0; i < length; i++) msg += (char)payload[i];
  Serial.printf("Message on [%s]: %s\n", topic, msg.c_str());

  if (String(topic) == TOPIC_PINTU_MANUAL) {
    if (msg == "BUKA") {
      myServo.write(90);
      doorIsOpen = true;
      manualDoorState = "MANUAL_OPEN";
    } else if (msg == "TUTUP") {
      myServo.write(0);
      doorIsOpen = false;
      manualDoorState = "MANUAL_CLOSED";
    }
    publishDoorStatus();
  }
  else if (String(topic) == TOPIC_KIPAS_MANUAL) {
    if (msg == "ON") {
        fanIsOn = true;
    } else if (msg == "OFF") {
        fanIsOn = false;
    }
    publishFanStatus();
  }
  else if (String(topic) == TOPIC_PINTU_JADWAL) {
    DynamicJsonDocument doc(128);
    if (!deserializeJson(doc, msg)) {
      sscanf(doc["open"] | "08:00", "%d:%d", &openHour, &openMinute);
      sscanf(doc["close"] | "17:00", "%d:%d", &closeHour, &closeMinute);
      Serial.printf("Jadwal diterima: Buka %02d:%02d, Tutup %02d:%02d\n", openHour, openMinute, closeHour, closeMinute);
      manualDoorState = "NONE"; 
      Serial.println("Mode pintu kembali ke otomatis karena jadwal baru.");
    } else {
       Serial.println("Gagal parse JSON jadwal.");
    }
  }
}

// --- Fungsi Reconnect MQTT --- 
void reconnectMQTT() {
  while (!client.connected()) {
    Serial.print("Attempting MQTT connection...");
    String clientId = "ESP32-" + String(random(0xffff), HEX);
    if (client.connect(clientId.c_str(), brokerUser, brokerPass)) {
      Serial.println("connected");
      // Subscribe ke topik setelah konek
      client.subscribe(TOPIC_PINTU_MANUAL);
      client.subscribe(TOPIC_PINTU_JADWAL);
      client.subscribe(TOPIC_KIPAS_MANUAL);
    } else {
      Serial.printf("failed, rc=%d try again in 5 seconds\n", client.state());
      delay(5000);
    }
  }
}

// --- Fungsi Publish Sensor --- 
void publishSensors() {
  float suhu = bmp.readTemperature();
  float kelembaban = dht.readHumidity();
  float tekanan = bmp.readPressure() / 100.0F;
  float ketinggian = bmp.readAltitude(1013.25); 

  digitalWrite(trigPin, LOW);
  delayMicroseconds(2);
  digitalWrite(trigPin, HIGH);
  delayMicroseconds(10);
  digitalWrite(trigPin, LOW);
  long duration_us = pulseIn(echoPin, HIGH, 25000); // Timeout 25ms
  float jarak_pintu = 0.0;
  if (duration_us > 0) {
      jarak_pintu = duration_us * 0.034 / 2.0;
  }

  // Cek jika pembacaan kelembaban gagal
  if (isnan(kelembaban)) {
    Serial.println("Failed to read from DHT sensor!");
    return; // Tidak mengirim data jika DHT gagal
  }

  DynamicJsonDocument doc(256);
  doc["suhu"] = suhu;
  doc["kelembaban"] = kelembaban;
  doc["tekanan"] = tekanan;
  doc["ketinggian"] = ketinggian;
  doc["jarak_pintu"] = jarak_pintu;

  char buffer[256];
  size_t n = serializeJson(doc, buffer);
  if (client.publish(TOPIC_SENSOR, buffer, n)) {
      Serial.println("Sensor data published.");
  } else {
      Serial.println("Failed to publish sensor data.");
  }
}

// --- Fungsi Kontrol Kipas ---
void kontrolKipas() {
  float suhu_terkini = bmp.readTemperature();

  bool shouldBeRunning = (fanIsOn || suhu_terkini >= 30.0);

  if (shouldBeRunning && !fanIsOn) {
    fanIsOn = true;
    publishFanStatus();
    Serial.println("Kipas ON otomatis karena suhu.");
  } else if (!shouldBeRunning && fanIsOn) {
    fanIsOn = false; 
    publishFanStatus();
    Serial.println("Kipas OFF otomatis karena suhu normal.");
  }

  if (shouldBeRunning) {
    myStepper.setSpeed(12);
    myStepper.step(100);
  }
}

// --- Logika Pintu Otomatis (dengan Mode Manual Permanen) ---
void kontrolPintu() {
  // PRIORITAS 1: Jika mode manual aktif, JANGAN jalankan logika otomatis
  if (manualDoorState != "NONE") {
    return; // Keluar dari fungsi, biarkan status manual berlaku
  }

  // --- Logika otomatis HANYA berjalan jika manualDoorState == "NONE" ---
  struct tm timeinfo;
  if (!getLocalTime(&timeinfo)) {
    Serial.println("Gagal mendapatkan waktu NTP untuk kontrol pintu!");
    return; // Keluar jika waktu tidak valid
  }

  int currentHour = timeinfo.tm_hour;
  int currentMinute = timeinfo.tm_min;

  // Cek apakah jadwal aktif
  bool jadwalAktif = (currentHour > openHour || (currentHour == openHour && currentMinute >= openMinute)) &&
                    (currentHour < closeHour || (currentHour == closeHour && currentMinute < closeMinute));

  // Baca sensor ultrasonik
  digitalWrite(trigPin, LOW);
  delayMicroseconds(2);
  digitalWrite(trigPin, HIGH);
  delayMicroseconds(10);
  digitalWrite(trigPin, LOW);
  long duration = pulseIn(echoPin, HIGH, 25000); // Timeout 25ms
  float jarak = 0.0;
  if (duration > 0) {
    jarak = duration * 0.034 / 2.0;
  }

  // Logika buka/tutup otomatis
  if (jadwalAktif) {
    // Kondisi A: Objek terdeteksi dekat
    if (jarak > 0 && jarak <= ULTRASONIC_THRESHOLD) {
      lastObjectDetectedTime = millis(); // Perbarui waktu terakhir objek terlihat
      // Jika pintu masih tertutup, buka
      if (!doorIsOpen) {
        myServo.write(90);
        doorIsOpen = true;
        publishDoorStatus();
        Serial.println("Pintu terbuka otomatis (objek terdeteksi dalam jadwal)");
      }
    }
    // Kondisi B: Objek TIDAK terdeteksi DAN pintu sedang terbuka
    else if ((jarak <= 0 || jarak > ULTRASONIC_THRESHOLD) && doorIsOpen) {
      // Cek apakah sudah 3 detik sejak objek terakhir terlihat
      if (millis() - lastObjectDetectedTime >= autoCloseDelay) {
        myServo.write(0);
        doorIsOpen = false;
        publishDoorStatus();
        Serial.println("Pintu ditutup otomatis (objek menjauh > 3 detik dalam jadwal)");
      }
    }
  }
  else { // Di luar jadwal
    // Jika pintu sedang terbuka, tutup segera
    if (doorIsOpen) {
      myServo.write(0);
      doorIsOpen = false;
      publishDoorStatus();
      Serial.println("Pintu ditutup otomatis (di luar jadwal)");
      lastObjectDetectedTime = 0; // Reset timer
    }
  }
}

// --- Setup ---
void setup() {
  Serial.begin(115200);
  connectToWiFi(); // Fungsi ini sudah didefinisikan di atas

  configTime(gmtOffset_sec, 0, ntpServer); // Inisialisasi NTP

  client.setServer(brokerHost, mqttPort);
  client.setCallback(callback); // Fungsi ini sudah didefinisikan di atas

  dht.begin();
  if (!bmp.begin(0x76)) { // Cek alamat I2C BMP280 (0x76 atau 0x77)
    Serial.println("Could not find a valid BMP280 sensor, check wiring or address!");
    while (1); // Berhenti jika BMP tidak ditemukan
  }

  myServo.attach(servoPin);
  myServo.write(0); // Posisi awal tertutup

  pinMode(trigPin, OUTPUT);
  pinMode(echoPin, INPUT);

  myStepper.setSpeed(10); // Kecepatan awal stepper

  Serial.println("Setup selesai.");
}

// --- Loop ---
unsigned long lastMsg = 0;
const int MSG_INTERVAL = 2000; // Interval pengiriman data sensor (ms)

void loop() {
  // Jaga koneksi MQTT
  if (!client.connected()) {
    reconnectMQTT(); 
  }
  client.loop();

  // Jalankan logika kontrol aktuator
  kontrolPintu(); 
  kontrolKipas();

  // Kirim data sensor secara berkala
  unsigned long now = millis();
  if (now - lastMsg >= MSG_INTERVAL) {
    lastMsg = now;
    publishSensors(); 
  }
}