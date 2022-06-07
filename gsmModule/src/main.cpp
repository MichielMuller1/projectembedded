#include <Arduino.h>
#include <SoftwareSerial.h>//met deze library kan je pinnen aanduiden als rx en tx om zo meerdere serial verbindingen op de esp32 te hebben.
//software serial object om te communiceren met de SIM800L
SoftwareSerial mySerial(16, 17); //SIM800L Tx & Rx is geconnecteerd met esp32 rx 16 en tx 17
const int wt_ms = 100; // aantal ms dat we als wachttijd gebruiken
String moduleAnswer = "";
void updateSerial(unsigned int wait_ms, String &bufString) 
{
bufString = "";
delay(wait_ms);
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
mySerial.println("AT+CMGS=\"+324xxxxxxxx\"");//de nummer naar waar je wil sturen
updateSerial(wt_ms, moduleAnswer);
mySerial.print(sms); //dit is de tekst die je wil sturen
updateSerial(wt_ms, moduleAnswer);
mySerial.write(26);//26ste ascii karakter meesturen is nodig!!!
mySerial.println("");
updateSerial(wt_ms, moduleAnswer);
}
//dingen in de setup worden enkel uitgevoerd bij opstarten
void setup()
{
//Begin serial communicatie met esp serial monitor
Serial.begin(9600);
//Begin serial communication met SIM800L
mySerial.begin(9600);
Serial.println("Initializing..."); 
updateSerial(wt_ms, moduleAnswer);
}
void loop()
{
send_text("De stroom is uitgevallen");//dit bericht zou verzonden moeten worden als de stroom zou uitvallen.
delay(60000);
}
