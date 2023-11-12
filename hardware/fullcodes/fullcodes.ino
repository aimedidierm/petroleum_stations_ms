#include <Arduino.h>
#include <SPI.h>
#include <MFRC522.h>
#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <WiFiClientSecureBearSSL.h>
#include <Wire.h>
#include <ArduinoJson.h>


#define RST_PIN         D0
#define SS_PIN          D8
MFRC522 mfrc522(SS_PIN, RST_PIN);   // Create MFRC522 instance.

const char* ssid = "Balance";
const char* password = "balance123";
int buzzerPin = D3;
String content = "";


void setup()
{
  Serial.begin(9600);
  SPI.begin();
  pinMode(buzzerPin, OUTPUT);
  mfrc522.PCD_Init();
  WiFi.mode(WIFI_STA);
  WiFi.begin(ssid, password);
//  lcd.setCursor(0, 0);
//  lcd.print("Connecting to");
//  lcd.setCursor(0, 1);
//  lcd.print("WiFi network");
  while (WiFi.status() != WL_CONNECTED) {
    delay(1000);
  }
//  lcd.clear(),
//  lcd.print("System");
//  lcd.setCursor(0, 1);
//  lcd.print("Starting");
//  delay(1000);
//  lcd.clear(),
//  lcd.print("System");
//  lcd.setCursor(0, 1);
//  lcd.print("Ready");
//  delay(2000);
//  lcd.clear(),
//  lcd.print("System");
//  lcd.setCursor(0, 1);
//  lcd.print("Ready");
//  delay(3000);
//  lcd.clear(),
//  lcd.print("Tap card");
}
void loop()
{
  readcard();
}

void readcard() {
  if (!mfrc522.PICC_IsNewCardPresent()) {
    return;
  }
  if (!mfrc522.PICC_ReadCardSerial()) {
    return;
  }
  Serial.print("UID tag: ");
  for (byte i = 0; i < mfrc522.uid.size; i++) {
    content += (mfrc522.uid.uidByte[i] < 0x10 ? "0" : "");
    content += String(mfrc522.uid.uidByte[i], HEX);
  }
  Serial.println("Card detected");
//  lcd.clear(),
//  lcd.print("Loading");
//  lcd.setCursor(0, 1);
//  lcd.print("Sending data");
  updateStatus();
}

void updateStatus() {
  if (WiFi.status() == WL_CONNECTED) {

    std::unique_ptr<BearSSL::WiFiClientSecure> client(new BearSSL::WiFiClientSecure);

    // Ignore SSL certificate validation (for testing only)
    client->setInsecure();
    HTTPClient http;

    Serial.println("[HTTPS] begin...");

    if (http.begin(*client, "https://gas.itaratec.com/api/attend")) {  // HTTPS
      Serial.println(content);
      Serial.println("[HTTPS] POST...");

      http.addHeader("Content-Type", "application/json");

      DynamicJsonDocument jsonDoc(128); // Adjust the buffer size as needed

      jsonDoc["card"] = content;

      String jsonPayload;
      serializeJson(jsonDoc, jsonPayload);

      // Send the POST request with the JSON payload
      int httpCode = http.POST(jsonPayload);

      // Check the HTTP response code
      if (httpCode > 0) {
        content = "";
        Serial.printf("[HTTPS] POST... code: %d\n", httpCode);

        // Read and print the response from the server
        String payload = http.getString();
        Serial.println(payload);
        DynamicJsonDocument doc(512); // Adjust the size based on your payload size
        DeserializationError error = deserializeJson(doc, payload);

        // Check for parsing errors
        if (error) {
          Serial.print(F("Error parsing JSON: "));
          Serial.println(error.c_str());
        } else {
          // Access JSON data
          bool cardAllowed = doc["card_allowed"];
          String message = doc["message"];

          if (cardAllowed == true)
          {
//            lcd.clear(),
//            lcd.print("Allowed!");
//            lcd.setCursor(0, 1);
//            lcd.print(message);
            playBeep1();
//            delay(3000);
//            lcd.clear();
//            lcd.setCursor(0, 0);
//            lcd.print("Tap card");
            readcard();
          }
          else {
//            lcd.clear(),
//            lcd.print("Error");
//            lcd.setCursor(0, 1);
//            lcd.print(message);
            playBeep2();
//            delay(3000);
//            lcd.clear();
//            lcd.setCursor(0, 0);
//            lcd.print("Tap card");
            readcard();
          }
        }
      } else {
        content = "";
        Serial.printf("[HTTPS] POST... failed, error: %s\n", http.errorToString(httpCode).c_str());
//        lcd.clear(),
//        lcd.print("Fail");
        playBeep2();
        readcard();
      }

      // End the HTTP connection
      http.end();
    } else {
      Serial.println("[HTTPS] Unable to connect");
//      lcd.clear(),
//      lcd.print("Unable to connect");
       playBeep2();
//      delay(3000);
      readcard();
    }
  }
}

void playBeep1() {
  digitalWrite(buzzerPin, HIGH);
  delay(3000);
  digitalWrite(buzzerPin, LOW);
  delay(1000);
}

void playBeep2() {
  digitalWrite(buzzerPin, HIGH);
  delay(400);
  digitalWrite(buzzerPin, LOW);
  delay(100);
  digitalWrite(buzzerPin, HIGH);
  delay(400);
  digitalWrite(buzzerPin, LOW);
  delay(100);
  digitalWrite(buzzerPin, HIGH);
  delay(400);
  digitalWrite(buzzerPin, LOW);
  delay(100);
}
