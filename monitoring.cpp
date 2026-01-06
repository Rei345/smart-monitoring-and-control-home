/**
 * SISTEM MONITORING & KONTROL SMART HOME BERBASIS IOT
 * ---------------------------------------------------
 * Platform : ESP32
 * Protocol : MQTT (Mosquitto) & HTTP (Laravel)
 */

#include <WiFi.h>
#include <PubSubClient.h>
#include <ESP32Servo.h>
#include <Adafruit_Sensor.h>
#include <Adafruit_BMP280.h>
#include "DHT.h"
#include <ArduinoJson.h>
#include <Stepper.h>
#include "time.h"

// ==========================================
// 1. KONFIGURASI JARINGAN
// ==========================================
const char* ssid        = "vivo Y36";
const char* password    = "sitompul18";
const char* brokerHost  = "10.89.124.94";
const int   mqttPort    = 1883;
const char* brokerUser  = "AdminMQTT";
const char* brokerPass  = "pwd123";

// NTP Server
const char* ntpServer   = "pool.ntp.org";
const long  gmtOffset   = 7 * 3600; // GMT+7 (WIB)

// ==========================================
// 2. TOPIK MQTT
// ==========================================
const char* TOPIC_SENSOR       = "sistem_monitoring_control_automation/sensor";
const char* TOPIC_CONFIG       = "sistem_monitoring_control_automation/config";
const char* TOPIC_PINTU_MANUAL = "sistem_monitoring_control_automation/pintu/manual";
const char* TOPIC_PINTU_JADWAL = "sistem_monitoring_control_automation/pintu/jadwal";
const char* TOPIC_PINTU_STATUS = "sistem_monitoring_control_automation/door/status";
const char* TOPIC_KIPAS_MANUAL = "sistem_monitoring_control_automation/kipas/manual";
const char* TOPIC_KIPAS_STATUS = "sistem_monitoring_control_automation/fan/status";
const char* TOPIC_KIPAS_SPEED  = "sistem_monitoring_control_automation/kipas/speed";
const char* TOPIC_REQ_CONFIG   = "sistem_monitoring_control_automation/request_config";

// ==========================================
// 3. HARDWARE
// ==========================================
#define DHTPIN    4
#define DHTTYPE   DHT11
#define SERVOPIN  15
#define TRIGPIN   18
#define ECHOPIN   5

Stepper myStepper(2048, 26, 33, 25, 32); 
WiFiClient espClient;
PubSubClient client(espClient);
DHT dht(DHTPIN, DHTTYPE);
Adafruit_BMP280 bmp;
Servo myServo;

// ==========================================
// 4. VARIABEL GLOBAL
// ==========================================
// Konfigurasi Default
float CONFIG_BATAS_SUHU = 30.0; 
int   CONFIG_BATAS_JARAK = 15; 

// Status
bool doorIsOpen = false;
bool fanIsOn = false;
String manualDoorState = "NONE";
int fanSpeedLevel = 0;
int currentFanSpeedRPM = 10;

// Jadwal (Default)
int openHour = 8, openMinute = 0;
int closeHour = 17, closeMinute = 0;

// Data Sensor
float currentDistance = 0.0;
float currentTemp = 0.0;

// Timer
unsigned long lastObjectDetectedTime = 0;
const long autoCloseDelay = 3000;
unsigned long lastMsgTime = 0;
unsigned long lastDistRead = 0;
const int MSG_INTERVAL = 2000; 

// ==========================================
// 5. KONEKSI
// ==========================================
void connectToWiFi() {
  Serial.print("\nMenghubungkan WiFi");
  WiFi.mode(WIFI_STA);
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) { delay(500); Serial.print("."); }
  Serial.println("\n✅ WiFi Terhubung!");
}

