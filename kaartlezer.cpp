#include <Arduino.h>

//Libraries
#include <SPI.h>//https://www.arduino.cc/en/reference/SPI
//we gebruiken de spi library om het spi protocol op de esp te kunnen beginnen
#include <MFRC522.h>//https://github.com/miguelbalboa/rfid
//we maken gebruik van de specifieke library van de module omdat dit gemakkelijker is om de UID uit te lezen
//het UID zit namelijk opgeslagen in een speciaal geheugen van de tag
//Constants
#define SS_PIN 23
#define RST_PIN 22
//Variables
byte nuidPICC[4] = {0, 0, 0, 0};
MFRC522::MIFARE_Key key;
MFRC522 rfid = MFRC522(SS_PIN, RST_PIN);

// geeft de uid van de kaart
void printHex(byte *buffer, byte bufferSize) {
 for (byte i = 0; i < bufferSize; i++) {
   Serial.print(buffer[i] < 0x10 ? " 0" : " ");
   Serial.print(buffer[i], HEX);
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
}
void loop() {
  readRFID();
}

