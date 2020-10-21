import mysql.connector
from mysql.connector import errorcode
import time
import board
import busio
import adafruit_ads1x15.ads1115 as ADS
from adafruit_ads1x15.analog_in import AnalogIn

#Cria barramento I2C
i2c = busio.I2C(board.SCL, board.SDA)

#Cria objeto ADC usando o barramento I2C
ads = ADS.ADS1115(i2c)

#CONEXAO COM O BANCO DE DADOS
try:
    db_connection = mysql.connector.connect(host='localhost', user='root', password='123', database='automatizacao_aerador')
except mysql.connector.Error as error:
    if error.errno == errorcode.ER_BAD_DB_ERROR:
        print("Banco não existe!")
    elif error.errno == errorcode.ER_ACCESS_DENIED_ERROR:
        print("User name ou password incorretos!")
    else:
        print(error)
mycursor = db_connection.cursor()

def calibrar_sonda():
    voltage = 0.0
    chan = AnalogIn(ads, ADS.P1)  #Le a porta P1 do ADS
    
    print('Calibrando Sonda O.D....')
    
    #Faz a media de 1000 leituras
    for i in range(1000):
        voltage += chan.voltage*1000
    voltagem_calibrada = voltage/1000
    
    print('Sonda O.D calibrada em %.2f' %(voltagem_calibrada), 'mV\n')
    return voltagem_calibrada

#while True:
def capturar_saturacao(voltagem_calibrada):
    voltage = 0.0
    voltagem_media = 0.0
    chan = AnalogIn(ads, ADS.P1)   #Le a porta P1 do ADS
    time.sleep(2.0)  
     
    #Faz a media de 1000 leituras
    for i in range(1000):
        voltage += chan.voltage*1000
    voltagem_media = voltage/1000

    #Formula para calcular a percentagem de saturacao
    percentagem_saturacao = (voltagem_media * 100.0)/voltagem_calibrada

    #Salva a Voltagem Media para fins de Analises do Desenvolvedor
    sql = "INSERT INTO voltagem(valor, dataHora) VALUES (%s, CURRENT_TIMESTAMP)"
    value = (voltagem_media,)
    mycursor.execute(sql, value)
    db_connection.commit()
    
    return percentagem_saturacao