void reconnectMQTT() {
  while (!client.connected()) {
    Serial.print("Menghubungkan MQTT...");
    String clientId = "ESP32-" + String(random(0xffff), HEX);
    if (client.connect(clientId.c_str(), brokerUser, brokerPass)) {
      Serial.println("✅ Terhubung!");
      client.subscribe(TOPIC_PINTU_MANUAL);
      client.subscribe(TOPIC_KIPAS_MANUAL);
      client.subscribe(TOPIC_PINTU_JADWAL);
      client.subscribe(TOPIC_CONFIG);
      client.subscribe(TOPIC_KIPAS_SPEED);
      
      client.publish(TOPIC_REQ_CONFIG, "sync");
    } else {
      Serial.print("Gagal rc="); Serial.print(client.state()); delay(5000);
    }
  }
}

// ==========================================
// 6. SENSOR & PUBLISH
// ==========================================
void readUltrasonic() {
  if (millis() - lastDistRead > 200) {
    lastDistRead = millis();
    digitalWrite(TRIGPIN, LOW); delayMicroseconds(2);
    digitalWrite(TRIGPIN, HIGH); delayMicroseconds(10);
    digitalWrite(TRIGPIN, LOW);
    long duration = pulseIn(ECHOPIN, HIGH, 30000); 
    if (duration > 0) {
      float d = duration * 0.034 / 2;
      if (d > 0 && d < 400) currentDistance = d; 
    }
  }
}

void publishDoorStatus() {
  StaticJsonDocument<64> doc;
  doc["door_state"] = doorIsOpen ? "OPEN" : "CLOSED";
  char buffer[64];
  serializeJson(doc, buffer);
  client.publish(TOPIC_PINTU_STATUS, buffer);
}

void publishFanStatus() {
  StaticJsonDocument<64> doc;
  doc["fan_state"] = fanIsOn ? "ON" : "OFF";
  char buffer[64];
  serializeJson(doc, buffer);
  client.publish(TOPIC_KIPAS_STATUS, buffer);
}

// ==========================================
// 7. CALLBACK MQTT
// ==========================================
void callback(char* topic, byte* payload, unsigned int length) {
  String msg;
  for (int i = 0; i < length; i++) msg += (char)payload[i];
  Serial.printf("Pesan [%s]: %s\n", topic, msg.c_str());

  // A. UPDATE CONFIG
  if (String(topic) == TOPIC_CONFIG) {
      DynamicJsonDocument doc(256);
      deserializeJson(doc, msg);
      if(doc.containsKey("batas_suhu")) CONFIG_BATAS_SUHU = doc["batas_suhu"];
      if(doc.containsKey("batas_jarak")) CONFIG_BATAS_JARAK = doc["batas_jarak"];
      Serial.println("✅ Konfigurasi Diperbarui!");
  }
  
  // B. PINTU MANUAL
  else if (String(topic) == TOPIC_PINTU_MANUAL) {
    if (msg == "BUKA") { 
        myServo.write(90); doorIsOpen = true; manualDoorState = "MANUAL_OPEN"; 
    } else if (msg == "TUTUP") { 
        myServo.write(0); doorIsOpen = false; manualDoorState = "MANUAL_CLOSED"; 
    } else if (msg == "AUTO") { 
        manualDoorState = "NONE";
        Serial.println("🔄 Mode: Kembali ke OTOMATIS");
    }
    publishDoorStatus();
  }

  // C. KIPAS MANUAL
  else if (String(topic) == TOPIC_KIPAS_MANUAL) {
    if (msg == "ON") fanIsOn = true; else if (msg == "OFF") fanIsOn = false;
    publishFanStatus();
  }

  // D. KECEPATAN KIPAS
  else if (String(topic) == TOPIC_KIPAS_SPEED) {
    int level = msg.toInt();
    fanSpeedLevel = level;
    if (level == 0) fanIsOn = false;
    else { fanIsOn = true; currentFanSpeedRPM = map(level, 1, 5, 5, 15); }
    publishFanStatus();
  }

  // E. JADWAL
  else if (String(topic) == TOPIC_PINTU_JADWAL) {
    DynamicJsonDocument doc(128); 
    deserializeJson(doc, msg);
    const char* o = doc["open"]; const char* c = doc["close"];
    sscanf(o, "%d:%d", &openHour, &openMinute);
    sscanf(c, "%d:%d", &closeHour, &closeMinute);
    manualDoorState = "NONE";
    Serial.println("✅ Jadwal Diperbarui");
  }
}

