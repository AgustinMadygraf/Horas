#novus_usb_v0.py
import pymysql
import minimalmodbus
import serial.tools.list_ports
import time
import os
from datetime import datetime

seg = 1
def detect_serial_ports(device_description):
    available_ports = list(serial.tools.list_ports.comports())
    for port, desc, hwid in available_ports:
        if device_description in desc:
            return port
    return None

device_description = "DigiRail Connect"  
com_port = detect_serial_ports(device_description)

if com_port:
    print(f"Puerto {device_description} detectado: {com_port}\n")
else:
    device_description = "USB-SERIAL CH340"  
    com_port = detect_serial_ports(device_description)
    if com_port:
        print(f"Puerto detectado: {com_port}\n")
    else:
        print("No se detectaron puertos COM para tu dispositivo.")
        input("Presiona una tecla para salir")
        exit()


# Dirección del dispositivo Modbus (ajusta la dirección del dispositivo según tu configuración)
device_address = 1

# Entradas digitales
D1 = 70
D2 = 71
# Contador
HR_COUNTER1_LO = 22
HR_COUNTER1_HI = 23
HR_COUNTER2_LO = 24
HR_COUNTER2_HI = 25

# Configuración de la base de datos MySQL
db_config = {
    'host': 'localhost',
    'user': 'root',
    'password': '12345678',
    'db': 'novus'  # Base de datos y subíndice
}

# Función para verificar la conexión a la base de datos
def check_db_connection():
    try:
        connection = pymysql.connect(**db_config)
        return connection
    except Exception as e:
        print(f"Error de conexión a la base de datos: {e}")
        return None

# Función para leer una entrada digital
def read_digital_input(instrument, address):
    if instrument:
        try:
            result = instrument.read_bit(address, functioncode=2)
            return result
        except Exception as e:
            print(f"Error al leer entrada digital en registro {address}: {e}")
    return None

# Función para leer registros de alta resolución
def read_high_resolution_register(instrument, address_lo, address_hi):
    if instrument:
        try:
            value_lo = instrument.read_register(address_lo, functioncode=3)
            value_hi = instrument.read_register(address_hi, functioncode=3)
            return value_lo, value_hi
        except Exception as e:
            print(f"Error al leer registro de alta resolución en registros {address_lo} y {address_hi}: {e}")
    return None, None

# Función para actualizar registros en la base de datos
def update_database(connection, address, value, descripcion):
    if connection:
        try:
            with connection.cursor() as cursor:
                sql = f"UPDATE registros_modbus SET valor = {value} WHERE direccion_modbus = {address}"
                cursor.execute(sql)
                connection.commit()
                print(f"Registro actualizado: dirección {address}, {descripcion} valor {value}")
        except Exception as e:
            print(f"Error al actualizar el registro en la base de datos: {e}")


def insert_database(connection, fecha_ahora, HR_COUNTER1):
    if connection:
        try:
            with connection.cursor() as cursor:
                sql = f"INSERT INTO `maq_bolsas`( `unixtime`, `HR_COUNTER1`) VALUES ({fecha_ahora}, {HR_COUNTER1})"
                cursor.execute(sql)
                connection.commit()
                print(f"Registro Insertado: unixtime = {fecha_ahora}, HR_COUNTER1= {HR_COUNTER1} , timestamp = {datetime.fromtimestamp(fecha_ahora)}")
        except Exception as e:
            print(f"Error al insertar el registro en la base de datos: {e}")

while True:
    os.system('cls' if os.name == 'nt' else 'clear')

    # Realiza tus operaciones de lectura y actualización aquí.
    connection = check_db_connection()
    try:
        instrument = minimalmodbus.Instrument(com_port, device_address)
    except Exception as e:
        print("Error al configurar el puerto serie:", str(e))
        time.sleep(10)
        continue

    if connection:
        D1_state = read_digital_input(instrument, D1)
        HR_COUNTER1_lo, HR_COUNTER1_hi = read_high_resolution_register(instrument, HR_COUNTER1_LO, HR_COUNTER1_HI)
        if HR_COUNTER1_lo is not None and HR_COUNTER1_hi is not None:
            HR_COUNTER1 = HR_COUNTER1_lo + HR_COUNTER1_hi * 65536
        print(f"Puerto {device_description} detectado: {com_port}\n")


        if D1_state is not None:
            update_database(connection, D1, D1_state, descripcion="HR_INPUT1_STATE")

        if HR_COUNTER1_lo is not None and HR_COUNTER1_hi is not None:
            update_database(connection, HR_COUNTER1_LO, HR_COUNTER1_lo, descripcion="HR_COUNTER1_LO ")
            update_database(connection, HR_COUNTER1_HI, HR_COUNTER1_hi, descripcion="HR_COUNTER1_HI ")
            
        fecha_ahora = int(time.time())
        print(f"la hora es: {datetime.fromtimestamp(fecha_ahora)}")
        fecha_sig = ((int(time.time()) // 300 + 1) * 300)
        fecha_sig_formateada = datetime.fromtimestamp(fecha_sig)
        print(f"Próxima actualización a las {fecha_sig_formateada}")
        seg = fecha_sig - fecha_ahora
        seg_truncado = round(seg, 1)
        print(f"Tiempo para la siguiente actualización: {seg_truncado} segundos")
        fecha_ahora = round(fecha_ahora/300,1)*300


    if seg < 2:
        try:
            insert_database(connection, fecha_ahora, HR_COUNTER1)
            time.sleep(15)
        except Exception as e:  
            print("Error al insertar en la base de datos:", e)

    time.sleep(1)
