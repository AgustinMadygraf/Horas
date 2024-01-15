import os
import pandas as pd
from sqlalchemy import create_engine
from sqlalchemy.exc import IntegrityError

def clear_screen():
    # para windows
    if os.name == 'nt':
        os.system('cls')
    # para mac y linux(here, os.name is 'posix')
    else:
        os.system('clear')

clear_screen()
# Obtener el directorio actual del script y la carpeta de archivos CSV
directorio_actual = os.path.dirname(os.path.abspath(__file__))
directorio_CSV = os.path.join(directorio_actual, "CSV")

# Obtener credenciales de las variables de entorno
usuario = os.environ.get('DB_USER')
contraseña = os.environ.get('DB_PASS')
host = os.environ.get('DB_HOST')
base_de_datos = os.environ.get('DB_NAME')
tabla = 'registro_horas_trabajo'

# Cadena de conexión
cadena_conexion = f"mysql+mysqlconnector://{usuario}:{contraseña}@{host}/{base_de_datos}"

# Crear el motor de conexión a la base de datos
engine = create_engine(cadena_conexion)

# Listar todos los archivos CSV en el directorio de CSV
archivos_csv = [archivo for archivo in os.listdir(directorio_CSV) if archivo.endswith('.csv')]

if not archivos_csv:
    print("No se encontraron archivos CSV en la carpeta 'CSV'.")
    input("Presiona Enter para salir.")
    exit()

# Imprimir la lista de archivos encontrados
print("Archivos CSV encontrados:")
for archivo in archivos_csv:
    print(archivo)


# Procesar cada archivo CSV
for archivo in archivos_csv:
    print(f"Procesando archivo: {archivo}...")

    ruta_completa = os.path.join(directorio_CSV, archivo)
    df = pd.read_csv(ruta_completa, delimiter=';')
    print(f"\n df: {df}")
    df_largo = df.melt(id_vars=['legajo'], var_name='fecha', value_name='horas_trabajadas')
    print(f"\n df_largo:")

    df_largo['fecha'] = pd.to_datetime(df_largo['fecha'], format='%d/%m/%Y').dt.date
    print (df_largo)
    
    df_largo.to_sql(tabla, con=engine, if_exists='append', index=False)

    print(f"Datos de {archivo} insertados en la base de datos.")
