#include <SoftwareSerial.h> //Librería que permite establecer comunicación serie en otros pines
#include "DHT.h"
#define DHTPIN 7      //Pin digital conectado al sensor de humedad y temperatura
#define LLAMA 2       //Pin sensor de llama
#define LR 8          //Pin LED rojo
#define LV 12         //Pin LED verde 
#define SONIDO 4      //Pin del sensor de sonido
#define BUZZER 6      //Pin buzzer
#define DHTTYPE DHT11 //Tipo de sensor de humedad y temperatura

SoftwareSerial BT(10,11); //10 RX, 11 TX. //Conectamos los pines RXD,TDX del módulo Bluetooth.
DHT dht(DHTPIN, DHTTYPE);

int valor_llama;          //Valor sensor llama
int valor_sonido = 0;     //Valor del sensor de sonido
int estado_led = LOW;     //Estado del LED RGB por detectar sonido
int estado_fuego = LOW;   //Estado de la detección de llamas
int tono = 468;           //Tono del buzzer
int intervalo = 7000;     //Tiempo entre medidas del sensor humtemp
unsigned long antes = 0;  //Tiempo previo
unsigned long ahora;      //Tiempo actual

void setup()
{
  Serial.begin(9600);  //Velocidad del puerto serie
  BT.begin(38400);     //Velocidad del puerto del módulo Bluetooth
  pinMode(LLAMA, INPUT);
  pinMode(SONIDO, INPUT);
  pinMode(BUZZER, OUTPUT);
  pinMode(LR, OUTPUT);
  pinMode(LV, OUTPUT);

  dht.begin();
}

void loop()
 {
    valor_llama = digitalRead(LLAMA);   //Lee el valor del sensor de llama
    valor_sonido = digitalRead(SONIDO); //Lee el valor del sensor de sonido
    
    if(valor_sonido == HIGH){
      estado_led = !estado_led; //Cambia el valor del estado del LED al opuesto (0 o 1)
      Serial.println("Cambio sonido");
      delay(100);
    }
      
    if(valor_llama == LOW){  //Si no detecta llama
      noTone(BUZZER);
      digitalWrite(LR, LOW);
      estado_fuego = LOW;
      cambioLED(estado_led);
    }
    else {  //Si detecta llama
      digitalWrite(LV, LOW);
      estado_fuego = HIGH;
      detectaFuego(tono);
    }
    
    ahora = millis(); //Tiempo actual
    
    if((ahora - antes) > intervalo){  //Envía los datos por Bluetooth respetando el intervalo de tiempo que se defina
      noTone(BUZZER); //Detiene el sonido del buzzer para que no haya problemas con el sensor humtemp
      delay(200);
      antes = ahora;  
      humedad_temperatura();

      //Se envía por Bluetooth el valor del estado del LED RGB
      char cadena_sonido[16];
      sprintf(cadena_sonido, "%d\n", estado_led);
      BT.write(cadena_sonido);

      //Se envía por Bluetooth el valor del estado del sensor de llamas
      char cadena_fuego[16];
      sprintf(cadena_fuego, "%d\n", estado_fuego);
      BT.write(cadena_fuego);
    }
 }


void detectaFuego(int tono){
  tone(BUZZER, tono);     //Suena el buzzer
  digitalWrite(LV, LOW);
  digitalWrite(LR, HIGH); //Solo muestra luz roja
  delay(200);
 }


void cambioLED(int estado_led){
  digitalWrite(LR, LOW);
      
  if(estado_led == LOW){  //No quiere ver luz si no hay fuego
    digitalWrite(LV, LOW);
  }
  else{  //Quiere ver luz aunque no haya fuego
    digitalWrite(LV, HIGH);
  }
 }


 void humedad_temperatura(){
  float h = dht.readHumidity(); //Humedad
  float t = dht.readTemperature(); //Temperatura en Celsius
  char x[8];
  char y[8];
  char cadena_t[16];
  char cadena_h[16];

  //Se envía por Bluetooth el valor de la humedad
  dtostrf(h,5,2,y);
  sprintf(cadena_h, "%s\n", y);
  BT.write(cadena_h);

  //Se envía por Bluetooth el valor de la temperatura
  dtostrf(t,5,2,x);
  sprintf(cadena_t, "%s\n", x);
  BT.write(cadena_t);
  
  Serial.print(F(" Humidity: "));
  Serial.print(h);
  Serial.print(F("%  Temperature: "));
  Serial.print(t);
  Serial.println(F("°C "));  
 }
