import RPi.GPIO as GPIO
import sys
import mysql.connector
from mysql.connector import errorcode
import time
import math
import leituraSondaPH as ph
import leituraSondaOD as od
import leituraSondaTemperatura as temperatura

GPIO.setup(21, GPIO.OUT)

#CONEXAO COM O BANCO DE DADOS
try:
    db_connection = mysql.connector.connect(host='localhost', user='root', password='123', database='automatizacao_aerador')
    print("\nBanco de Dados conectado!")
except mysql.connector.Error as error:
    if error.errno == errorcode.ER_BAD_DB_ERROR:
        print("Banco não existe!")
    elif error.errno == errorcode.ER_ACCESS_DENIED_ERROR:
        print("User name ou password incorretos!")
    else:
        print(error)
mycursor = db_connection.cursor()

#VERIFICA SE SONDA OXIGENIO ESTA CALIBRADA
sql = "SELECT valorCalibrado FROM calibracao_sonda_od"
mycursor.execute(sql)
valorCalibrado = mycursor.fetchall()

if(valorCalibrado == []):
    print("\nFalta calibrar sensor OD\nExecute o calibracaoSondaOD.py !!!")
    sys.exit()      
else:
    print("\nSonda OD calibrada. Valor Calibrado: ", valorCalibrado[0][0])
    sondaODNaoCalibrada = False

aeradorLigado = 0
estadoAnteriorAerador = 0
intervaloLeitura = 1800.0 #30 min
while True:
    #LÊ SENDOR DE OD (PERCENTAGEM SATURACAO)
    percentagemSaturacaoOxigenio = od.capturar_saturacao(valorCalibrado[0][0])
    print('Porce. Saturacao: %.2f' %(percentagemSaturacaoOxigenio))
    time.sleep(5.0)
            
    #LÊ SENSOR DE PH
    valorPH = ph.capturar_ph()
    print('PH = %.2f' %(valorPH))
    time.sleep(5.0)
           
    #LÊ SENSOR DE TEMPETARURA 
    temperaturaMedia = temperatura.ler_temperatura_media()   
    temperaturaFinal = temperatura.arrendondar_temperatura(temperaturaMedia)
        
    #BUSCA SATURACAO OXIGENCIO NA TABELA DE TEMPERATURA_SATURACAO
    sql = "SELECT saturacao_oxigenio FROM temperatura_saturacao where temperatura = %s"
    values = (temperaturaFinal,)
    mycursor.execute(sql, values)
    resultadoSQL = mycursor.fetchall()
    saturacaoOxigenio = resultadoSQL[0][0]
    print('Temperatura: ', temperaturaFinal, 'Saturacao: ', saturacaoOxigenio)
    
    #CALCULA OXIGENIO DISSOLVIDO
    oxigenioDissolvido = (percentagemSaturacaoOxigenio / 100) * saturacaoOxigenio
    oxigenioDissolvido = round(oxigenioDissolvido, 2)
    
    #SALVA NO BANCO OS VALORES DE OD,PH E TEMPERAURA
    sql = "INSERT INTO dado_obtido_sensor(dataHora, valorObtidoSonda, idSensor) VALUES (CURRENT_TIMESTAMP, %s, %s)"
    values = [(oxigenioDissolvido, 1), (valorPH, 2), (temperaturaFinal, 3)]
    mycursor.executemany(sql, values)
    db_connection.commit()
        
    print("\nOxigenio:", oxigenioDissolvido)
        
    #SETA VALOR PARA AERADOR LIGADO OU NAO
    if oxigenioDissolvido <= 4.5 and aeradorLigado == 0:
        #SALVA NA TABELA AERADOR O HORARIO QUE O AERADOR FOI ATIVADO
        sql = "INSERT INTO aerador(dataHoraInicio, dataHoraFim) VALUE (CURRENT_TIMESTAMP, NULL)"
        mycursor.execute(sql)
        db_connection.commit()
       
        intervaloLeitura = 600.0 #10 min
        #SETA 1 NA PORTA GPIO21 PARA QUE O AERADOR SEJA ATIVADO
        aeradorLigado = 1
        GPIO.output(21,1)
    elif oxigenioDissolvido > 4.5 and aeradorLigado == 1:
        #BUSCA NA TABELA AERADOR O ULTIMO REGISTRO DO AERADOR LIGADO PARA SALVAR A HORA DE FIM DE EXECUCAO
        sql = "SELECT idAerador FROM aerador where dataHoraFim is null ORDER BY idAerador DESC LIMIT 1"
        mycursor.execute(sql)
        resultadoSQL = mycursor.fetchall()
        idAerador = resultadoSQL[0][0]
     
        #SALVA NA TABELA AERADOR O HORARIO QUE O AERADOR FOI DESATIVADO
        sql = "UPDATE aerador SET dataHoraFim = CURRENT_TIMESTAMP WHERE idAerador = %s"
        values = (idAerador,)            
        mycursor.execute(sql, values)
        db_connection.commit()     
        
        intervaloLeitura = 1800.0 #10 min
        #SETA 0 NA PORTA GPIO12 PARA QUE O AERADOR SEJA DESATIVADO
        aeradorLigado = 0
        GPIO.output(21,0)
        
    print('Aerador Ligado: ', aeradorLigado)
    print('\n\n')
    time.sleep(intervaloLeitura) #30 min
    #time.sleep(60.0) #1 min