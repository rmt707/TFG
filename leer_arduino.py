import serial
import time
import mysql.connector
from datetime import datetime

arduino = serial.Serial("/dev/rfcomm0", 9600) #Conexion con el Arduino
arduino.flushInput()
bbdd = mysql.connector.connect(host="localhost", user="admin", passwd="telematica", database="sistema_de_telemetria")
time.sleep(2) #Espera 2 segundos para crear las conexiones
i = 1

while True:
    try:
        datosASCII = arduino.readline() #humedad, temperatura, sonido, llama
        valor = float(datosASCII)
        #print("Valor=",valor)
        #print("type=",type(valor))

        dt = datetime.now() #Fecha y hora actuales
        dia = dt.day
        mes = dt.month
        anyo = dt.year
        hora = dt.hour
        minutos = dt.minute
        segundos = dt.second        

        datos = [str(dia),str(mes),str(anyo),str(hora),str(minutos),str(segundos)]
        
        
        if(i == 1):
            valor = valor % 100
            datos.insert(6,valor)
            #print("valor insertado= ",valor)
            sql = "INSERT INTO humedad(dia, mes, anyo, hora, minutos, segundos, humedad) values (%s, %s, %s, %s, %s, %s, %s)"
        
        if(i == 2):
            datos.insert(6,valor)
            sql = "INSERT INTO temperatura(dia, mes, anyo, hora, minutos, segundos, temperatura) values (%s, %s, %s, %s, %s, %s, %s)"
            
        if(i == 3):
            datos.insert(6,valor)
            sql = "INSERT INTO sensor_sonido(dia, mes, anyo, hora, minutos, segundos, deteccion_sonido) values (%s, %s, %s, %s, %s, %s, %s)"
            
        if(i == 4):
            datos.insert(6,valor)
            sql = "INSERT INTO sensor_llama(dia, mes, anyo, hora, minutos, segundos, deteccion_llama) values (%s, %s, %s, %s, %s, %s, %s)"
        
        cursor1 = bbdd.cursor()
        cursor1.execute(sql,datos)
        bbdd.commit()
        
        if(i == 4):
            i = 0
        
        i = i + 1
    except KeyboardInterrupt:
        break
bbdd.close() #Fin de la comunicacion con la bbdd
arduino.close() #Fin de la comunicacion con el Arduino