// ==========================================
// 8. LOGIKA OTOMATIS
// ==========================================
void logicPintu() {
  if (manualDoorState != "NONE") return;

  struct tm timeinfo;
  if (!getLocalTime(&timeinfo)) return;

  int timeNow = (timeinfo.tm_hour * 60) + timeinfo.tm_min;
  int timeOpen = (openHour * 60) + openMinute;
  int timeClose = (closeHour * 60) + closeMinute;

  bool isScheduleActive = false;
  if (timeOpen < timeClose) {
    if (timeNow >= timeOpen && timeNow < timeClose) isScheduleActive = true;
  } else {
    if (timeNow >= timeOpen || timeNow < timeClose) isScheduleActive = true;
  }

  if (isScheduleActive) {
    // BUKA jika ada objek dekat
    if (currentDistance > 0 && currentDistance <= CONFIG_BATAS_JARAK) {
      lastObjectDetectedTime = millis();
      if (!doorIsOpen) {
        myServo.write(90); doorIsOpen = true; publishDoorStatus();
        Serial.println("👁️ AUTO: Objek Terdeteksi -> BUKA");
      }
    } 
    // TUTUP jika objek hilang & timeout
    else if (doorIsOpen && (millis() - lastObjectDetectedTime > autoCloseDelay)) {
      myServo.write(0); doorIsOpen = false; publishDoorStatus();
      Serial.println("⏳ AUTO: Timeout -> TUTUP");
    }
  } else {
    // Di luar jadwal -> TUTUP
    if (doorIsOpen) {
      myServo.write(0); doorIsOpen = false; publishDoorStatus();
      Serial.println("🔒 JADWAL TUTUP");
    }
  }
}

void logicKipas() {
  currentTemp = bmp.readTemperature();
  bool triggerOn = fanIsOn || (currentTemp >= CONFIG_BATAS_SUHU) || (fanSpeedLevel > 0);
  if (triggerOn) {
    int speed = (fanSpeedLevel > 0) ? currentFanSpeedRPM : 10;
    myStepper.setSpeed(speed); myStepper.step(10); 
  }
}

// ==========================================
// 9. MAIN LOOP
// ==========================================
void setup() {
  Serial.begin(115200);
  dht.begin();
  if (!bmp.begin(0x76)) Serial.println("⚠️ Warning: BMP280 Error");
  
  myServo.attach(SERVOPIN); myServo.write(0);
  pinMode(TRIGPIN, OUTPUT); pinMode(ECHOPIN, INPUT);

  connectToWiFi();
  configTime(gmtOffset, 0, ntpServer);
  client.setServer(brokerHost, mqttPort);
  client.setCallback(callback);
  
  Serial.println("\n=== SISTEM SIAP ===");
}

void loop() {
  if (WiFi.status() != WL_CONNECTED) connectToWiFi();
  if (!client.connected()) reconnectMQTT();
  client.loop();

  readUltrasonic();
  logicPintu();
  logicKipas();

  unsigned long now = millis();
  if (now - lastMsgTime > MSG_INTERVAL) {
    lastMsgTime = now;
    float h = dht.readHumidity();
    float p = bmp.readPressure() / 100.0F;
    float a = bmp.readAltitude(1013.25);
    
    if (!isnan(h)) {
      StaticJsonDocument<256> doc;
      doc["suhu"] = currentTemp;
      doc["kelembaban"] = h;
      doc["tekanan"] = p;
      doc["ketinggian"] = a;
      doc["jarak_pintu"] = currentDistance;
      
      char buffer[256];
      serializeJson(doc, buffer);
      client.publish(TOPIC_SENSOR, buffer);
      // Serial.println("📡 Data Terkirim"); 
    }
  }
}