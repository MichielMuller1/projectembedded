#include <Arduino.h>
#include <SoftwareSerial.h>
#include <Wire.h>

#include <WiFi.h>
#include <HTTPClient.h>
#include <Arduino_JSON.h>

#include <SPI.h>
#include <MFRC522.h>
#define SS_PIN 32
#define RST_PIN 14
const char* ssid     = "embedded";
const char* password = "IoTembedded";
const char* serverName = "http://embed-dev-1.stuvm.be/post-kaart.php";
const char* serverName1 = "http://embed-dev-1.stuvm.be/kastjes.php";
String apiKeyValue = "tPmAT5Ab3j7F7";

String sensorReadings;
String sensorReadingsArr[100];

//////////////////////////////////////
//////////////////////////////////////
//variablen definieren
//////////////////////////////////////
//////////////////////////////////////

byte nuidPICC[4] = {0, 0, 0, 0};
MFRC522::MIFARE_Key key;
MFRC522 rfid = MFRC522(SS_PIN, RST_PIN);
String HEXA = "";

//hoort bij gsm module
SoftwareSerial mySerial(16, 17); //SIM800L Tx & Rx is geconnecteerd met esp32 rx 16 en tx 17
const int wt_ms = 100; // aantal ms dat we als wachttijd gebruiken voor de gsm module
String moduleAnswer = "";

//hoort bij stroomspoel
const int stroomspoelBoven = A2;  //De analoge esp32 pin die gaat uitlezen (A2 en A3)
const int stroomspoelBeneden = A3;
#define ACTectionRange 20;    //onze sensor is voor 20A maximum
// VREF: Analog reference
#define VREF 3.3  //referentie voltage is 3.3V van de esp32


//van de io expansion
//https://www.instructables.com/IO-Expander-for-ESP32-ESP8266-and-Arduino/
#define MCPAddress  0x20

#define GP0 	0x00   // DATA PORT REGISTER 0 
#define GP1 	0x01   // DATA PORT REGISTER 1 
#define OLAT0 	0x02   // OUTPUT LATCH REGISTER 0 
#define OLAT1 	0x03   // OUTPUT LATCH REGISTER 1 
#define IPOL0   0x04  // INPUT POLARITY PORT REGISTER 0 
#define IPOL1   0x05  // INPUT POLARITY PORT REGISTER 1 
#define IODIR0  0x06  // I/O DIRECTION REGISTER 0 
#define IODIR1  0x07  // I/O DIRECTION REGISTER 1 
#define INTCAP0 0x08  // INTERRUPT CAPTURE REGISTER 0 
#define INTCAP1 0x09  // INTERRUPT CAPTURE REGISTER 1 
#define IOCON0  0x0A  // I/O EXPANDER CONTROL REGISTER 0 
#define IOCON1  0x0B  // I/O EXPANDER CONTROL REGISTER 1 

//magneetcontacten
const int magneetBoven = 33;
const int magneetMidden = 15;
const int magneetOnder = A4;

//solenoid
const int slotBoven = 27;
const int slotMidden = 12;
const int slotOnder = 21;

//relais
const int relaisBoven = 32;
const int relaisOnder = 0; // de code om deze relais aan te zetten is writeBlockData(GP0,0); om hem uit te zetten writeBlockData(GP0,1);

//solentoid
String openKastje = "0";

String rnummer = "0";

//////////////////////////////////////
//////////////////////////////////////
//functies
//////////////////////////////////////
//////////////////////////////////////

/////////////////////////////////////
//get
////////////////////////////////////
String httpGETRequest(const char* serverName) {
  WiFiClient client;
  HTTPClient http;

  // Your Domain name with URL path or IP address with path
  http.begin(client, serverName);

  // Send HTTP POST request
  int httpResponseCode = http.GET();

  String payload = "{}";

  if (httpResponseCode > 0) {
    Serial.print("HTTP Response code: ");
    Serial.println(httpResponseCode);
    payload = http.getString();
  }
  else {
    Serial.print("Error code: ");
    Serial.println(httpResponseCode);
  }
  // Free resources
  http.end();

  return payload;
}

