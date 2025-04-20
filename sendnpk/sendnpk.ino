#include <WiFi.h>
#include <HTTPClient.h>

const char* ssid = "Admin";
const char* password = "machine1";

const String serverURL = "http://192.168.100.9/receive.php";

#define RXD2 16
#define TXD2 17
#define RE_PIN 18
#define DE_PIN 19

const byte readNPK[] = {0x01, 0x03, 0x00, 0x00, 0x00, 0x03, 0x05, 0xCB};
byte response[20];

void setup() {
  Serial.begin(115200);
  Serial2.begin(9600, SERIAL_8N1, RXD2, TXD2);

  pinMode(RE_PIN, OUTPUT);
  pinMode(DE_PIN, OUTPUT);
  digitalWrite(RE_PIN, LOW);
  digitalWrite(DE_PIN, LOW);

  // Wi-Fi setup
  WiFi.begin(ssid, password);
  Serial.print("Connecting to WiFi");
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("\nConnected to WiFi!");
}

void loop() {
  int nitrogen = 0, phosphorus = 0, potassium = 0;
  readNPKValues(nitrogen, phosphorus, potassium);

  Serial.println("------ NPK Readings ------");
  Serial.print("Nitrogen: "); Serial.print(nitrogen); Serial.println(" mg/kg");
  Serial.print("Phosphorus: "); Serial.print(phosphorus); Serial.println(" mg/kg");
  Serial.print("Potassium: "); Serial.print(potassium); Serial.println(" mg/kg");
  Serial.println("--------------------------");

  sendGET(nitrogen, phosphorus, potassium);
  delay(3000);
  sendPOST(nitrogen, phosphorus, potassium);
  delay(10000);
}

void readNPKValues(int &n, int &p, int &k) {
  digitalWrite(DE_PIN, HIGH);
  digitalWrite(RE_PIN, HIGH);
  delay(10);
  Serial2.write(readNPK, sizeof(readNPK));
  Serial2.flush();

  digitalWrite(DE_PIN, LOW);
  digitalWrite(RE_PIN, LOW);

  memset(response, 0, sizeof(response));
  int i = 0;
  unsigned long start = millis();
  while (millis() - start < 1000 && i < 11) {
    if (Serial2.available()) {
      response[i++] = Serial2.read();
    }
  }

  if (i >= 9) {
    n = (response[3] << 8) | response[4];
    p = (response[5] << 8) | response[6];
    k = (response[7] << 8) | response[8];
  } else {
    Serial.println("Failed to read NPK sensor.");
    n = p = k = 0;
  }
}

void sendGET(int n, int p, int k) {
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
    String url = serverURL + "?nitrogen=" + String(n) + "&phosphorus=" + String(p) + "&potassium=" + String(k);
    http.begin(url);
    int httpCode = http.GET();

    if (httpCode > 0) {
      String response = http.getString();
      Serial.println("GET Response: " + response);
    } else {
      Serial.println("GET Request failed.");
    }

    http.end();
  } else {
    Serial.println("WiFi disconnected.");
  }
}

void sendPOST(int n, int p, int k) {
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
    http.begin(serverURL);
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");

    String postData = "nitrogen=" + String(n) + "&phosphorus=" + String(p) + "&potassium=" + String(k);
    int httpCode = http.POST(postData);

    if (httpCode > 0) {
      String response = http.getString();
      Serial.println("POST Response: " + response);
    } else {
      Serial.println("POST Request failed.");
    }

    http.end();
  } else {
    Serial.println("WiFi disconnected.");
  }
}
