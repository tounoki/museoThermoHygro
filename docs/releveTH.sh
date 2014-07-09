#!/bin/bash

RECORD=$(/home/pi/adafruit/Adafruit-Raspberry-Pi-Python-Code-master/Adafruit_DHT_Driver/Adafruit_DHT 22 4 | grep Temp)

while [[ $RECORD != Temp* ]]
do
        sleep 2
        RECORD=$(/home/pi/adafruit/Adafruit-Raspberry-Pi-Python-Code-master/Adafruit_DHT_Driver/Adafruit_DHT 22 4 | grep $
done

T=`expr match "$RECORD" '\(.* \*C\)'`
T=${T#T*=}
T=${T%\*C}

echo $T

H=`expr match "$RECORD" '.*\(Hum.*\)'`
H=${H#H*=}
H=${H%\%}

echo $H

D=$(date +%Y-%m-%d\ %T)

echo $D

mysql -uroot -pPASSWORD -h127.0.0.1 -e "INSERT INTO thermo.measures (ID, dateAndTime, temperature, hygrometry, device_id) VALUES (NULL,'$D',$T,$H,1);"

exit 0

