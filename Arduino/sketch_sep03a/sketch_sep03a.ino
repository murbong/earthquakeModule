#include <doxygen.h>
#include <ESP8266.h>
#define SSID  "Daebong"      // change this to match your WiFi SSID
#define PASS  "eoqhddl0921!"  // change this to match your WiFi password
#define HOST_NAME "earthquake.murbong.ga"
#define HOST_PORT   (80)
#define SERIAL_BUFFER_SIZE 64

#define dbgTerminal Serial
ESP8266 wifi(Serial1);

const int xPin = 0;
const int yPin = 1;
const int zPin = 2;
const float ts = 0.00001;
unsigned long tCount = 0;
unsigned long tCountPre = 0;
char rx_byte = 0;

float x,y,z; 
float dot_x,dot_y,dot_z;
float t, dt;
float int_y = 0;
float x_pre,y_pre,z_pre;

void saveStatesForNextStep() {
  tCountPre = tCount;
}

void printStates(float x, float y, float z) {
  Serial.print("X : ");
  Serial.print(x);
  Serial.print(" Y : ");
  Serial.print(y);
  Serial.print(" Z : ");
  Serial.println(z);
  
}

int minVal = 265;
int maxVal = 402;
void setup(void)
{
    Serial.begin(9600);
    Serial1.begin(115200);
    Serial.print("setup begin\r\n");

    Serial.print("FW Version: ");
    Serial.println(wifi.getVersion().c_str());
    
    
    if (wifi.setOprToStationSoftAP()) {
        Serial.print("to station + softap ok\r\n");
    } else {
        Serial.print("to station + softap err\r\n");
    }

while(true){

    if (wifi.joinAP(SSID, PASS)) {
        Serial.print("Join AP success\r\n");
        Serial.print("IP: ");       
        Serial.println(wifi.getLocalIP().c_str());
        break;
    } else {
        Serial.print("Join AP failure\r\n");
    }

}
    
    if (wifi.disableMUX()) {
        Serial.print("single ok\r\n");
    } else {
        Serial.print("single err\r\n");
    }
    
    Serial.print("setup end\r\n");
    x_pre = 0;
  y_pre = 0;
  z_pre = 0;





}
void loop(){

    uint8_t buffer[1024] = {0};


 tCount = micros();
 t = (float)(tCount) * ts;
 dt = (float)(tCount - tCountPre) * ts;
 x= analogRead(xPin);
 dot_x = (x - x_pre)/dt;
 x_pre=x;

 int y = analogRead(yPin);
 dot_y = (y - y_pre)/dt;
 y_pre=y;

 int z = analogRead(zPin);
 dot_z = (z - z_pre)/dt;
 z_pre=z;

    String http = String();
    http += "GET /auto.php?freq=";
    http += (abs(dot_x)+abs(dot_y)+abs(dot_z))/3;
    http += " HTTP/1.1\r\n";
    http += "Host: ";
    http += HOST_NAME;
    http += "\r\n";
    http += "Connection: close\r\n\r\n";
    Serial.println(http);

    if (wifi.createTCP(HOST_NAME, HOST_PORT)) {
        Serial.print("create tcp ok\r\n");
        Serial.println(http);
        wifi.send((const uint8_t*)http.c_str(), http.length());
    } else {
        Serial.print("create tcp err\r\n");
    }
  if (wifi.releaseTCP()) {
        Serial.print("release tcp ok\r\n");
    } else {
        Serial.print("release tcp err\r\n");
    }
   


saveStatesForNextStep();
//printStates(dot_x,dot_y,dot_z);
delay(1000);

}
