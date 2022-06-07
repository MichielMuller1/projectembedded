#include "Arduino.h"
/*!
 * @file readACCurrent.
 * @n This example reads Analog AC Current Sensor.

 * @copyright   Copyright (c) 2010 DFRobot Co.Ltd (https://www.dfrobot.com)
 * @licence     The MIT License (MIT)
 * @get from https://www.dfrobot.com

 Created 2016-3-10
 By berinie Chen <bernie.chen@dfrobot.com>

 Revised 2019-8-6
 By Henry Zhao<henry.zhao@dfrobot.com>
*/

const int ACPin = A2;         //De analoge esp32 pin die gaat uitlezen (A2 en A3)
#define ACTectionRange 20;    //onze sensor is voor 20A maximum

// VREF: Analog reference
#define VREF 3.3  //referentie voltage is 3.3V van de esp32

float readACCurrentValue()
{
  //variable initialiseren
  float ACCurrtntValue = 0;
  float peakVoltage = 0;  
  float Ueff = 0;  //Vrms
  //5 keer achter elkaar een meting doen met een korte delay tussen en deze optellen om later het gemiddelde te berekenen
  for (int i = 0; i < 5; i++)
  {
    peakVoltage += analogRead(ACPin);   //het peak voltage uitlezen en bijtellen
    delay(1);
  }
  //het gemiddelde nemen
  peakVoltage = peakVoltage / 5;
  //Ueff = Umax/vierkantswortel(2) ; vierkantswortel2 = 0.707  
  Ueff = peakVoltage * 0.707;    

  /*The circuit is amplified by 2 times, so it is divided by 2.*/
  Ueff = (Ueff / 1024 * VREF ) / 2;  

  ACCurrtntValue = Ueff * ACTectionRange;

  return ACCurrtntValue;
}

void setup() 
{
  Serial.begin(115200);
}

void loop() 
{
  float ACCurrentValue = readACCurrentValue(); //uitlezen van de huidige stroom
  Serial.print(ACCurrentValue);//huidige stroom naar serial monitor printen
}