import os
import glob
import time
import math
 
os.system('modprobe w1-gpio')
os.system('modprobe w1-therm')
 
base_dir = '/sys/bus/w1/devices/'
device_folder = glob.glob(base_dir + '28*')[0]
device_file = device_folder + '/w1_slave'
 
def read_temp_raw():
    f = open(device_file, 'r')
    lines = f.readlines()
    f.close()
    return lines
 
def ler_temperatura():
    lines = read_temp_raw()
    while lines[0].strip()[-3:] != 'YES':
        time.sleep(0.2)
        lines = read_temp_raw()
    equals_pos = lines[1].find('t=')
    if equals_pos != -1:
        temp_string = lines[1][equals_pos+2:]
        temp_c = float(temp_string) / 1000.0
        return temp_c

def ler_temperatura_media():
    temperatura = 0.0
    
    for i in range(10):
        temperatura += ler_temperatura()
    
    temperaturaMedia = temperatura/10
    return temperaturaMedia

def arrendondar_temperatura(temperaturaMedia):
    parteDecimalTemperatura = round(temperaturaMedia % 1,2)
    
    if(parteDecimalTemperatura <= 0.3):
        temperaturaMedia = math.trunc(temperaturaMedia)
    elif(parteDecimalTemperatura >= 0.7):
        temperaturaMedia = math.trunc(temperaturaMedia) + 1
    else:
        temperaturaMedia = math.trunc(temperaturaMedia) + 0.5
    
    return temperaturaMedia
    
    