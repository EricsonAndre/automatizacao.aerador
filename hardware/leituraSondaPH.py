import time
import board
import busio
import adafruit_ads1x15.ads1115 as ADS
from adafruit_ads1x15.analog_in import AnalogIn

#Cria barramento I2C
i2c = busio.I2C(board.SCL, board.SDA)

#Cria objeto ADC usando o barramento I2C
ads = ADS.ADS1115(i2c)

#while True:
def capturar_ph():
    volts = 0.0
    
    #Faz a media de 10 leituras (Quanto mais, mais preciso)
    for i in range(10):
        chan = AnalogIn(ads, ADS.P0) #Le a porta P0 do ADS
        volts += (chan.value * 4096 / 32768 / 1000) # CONVERTE DE DIGITAL PAR VOLTS
        time.sleep(5)
    voltagem_media = (volts/10)
    
    print('\nVoltagem Media pH: ', voltagem_media)
    
    return ((-5.7 * round(voltagem_media, 3))+21.44)   #Formula para calcular pH. 
