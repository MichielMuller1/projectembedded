// pin 4 voor reedswitch
const int reedSwitch = 4;

// Detecteert of de status van de deur veranderd is
bool changeState = false;

//reedswitch state (0=open, 1=toe)
bool state;
String doorState;

// declaratie variabelen
unsigned long previousMillis; 
const long interval = 1500;

// runt wanneer de status van de deur veranderd
ICACHE_RAM_ATTR void changeDoorStatus() {
  changeState = true;
}

void setup() {
  Serial.begin(115200);

  // huidige status van de deur
  pinMode(reedSwitch, INPUT);
  state = digitalRead(reedSwitch);
  
  //reedswitch pin als interrupt, interrupt functie en set CHANGE mode
  attachInterrupt(digitalPinToInterrupt(reedSwitch), changeDoorStatus, CHANGE);

}

void loop(){
  if (changeState){
    unsigned long currentMillis = millis();
    if(currentMillis - previousMillis >= interval) {
      previousMillis = currentMillis;
      // als de status veranderd, inverteer de status van de deur
      state = !state;
      if(state) {
        doorState = "Toe";
      }
      else{
        doorState = "Open";
      }
      changeState = false;
      Serial.println(state);
      Serial.println(doorState);
        
      }
    }  
  }