////////////////////////////////////
//gsmModule
////////////////////////////////////
void updateSerial(unsigned int wait_ms, String &bufString) 
{
  bufString = "";
  vTaskDelay(wait_ms/ portTICK_PERIOD_MS);
  while (Serial.available()) //als er iets op de serial aankomt wordt het doorgestuurd naar myserial
  {
    mySerial.write(Serial.read());
  }
  if(mySerial.available()) {//als er iets binnenkomt op myserial wordt het in bufstring geplaatst
    bufString = mySerial.readString();
  }
  if(bufString != ""){//als bufstring niet 0 is de inhoud naar de serialmonitor printen
    Serial.println(bufString);
  }
}
//deze functie stuurt als ze word opgeroepen een sms met de tekst in die word meegegeven als parameter
void send_text(String sms){
  mySerial.println("AT+CMGF=1"); // SMS text mode
  updateSerial(wt_ms, moduleAnswer);
  mySerial.println("AT+CMGS=\"+32479560239\"");//de nummer naar waar je wil sturen
  updateSerial(wt_ms, moduleAnswer);
  mySerial.print(sms); //dit is de tekst die je wil sturen
  updateSerial(wt_ms, moduleAnswer);
  mySerial.write(26);//26ste ascii karakter meesturen is nodig!!!
  mySerial.println("");
  updateSerial(wt_ms, moduleAnswer);
}
//////////////////////////////////
//stroom meten code
//////////////////////////////////
float readACCurrentValue(int ACPin)
{
  //variable initialiseren
  float ACCurrtntValue = 0;
  float peakVoltage = 0;  
  float Ueff = 0;  //Vrms
  //5 keer achter elkaar een meting doen met een korte delay tussen en deze optellen om later het gemiddelde te berekenen
  for (int i = 0; i < 5; i++)
  {
    peakVoltage += analogRead(ACPin);   //het peak voltage uitlezen en bijtellen
    vTaskDelay(1/ portTICK_PERIOD_MS);
  }
  //het gemiddelde nemen
  peakVoltage = peakVoltage / 5;
  //Ueff = Umax/vierkantswortel(2) ; vierkantswortel2 = 0.707  
  Ueff = peakVoltage * 0.707;    

  /*The circuit is amplified by 2 times, so it is divided by 2.*/
  Ueff = (Ueff / 4095 * VREF ) / 2;  

  ACCurrtntValue = Ueff * ACTectionRange;

  return ACCurrtntValue/5;//delen door 5 omdat de draad 5 keer door de spoel gaat
}

//////////////////////////////////
//multiplexer aansturen
//////////////////////////////////
void writeBlockData(uint8_t cmd, uint8_t data)
{
  Wire.beginTransmission(MCPAddress);
  Wire.write(cmd);
  Wire.write(data);
  Wire.endTransmission();
  vTaskDelay(10/portTICK_PERIOD_MS);
}
//effect quinten
void effectQuinten(){
  for (size_t i = 1; i < 8; i++)
  {
    if(i==4){
      continue;
    }
    writeBlockData(GP1, i+(16*i));
    vTaskDelay(300/portTICK_PERIOD_MS);
  }
}

void ledstrip(String kleurWaar){
  if (kleurWaar=="onderRood")
  {
    writeBlockData(GP1, 1);
  }else if (kleurWaar=="onderGroen")
  {
    writeBlockData(GP1,2);
  }else if (kleurWaar=="bovenRood")
  {
    writeBlockData(GP1,16);
  }else if (kleurWaar=="bovenGroen")
  {
    writeBlockData(GP1,32);
  }else{
    writeBlockData(GP1,0);
  }
}

////////////////////////////////////
//get kastjes ledstrip
////////////////////////////////////
void getKast(){
    if (WiFi.status() == WL_CONNECTED) {

      sensorReadings = httpGETRequest(serverName1);
      Serial.println(sensorReadings);
      JSONVar myObject = JSON.parse(sensorReadings);

      if (JSON.typeof(myObject) == "undefined") {
        Serial.println("Parsing input failed!");
        return;
      }

      Serial.print("JSON object = ");
      Serial.println(myObject);

      JSONVar keys = myObject[0].keys();

      for (int i = 0; i < keys.length(); i++) {
        JSONVar value = myObject[0][keys[i]];
        String jsonString = JSON.stringify(value);
        Serial.print(keys[i]);
        Serial.print(" = ");
        Serial.println(value);
        sensorReadingsArr[i] = value;
      }

      if(sensorReadingsArr[4] == "1"){
        ledstrip("bovenRood");
      } else {
        ledstrip("bovenGroen");
      }

      JSONVar keys = myObject[1].keys();

      for (int i = 0; i < keys.length(); i++) {
        JSONVar value = myObject[0][keys[i]];
        String jsonString = JSON.stringify(value);
        Serial.print(keys[i]);
        Serial.print(" = ");
        Serial.println(value);
        sensorReadingsArr[i] = value;
      }
      if(sensorReadingsArr[4] == "1"){
        ledstrip("onderRood");
      } else {
        ledstrip("onderGroen");
      }
    }
  }

