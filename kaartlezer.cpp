#include <Arduino.h>
 
#include <Arduino_JSON.h>

#include <WiFi.h>
#include <HTTPClient.h>

const char* ssid     = "embedded";
const char* password = "IoTembedded";
const char* serverName = "http://embed-dev-1.stuvm.be/post-kaart.php";
const char* serverName1 = "http://embed-dev-1.stuvm.be/kaarten.php";
String apiKeyValue = "tPmAT5Ab3j7F7";

unsigned long lastTime = 0;
unsigned long timerDelay = 5000;
String sensorReadings;
String sensorReadingsArr[100];

//Libraries
#include <SPI.h>
#include <MFRC522.h>
#define SS_PIN 23
#define RST_PIN 22
//Variables
byte nuidPICC[4] = {0, 0, 0, 0};
MFRC522::MIFARE_Key key;
MFRC522 rfid = MFRC522(SS_PIN, RST_PIN);
String HEXA = "";

void printHex(byte *buffer, byte bufferSize) {
 for (byte i = 0; i < bufferSize; i++) {
   HEXA += buffer[i] < 0x10 ? " 0" : " ";
   HEXA += String(buffer[i], HEX);
  
 }
  Serial.print(HEXA);
  if (WiFi.status() == WL_CONNECTED) {
    WiFiClient client;
    HTTPClient http;

    // Your Domain name with URL path or IP address with path
    http.begin(client, serverName);
        http.addHeader("Content-Type", "application/x-www-form-urlencoded");
        String httpRequestData = "api_key=" + apiKeyValue + "&num='" + HEXA + "'";
        Serial.print("httpRequestData: ");
        Serial.println(httpRequestData);

        int httpResponseCode = http.POST(httpRequestData);

        if (httpResponseCode > 0) {
          Serial.print("HTTP Response code: ");
          Serial.println(httpResponseCode);
        }
        else {
          Serial.print("Error code: ");
          Serial.println(httpResponseCode);
        }
        // Free resources
        http.end();
      
    HEXA ="";
  }
 
}

void readRFID() { /* function readRFID */
 ////Read RFID card
 for (byte i = 0; i < 6; i++) {
   key.keyByte[i] = 0xFF;
 }
 // Look for new 1 cards
 if ( ! rfid.PICC_IsNewCardPresent())
   return;
 // Verify if the NUID has been readed
 if (  !rfid.PICC_ReadCardSerial())
   return;
 // Store NUID into nuidPICC array
 for (byte i = 0; i < 4; i++) {
   nuidPICC[i] = rfid.uid.uidByte[i];
 }
 Serial.print(F("RFID In hex: "));
 printHex(rfid.uid.uidByte, rfid.uid.size);
 Serial.println();
 // Halt PICC
 rfid.PICC_HaltA();
 // Stop encryption on PCD
 rfid.PCD_StopCrypto1();
}


 
void setup() {
 //Init Serial USB
 Serial.begin(115200);
 //init rfid D8,D5,D6,D7
 SPI.begin();
 rfid.PCD_Init();
WiFi.begin(ssid, password);
  Serial.println("Connecting");
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("");
  Serial.print("Connected to WiFi network with IP Address: ");
  Serial.println(WiFi.localIP());

}


void loop()
{
  if ( ! rfid.PICC_IsNewCardPresent()){
    readRFID();
  }
}

