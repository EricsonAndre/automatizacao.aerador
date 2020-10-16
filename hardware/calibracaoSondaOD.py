import mysql.connector
import sys
from mysql.connector import errorcode
import leituraSondaOD as od

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

valorCalibrado = 0.0
sondaODNaoCalibrada = True
while (sondaODNaoCalibrada):
    #BUSCA NO BANCO UMA CALIBRACAO DE OD CADASTRADA
    sql = "SELECT valorCalibrado FROM calibracao_sonda_od"
    mycursor.execute(sql)
    resultadoSQL = mycursor.fetchall()
   
    #VERIFICA SE SONDA OXIGENIO ESTA CALIBRADA
    if(resultadoSQL == []):
        sondaODNaoCalibrada = True
        confirm = input('\nGostaria de Calibrar agora? [s/n] ')
    
        if confirm in ['S', 's', '']:
            sondaODNaoCalibrada = False
            
            print("\nCalibrando sonda...")
            valorCalibrado = od.calibrar_sonda()
                        
            sql = "INSERT INTO calibracao_sonda_od(valorCalibrado) VALUES (%s)"
            value = (valorCalibrado,)
            mycursor.execute(sql, value)
            db_connection.commit()
    else:
        sondaODNaoCalibrada = False
        valorCalibrado = resultadoSQL[0][0]
        
        confirm = input('\nSonda OD calibrada. Gostaria de Recalibrar? [s/n] ')
    
        if confirm in ['S', 's', '']:
            print("\nCalibrando sonda...")
            valorCalibrado = od.calibrar_sonda()
                        
            #BUSCA NA TABELA DE CALIBRACAO O ULTIMO REGISTRO
            sql = "SELECT MAX(idCalibracao) FROM calibracao_sonda_od"
            mycursor.execute(sql)
            resultadoSQL = mycursor.fetchall()
            idCalibracao = resultadoSQL[0][0]
            
            #FAZ UM UPDATE NO VALOR CALIBRADO DA SONDA DE OD
            sql = "UPDATE calibracao_sonda_od SET valorCalibrado = %s WHERE idCalibracao = %s"
            values = (valorCalibrado, idCalibracao)
            mycursor.execute(sql, values)
            db_connection.commit()              
        
    print('\nValor Calibrado: ', valorCalibrado)

sys.exit()    