//////////////////////////////////
//kaartlezer
//////////////////////////////////
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

//////////////////////////////////
//solenoid
//////////////////////////////////

void solenoid(int solenoid, int magneetcontact){
  digitalWrite(solenoid,HIGH);
  while (digitalRead(magneetcontact)==0){
    delay(20);
  }
  if(digitalRead(magneetcontact)==1){//als het open is 5 seconden wachten
    int time = millis();
    while (millis()<(time+5000))//zolang er geen 5 seconden verstreken zijn
    {
        delay(100);
    }
  }
  digitalWrite(solenoid,LOW);
  delay(50000);
  //schrijf naar openkastje kastNr een 0
}

void solenoid(){
  //alle solenoids open
        digitalWrite(slotBoven,HIGH);
        digitalWrite(slotMidden,HIGH);
        digitalWrite(slotOnder,HIGH);

        while (digitalRead(magneetMidden)==0)//zolang het nog dicht is niets doen
        {
            delay(20);
            Serial.println("magneet dicht");
        }
        if(digitalRead(magneetMidden)==1){//als het open is 5 seconden wachten
            Serial.println("magneetOpen");
            int time = millis();
            while (millis()<(time+5000))//zolang er geen 5 seconden verstreken zijn
            {
                delay(100);
                Serial.println("wachten op time");
            }
            Serial.println("terug dicht");
            //kastje is al zeker 5 seconden open dus de solenoids mogen terug dicht.
            digitalWrite(slotBoven,LOW);
            digitalWrite(slotMidden,LOW);
            digitalWrite(slotOnder,LOW);
        }
        delay(50000);
        //openkastje kastnr op 0 zetten
}



//////////////////////////////////
//setup
//////////////////////////////////
void setup()
{
  //Begin serial communicatie met esp serial monitor
  Serial.begin(9600);

  //gsm module
  //Begin serial communication met SIM800L
  mySerial.begin(9600);
  Serial.println("Initializing..."); 
  updateSerial(wt_ms, moduleAnswer);

  //io expander
  vTaskDelay(1000/ portTICK_PERIOD_MS);
  Wire.begin(23,22); //ESP32
  //klok van 1MHz
  Wire.setClock(1000000); 
  //alle pinnen instellen als output
  writeBlockData(IODIR0, 0x00);
  writeBlockData(IODIR1, 0x00);
  //alle pinnen op 0 zetten
  writeBlockData(GP0, B00000001);//de relais
  writeBlockData(GP1, B00000000);//de leds

  //magneetcontacten
  pinMode(magneetBoven,INPUT_PULLUP);
  pinMode(magneetMidden,INPUT_PULLUP);
  pinMode(magneetOnder,INPUT_PULLUP);

  //solenoids
  pinMode(slotBoven,OUTPUT);
  pinMode(slotMidden,OUTPUT);
  pinMode(slotOnder,OUTPUT);

  //kaartlezer
  SPI.begin();
  rfid.PCD_Init();

  

  //wifi
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

//////////////////////////////////
//hoofdloop
//////////////////////////////////
void loop()
{
  readRFID();
  getKast();

  if(openKastje == "A"){
    Serial.println("solenoid alles");
    solenoid();
  }else if (openKastje == "1")
  {
    Serial.println("solenoid 1");
    solenoid(slotBoven,magneetBoven);
  }else if (openKastje == "2")
  {
    Serial.println("solenoid 2");
    solenoid(slotOnder,magneetOnder);
  }

  if(rnummer == "u0140140"){
    effectQuinten();
  }
 
}
