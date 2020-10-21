import RPi.GPIO as GPIO
import time
import leituraSondaTemperatura as temperatura



GPIO.setmode(GPIO.BCM)
#CONEXAO COM O BANCO DE DADOS
while True:
    temperaturaMedia = temperatura.ler_temperatura_media()   
    temperaturaFinal = temperatura.arrendondar_temperatura(temperaturaMedia)
    print('Temperatura: ', temperaturaFinal);
    
    


