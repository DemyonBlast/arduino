  #include "DHT.h" /*Temperature/humidity Lib*/
#include <Adafruit_TSL2561_U.h> /*Luminosity Lib*/
#include <Adafruit_BMP085.h> /*Barometer Lib*/
#include "Adafruit_Sensor.h" /* Sensors Lib*/
/* Luminosity
   
   Connections:

   Connect SCL to analog 5
   Connect SDA to analog 4
   Connect VDD to 3.3V DC
   Connect GROUND to common ground

   UV Sensor
   Connections:
   3.3V = 3.3V
   OUT = A0
   GND = GND
   EN = 3.3V
   3.3V = A1

  Atmospheric Pressure
  
  Connections:
  
  VCC = 3.3V
  GND =GND
  SCL 
  SDA
EOC is not used, it signifies an end of conversion
XCLR is a reset pin, also not used here*/

#define DHTPIN A0 /*Temperature/humidity Pin*/
#define DHTTYPE DHT11 /*Temperature/humidity sensor type*/
Adafruit_TSL2561_Unified tsl = Adafruit_TSL2561_Unified(TSL2561_ADDR_FLOAT, 12345);
#define  CALIBARAION_SAMPLE_TIMES 10     /*nº of samples from calibration*/
#define  CALIBRATION_SAMPLE_INTERVAL 500 /*time beetween samples while calibrating*/
#define  READ_SAMPLE_INTERVAL 10         /*nº of samples in a regular operation*/
#define  READ_SAMPLE_TIMES 5             /*time beetween  each sample reading*/
#define  GAS_LPG 0                      
#define  GAS_CO  1
#define  GAS_SMOKE 2
#define  RL_VALUE 5                       /*Resistence value of the board in kohms*/
#define  RO_CLEAN_AIR_FACTOR 9.83         /*RO_CLEAR_AIR_FACTOR=(Sensor resistence in clean air)/RO(datasheet value) */

/* -------------------------------------- Calibration variables of GAS Sensor MQ2 ----------------------------------------------------*/
float  LPGCurve[3] = {2.3,0.21,-0.47};                                                    
float COCurve[3] = {2.3,0.72,-0.34};  
float SmokeCurve[3] = {2.3,0.53,-0.44};                                               

Adafruit_BMP085 bmp; /*Barometer lib variable*/
DHT dht(DHTPIN, DHTTYPE); /*Temperature/humidity lib variable*/
#define PINO_SENSOR_MQ2  A5 /*GAS Pin variable*/
float resultadoMQ2 = 0;   /* returns funcaoGASeFUMO()*/
int smoke=0;
int lpg=0; /*Liquefied petroleum gas*/
int co=0; /*Carbon dioxide*/
float Ro = 10; /*10 kohms*/
int UVOUT = A4; /*UV Sensor Output*/
int REF_3V3 = A1; /*3.3V power variable for UV sensor*/

/*--------------------------------------Gas Sensor MQCalibration --------------------------------------
Input:   PINO_SENSOR_MQ2 - Analog Pin
Output:  sensor's Ro
 This function uses MQResistanceCalculation to calculate the value of clean air resistence dividing it by RO_CLEAN_AIR_FACTOR. According to the
          datasheet the RO_CLEAN_AIR_FACTOR 10, it will be different for other sensors
*/ 
float MQCalibration(int PINO_SENSOR_MQ2){
      int i;
      float val=0;
      for (i=0;i<CALIBARAION_SAMPLE_TIMES;i++) {            
          val += MQResistanceCalculation(analogRead(PINO_SENSOR_MQ2));
          delay(CALIBRATION_SAMPLE_INTERVAL);
      }
      val = val/CALIBARAION_SAMPLE_TIMES;
      val = val/RO_CLEAN_AIR_FACTOR;         
      return val;                                    
}              

/*--------------------------------------Gas Sensor  MQRead --------------------------------------
Input:   mq_pin - Analog Pin
Output:  Rs - sensor resistence
  Uses function MQResistanceCalculation to calculate the value of the sensors resistence*/ 
  
float MQRead(int PINO_SENSOR_MQ2){
    int i;
    float rs=0;
    for (i=0;i<READ_SAMPLE_TIMES;i++) {
        rs += MQResistanceCalculation(analogRead(PINO_SENSOR_MQ2));
        delay(READ_SAMPLE_INTERVAL);
    }
    rs = rs/READ_SAMPLE_TIMES;
    return rs;  
}
 
/*--------------------------------------Gas Sensor  MQGetGasPercentage --------------------------------------
Input:   rs_ro_ratio
         gas_id  
Output:  ppm do gas tipo
Calculates the percentage of gas in ppm*/ 

int MQGetGasPercentage(float rs_ro_ratio, int gas_id){
      if ( gas_id == GAS_LPG ) {
            return MQGetPercentage(rs_ro_ratio,LPGCurve);
      } else if ( gas_id == GAS_CO ) {
            return MQGetPercentage(rs_ro_ratio,COCurve);
      } else if ( gas_id == GAS_SMOKE ) {
            return MQGetPercentage(rs_ro_ratio,SmokeCurve);
      }    
     
      return 0;
}
 
/*--------------------------------------Gas Sensor  MQGetPercentage--------------------------------------
Input:   rs_ro_ratio - Rs/Ro
Output:  gas type in ppm*/ 

int  MQGetPercentage(float rs_ro_ratio, float *pcurve){
      return (pow(10,( ((log(rs_ro_ratio)-pcurve[1])/pcurve[2]) + pcurve[0])));
}



