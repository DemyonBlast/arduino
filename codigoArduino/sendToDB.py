import serial
import time
import pymysql.cursors
import re

ser = serial.Serial(
    port='COM3',\
    baudrate=9600,\
    parity=serial.PARITY_NONE,\
    stopbits=serial.STOPBITS_ONE,\
    bytesize=serial.EIGHTBITS,\
        timeout=0)

print("connected to: " + ser.portstr)

#database connection
connection  = pymysql.connect(host='localhost', port=3306, user='root', passwd='', db='sensores')

#connection.cursor=db.cursor()#cursor object using cursor() method


#this will store the line
seq = []
values=[]
count = 0
cur = connection.cursor()
while True:
    for c in ser.read():
        seq.append(chr(c)) #convert from ANSII
        joined_seq = ''.join(str(v) for v in seq) #Make a string from array
        #print(joined_seq)
        #print("flag1")
        if chr(c) == '\n':
           # print("flag2")
            seqSize=len(joined_seq)
            temp, hum = joined_seq[:5], joined_seq[5:]
            try:
               # print("flag3")
                values=list(re.findall(r"(\d+(?:\.\d+)?)", joined_seq))
                with connection.cursor() as cursor:
                    sqlHum="INSERT INTO sensor (id,tipoID_fk, id_arduino_fk, valores) VALUES (1,1,1,%s);"
                    cur.execute(sqlHum, values[0])# Execute the SQL command
                    
                    sqlLux="INSERT INTO sensor (id,tipoID_fk, id_arduino_fk, valores) VALUES (2,5,1,%s);"
                    cur.execute(sqlLux, values[1])# Execute the SQL command
                    
                    sqlLpg="INSERT INTO sensor (id,tipoID_fk, id_arduino_fk, valores) VALUES (3,3,1,%s);"
                    cur.execute(sqlLpg, values[2])# Execute the SQL command
                    
                    sqlCo="INSERT INTO sensor (id,tipoID_fk, id_arduino_fk, valores) VALUES (4,6,1,%s);"
                    cur.execute(sqlCo, values[3])# Execute the SQL command
                    
                    sqlSmoke="INSERT INTO sensor (id,tipoID_fk, id_arduino_fk, valores) VALUES (5,7,1,%s);"
                    cur.execute(sqlSmoke, values[4])# Execute the SQL command
                    
                    sqlUv="INSERT INTO sensor (id,tipoID_fk, id_arduino_fk, valores) VALUES (6,2,1,%s);"
                    cur.execute(sqlUv, values[5])# Execute the SQL command
                    
                    sqlTemp="INSERT INTO sensor (id,tipoID_fk, id_arduino_fk, valores) VALUES (7,4,1,%s);"
                    cur.execute(sqlTemp, values[6])# Execute the SQL command
                    
                    sqlPressure="INSERT INTO sensor (id,tipoID_fk, id_arduino_fk, valores) VALUES (8,8,1,%s);"
                    cur.execute(sqlPressure, values[7])# Execute the SQL command
                    
                    sqlSeaLevelPressure="INSERT INTO sensor (id,tipoID_fk, id_arduino_fk, valores) VALUES (9,9,1,%s);"
                    cur.execute(sqlSeaLevelPressure, values[8])# Execute the SQL command
                    
                    sqlAltitude="INSERT INTO sensor (id,tipoID_fk, id_arduino_fk, valores) VALUES (10,10,1,%s);"
                    cur.execute(sqlAltitude, values[9])# Execute the SQL command
                                        
                    connection.commit()# Commit your changes in the database
                    #print("flag4")
                    #print (values)
                    count += 1
                    seq = []
                    values=[]
                    break
            except ValueError:
                print("Oops!  That was no valid number.  Try again...")
        #print("flag5")
# disconnect from server
cur.close()
connection.close()
ser.close()


