/*--------------------------------------Gas Sensor MQResistanceCalculation --------------------------------------
Input:   raw_adc - valor de raw lido a partir de adc que representa a voltagem
Output:  O valor calculado da resistencia do sensor

The sensor and the resistance of the charge creates a tension division. Given the voltage through
the charge's resistance it is calculated the resistance of the sensor.*/ 

float MQResistanceCalculation(int raw_adc){
      return ( ((float)RL_VALUE*(1023-raw_adc)/raw_adc));
}
void configureSensor(void)/* Setup the luminosity sensor gain and integration time */
{
  /* You can also manually set the gain or enable auto-gain support */
  // tsl.setGain(TSL2561_GAIN_1X);      /* No gain ... use in bright light to avoid sensor saturation */
  // tsl.setGain(TSL2561_GAIN_16X);     /* 16x gain ... use in low light to boost sensitivity */
  tsl.enableAutoRange(true);            /* Auto-gain ... switches automatically between 1x and 16x */
  
  /* Changing the integration time gives you better sensor resolution (402ms = 16-bit data) */
  tsl.setIntegrationTime(TSL2561_INTEGRATIONTIME_13MS);      /* fast but low resolution */
  // tsl.setIntegrationTime(TSL2561_INTEGRATIONTIME_101MS);  /* medium resolution and speed   */
  // tsl.setIntegrationTime(TSL2561_INTEGRATIONTIME_402MS);  /* 16-bit data but slowest conversions */

  /* Update these values depending on what you've set above! */  
 /* Serial.println("------------------------------------");
  Serial.print  ("Gain:         "); Serial.println("Auto");
  Serial.print  ("Timing:       "); Serial.println("13 ms");
  Serial.println("------------------------------------");*/
}

/*Takes an average of readings on a given pin, returns the average*/
int averageAnalogRead(int pinToRead)
{
  byte numberOfReadings = 8;
  unsigned int runningValue = 0; 

  for(int x = 0 ; x < numberOfReadings ; x++)
    runningValue += analogRead(pinToRead);
  runningValue /= numberOfReadings;

  return(runningValue);  
}

/*The Arduino Map function but for floats*/
float mapfloat(float x, float in_min, float in_max, float out_min, float out_max)
{
  return (x - in_min) * (out_max - out_min) / (in_max - in_min) + out_min;
}


/*-------------------------------------------------------------Main-----------------------------------------------------------------------*/
void setup(void) {
  Serial.begin(9600);
  dht.begin(); /*Temperature/humidity sensor initialization*/
  tsl.begin();/*Luminosity sensor initialization*/
  configureSensor();/* Setup the luminosity sensor gain and integration time */
  sensors_event_t event;/* new Luminosity sensor event variable*/ 
  tsl.getEvent(&event);/* Get a new Luminosity sensor event */ 
  Ro = MQCalibration(PINO_SENSOR_MQ2);/*Gas sensor calibration*/
  bmp.begin(); /*Barometer sensor initialization*/
}
void loop(void) {
  float temp, hum;/*initialize variable for storing Temperature/humidity*/
  sensors_event_t event;/* new luminosity sensor event variable*/ 
  tsl.getEvent(&event);/* Get a new luminosity sensor event */ 
  temp=dht.readTemperature(); /*Temperature reading*/
  hum=dht.readHumidity(); /*percentage of humidity in the air*/
  delay(2000);

/*-------------------Temperature/humidity---------------------------*/
  char tempToStr[5];
  char humToStr[4];

  dtostrf(temp,4,2,tempToStr);/*Convert raw data into string*/
  dtostrf(hum,4,2,humToStr);/*Convert raw data into string*/

  Serial.print("hum");
  /*Serial.print(tempToStr);first one temp segundo hum*/
  Serial.print(humToStr);
  
/*-------------------Luminosity---------------------------*/

    Serial.print("lux"); Serial.print(event.light);


  /*-------------------Gas---------------------------*/
   lpg = (MQGetGasPercentage(MQRead(PINO_SENSOR_MQ2)/Ro,GAS_LPG) );
   co = (MQGetGasPercentage(MQRead(PINO_SENSOR_MQ2)/Ro,GAS_CO) );
   smoke = (MQGetGasPercentage(MQRead(PINO_SENSOR_MQ2)/Ro,GAS_SMOKE) );
   
   Serial.print("lpg");
   Serial.print(lpg);
   Serial.print("co");
   Serial.print(co);
   Serial.print("smoke");
   Serial.print(smoke);
   /*-------------------UV light---------------------------*/
  int uvLevel = averageAnalogRead(UVOUT);
  int refLevel = averageAnalogRead(REF_3V3);

  //Use the 3.3V power pin as a reference to get a very accurate output value from sensor
  float outputVoltage = 3.3 / refLevel * uvLevel;

  float uvIntensity = mapfloat(outputVoltage, 0.99, 2.8, 0.0, 15.0); //Convert the voltage to a UV intensity level

  Serial.print("uv");
  Serial.print(uvIntensity);

 /*-------------------Atmospheric Pressure---------------------------*/
    Serial.print("temp");
    Serial.print(bmp.readTemperature());
    
    Serial.print("pressure");
    Serial.print(bmp.readPressure());

    Serial.print("pressuresealevel");
    Serial.print(bmp.readSealevelPressure());

    Serial.print("altitude");
    Serial.println(bmp.readAltitude(101500));
    delay(10000);
}
/*------------------------------------------------------------------------------------------------------------------------------------*/










                                   



