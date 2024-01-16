
# SYSTEM

## Contexto del Proyecto
Este prompt está diseñado para ser utilizado en conjunto con la estructura de directorios y archivos de un proyecto de software, enfocándose en el desarrollo y diseño UX/UI. Será aplicado por modelos de lenguaje de gran escala como ChatGPT, Google Bard, BlackBox, etc., para proporcionar análisis y recomendaciones de mejora.

## Objetivo
El objetivo es analizar un proyecto de software para identificar áreas específicas donde aplicar mejores prácticas de programación, diseño UX/UI, y técnicas de machine learning para optimización y automatización. Tendrás que prestar atención al archivo REAMDE.md

# USER

### Pasos para la Mejora del Proyecto
1. **Análisis Automatizado del Proyecto:**
   - Realizar una revisión  de la estructura de directorios y archivos, y contenido del proyecto utilizando pruebas automáticas y análisis de rendimiento.

2. **Identificación de Áreas de Mejora con Machine Learning:**
   - Utilizar algoritmos de machine learning para identificar patrones de errores comunes, optimización de rendimiento y áreas clave para mejoras.

3. **Sugerencias Específicas y Refactorización:**
   - Proporcionar recomendaciones detalladas y automatizadas para las mejoras identificadas, incluyendo sugerencias de refactorización y optimización.

4. **Plan de Acción Detallado con Retroalimentación:**
   - Desarrollar un plan de acción con pasos específicos, incluyendo herramientas y prácticas recomendadas.
   - Implementar un sistema de retroalimentación para ajustar continuamente el proceso de mejora basándose en el uso y rendimiento.

5. **Implementación y Evaluación Continua:**
   - Indicar archivos o componentes específicos para mejoras.
   - Evaluar el impacto de las mejoras y realizar ajustes basándose en retroalimentación continua.

### Consideraciones para la Mejora
- **Desarrollo de Software:**
   - Examinar estructura de archivos, logging, código duplicado, ciberseguridad, nomenclatura y prácticas de codificación.
   - Incorporar pruebas automáticas y análisis de rendimiento.

- **Diseño UX/UI:**
   - Enfocarse en accesibilidad, estética, funcionalidad y experiencia del usuario.

- **Tecnologías Utilizadas:**
   - El proyecto utiliza Python, PHP, HTML, MySQL, JavaScript y CSS. Las recomendaciones serán compatibles con estas tecnologías.

- **Automatización y Machine Learning:**
   - Implementar pruebas automáticas y algoritmos de machine learning para detectar y sugerir mejoras.
   - Utilizar retroalimentación para ajustes continuos y aprendizaje colectivo.

- **Documentación y Conocimiento Compartido:**
   - Mantener una documentación detallada de todos los cambios y mejoras para facilitar el aprendizaje y la mejora continua.



## Estructura de Carpetas y Archivos
```bash
horas/
    actualizar_centro.php
    centro_costo.php
    centro_costo_logic.php
    database.php
    DatabaseManager.php
    GestorHoras.php
    helpers.php
    horas_1.sql
    horas_2.sql
    importar_datos_horas_trabajo.py
    index.php
    index_test.php
    insertar_centro.php
    legajo.php
    mostrar_horas.php
    procesar.php
    README.md
    AMIS/
        00-Prompt-for-ProjectAnalysis.md
        01-ProjectAnalysis.md
        02-ToDoList.md
        03-MySQL.md
    CSS/
        header.css
    CSV/
        config.php
    includes/
        config.php
        db.php
    templates/
        footer.php
        header.php
```


## Contenido de Archivos Seleccionados

### C:\AppServ\www\horas\actualizar_centro.php
```plaintext
<?php
//actualizar\_centro.php
include 'templates/header.php'; 
require\_once 'includes/db.php';
require\_once 'legajo.php';
require\_once 'helpers.php'; // Asegúrate de que este archivo contenga la función obtenerNombreCentroCosto

// Preparar la consulta SQL
$sql = "SELECT \* FROM registro\_horas\_trabajo WHERE legajo = ? AND horas\_trabajadas > 1 AND centro\_costo IS NOT NULL  ORDER BY fecha ASC";

// Preparar la sentencia
$stmt = $conexion->prepare\($sql\);

// Vincular parámetros
$stmt->bind\_param\("s", $legajo\);

// Ejecutar la sentencia
$stmt->execute\(\);

// Obtener los resultados
$resultado = $stmt->get\_result\(\);

// Cerrar la sentencia
$stmt->close\(\);

// Comenzar el HTML
echo "<\!DOCTYPE html><html><head><title>Registro de Horas</title></head><body>";

// Verificar si hay resultados y mostrarlos
if \($resultado->num\_rows > 0\) {
    echo "<table border='1'>
            <tr>
                <th>Legajo</th>
                <th>Fecha</th>
                <th>Hor

as</th>
                <th>Centro de costo anterior</th>
                <th>Centro de costo actual</th>
                <th></th>
            </tr>";

    while\($fila = $resultado->fetch\_assoc\(\)\) {
        echo "<tr>
                <td>".$fila\["legajo"\]."</td>
                <td>".$fila\["fecha"\]."</td>  
                <td>".$fila\["horas\_trabajadas"\]."</td>
                <td>".obtenerNombreCentroCosto\($fila\["centro\_costo"\]\)."</td>
                <td>
                    <form action='procesar.php' method='GET'>
                        <select name='centro\_costo'>
                            <option value=''></option>    
                            <option value='1'>Maquina de bolsas</option>
                            <option value='2'>Boletas y folletería</option>
                            <option value='3'>Logistica</option>
                            <option value='4'>Administración</option>
                            <option value='5'>Club</option>
                            <option value='6'>Mantenimiento</option>
                            <option value='7'>Comedor</option>
                            <option value='8'>Guardia</option>
                            <option value='9'>Sistemas</option>
                            <option value='10'>Enfermería</option>
                        </select>
                        <input type='hidden' name='legajo' value='".$fila\["legajo"\]."'>
                        <input type='hidden' name='fecha' value='".$fila\["fecha"\]."'>
                        <input type='submit' value='Actualizar'>
                    </form>
                </td>
                <td>".$fila\["proceso"\]."</td>
              </tr>";
    }

    echo "</table>";
} else {
    echo "No se encontraron resultados.";
}

// Finalizar el HTML
echo "</body></html>";

// Cerrar la conexión
$conexion->close\(\);

```

### C:\AppServ\www\horas\centro_costo.php
```plaintext
<?php
include 'templates/header.php'; 
require\_once 'includes/db.php';
require\_once 'helpers.php'; // Asegúrate de que este archivo incluya la función obtenerNombreCentroCosto

// Preparar la consulta SQL utilizando consultas preparadas
$sql = "SELECT COALESCE\(centro\_costo, 'Sin Asignar'\) AS centro\_costo, SUM\(horas\_trabajadas\) AS total\_horas FROM registro\_horas\_trabajo GROUP BY COALESCE\(centro\_costo, 'Sin Asignar'\) ORDER BY \`total\_horas\` DESC";

// Preparar la sentencia
$stmt = $conexion->prepare\($sql\);

// Ejecutar la sentencia
$stmt->execute\(\);

// Obtener los resultados
$resultado = $stmt->get\_result\(\);

// Cerrar la sentencia
$stmt->close\(\);

$totalHoras = 0;
$datosGrafico = \[\["Centro de Costo", "Horas"\]\];

if \($resultado\) {
    while \($fila = $resultado->fetch\_assoc\(\)\) {
        $totalHoras += $fila\["total\_horas"\];
        $nombreCentro = obtenerNombreCentroCosto\($fila\["centro\_costo"\]\);
        array\_push\($datosGrafico, \[$nombreCentro, \(float\)$fila\["total\_horas"\]\]\);
    }
}

$datosJson = json\_encode\($datosGrafico\);

// Comenzar el HTML y el resto del código...

echo "<\!DOCTYPE html><html><head>
      <title>Total Horas por Centro de Costo</title>
      <script type='text/javascript' src='https://www.gstatic.com/charts/loader.js'></script>
      <script type='text/javascript'>
          google.charts.load\('current', {'packages':\['corechart'\]}\);
          google.charts.setOnLoadCallback\(function\(\) {
              drawChart\($datosJson\);
          }\);
          function drawChart\(dataArray\) {
              var data = google.visualization.arrayToDataTable\(dataArray\);
              var options = {
                  title: 'Total Horas por Centro de Costo'
              };
              var chart = new google.visualization.PieChart\(document.getElementById\('piechart'\)\);
              chart.draw\(data, options\);
          }
      </script>
      </head><body>";

// Mostrar tabla de datos
echo "<table border='1'>
      <tr>
          <th>Centro de Costo</th>
          <th>Horas</th>
          <th>Porcentaje \[%\]</th>
      </tr>";

$resultado->data\_seek\(0\); // Rebobinar para usar de nuevo el resultado

if \($resultado && $resultado->num\_rows > 0\) {
    while \($fila = $resultado->fetch\_assoc\(\)\) {
        $porcentaje = $fila\["total\_horas"\] / $totalHoras \* 100;
        echo "<tr>
                <td>". obtenerNombreCentroCosto\($fila\["centro\_costo"\]\). "</td>
                <td>". $fila\["total\_horas"\]. "</td>
                <td>". number\_format\($porcentaje, 2\). "%</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "No se encontraron resultados.";
}

echo "<div id='piechart' style='width: 900px; height: 500px;'></div>";
echo "</body></html>";
$conexion->close\(\);

```

### C:\AppServ\www\horas\centro_costo_logic.php
```plaintext
<?php
require\_once 'includes/db.php';
require\_once 'helpers.php';

function obtenerDatosCentroCosto\(\) {
    global $conexion;

    // Preparar la consulta SQL utilizando consultas preparadas
    $sql = "SELECT COALESCE\(centro\_costo, 'Sin Asignar'\) AS centro\_costo, SUM\(horas\_trabajadas\) AS total\_horas FROM registro\_horas\_trabajo GROUP BY COALESCE\(centro\_costo, 'Sin Asignar'\) ORDER BY \`total\_horas\` DESC";

    // Preparar la sentencia
    $stmt = $conexion->prepare\($sql\);

    // Ejecutar la sentencia
    $stmt->execute\(\);

    // Obtener los resultados
    $resultado = $stmt->get\_result\(\);

    // Cerrar la sentencia
    $stmt->close\(\);

    $datosGrafico = \[\["Centro de Costo", "Horas"\]\];
    $totalHoras = 0;

    if \($resultado\) {
        while \($fila = $resultado->fetch\_assoc\(\)\) {
            $totalHoras += $fila\["total\_horas"\];
            $nombreCentro = obtenerNombreCentroCosto\($fila\["centro\_costo"\]\);
            array\_push\($datosGrafico, \[$nombreCentro, \(float\)$fila\["total\_horas"\]\]\);
        }
    }

    // Cerrar la conexión
    $conexion->close\(\);

    return \[
        'datosGrafico' => $datosGrafico,
        'totalHoras' => $totalHoras
    \];
}

```

### C:\AppServ\www\horas\database.php
```plaintext
<?php
require\_once 'path/to/env.php'; // Asegúrate de incluir tu archivo de variables de entorno

class Database {
    private $host;
    private $db\_name;
    private $username;
    private $password;
    public $conn;

    public function \_\_construct\(\) {
        $this->host = getenv\('DB\_HOST'\);
        $this->db\_name = getenv\('DB\_NAME'\);
        $this->username = getenv\('DB\_USER'\);
        $this->password = getenv\('DB\_PASS'\);
    }

    public function getConnection\(\) {
        $this->conn = null;

        try {
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db\_name . ";charset=utf8";
            $this->conn = new PDO\($dsn, $this->username, $this->password, \[
                PDO::ATTR\_ERRMODE => PDO::ERRMODE\_EXCEPTION,
                PDO::ATTR\_EMULATE\_PREPARES => false
            \]\);
        } catch\(PDOException $exception\) {


            // Registra el error en un archivo de log y muestra un mensaje genérico al usuario
            error\_log\($exception->getMessage\(\)\); // Asegúrate de que el archivo de log no sea accesible públicamente
            die\("Ocurrió un error al conectar con la base de datos."\);
        }

        return $this->conn;
    }
}


```

### C:\AppServ\www\horas\DatabaseManager.php
```plaintext
<?php
// DatabaseManager.php

class DatabaseManager {
    private $conexion;

    public function \_\_construct\($servername, $username, $password, $dbname\) {
        $this->conexion = new mysqli\($servername, $username, $password, $dbname\);

        if \($this->conexion->connect\_error\) {
            die\("Conexión fallida: " . $this->conexion->connect\_error\);
        }
    }

    public function obtenerInformacionAsociados\(\) {
        $sql = "SELECT \* FROM informacion\_asociados";
        $stmt = $this->conexion->prepare\($sql\);
        $stmt->execute\(\);
        $resultado = $stmt->get\_result\(\);
        $stmt->close\(\);

        return $resultado;
    }

    public function close\(\) {
        $this->conexion->close\(\);
    }
}

```

### C:\AppServ\www\horas\GestorHoras.php
```plaintext
<?php
#Gestor\_horas.php
include 'templates/header.php'; 
require\_once 'includes/db.php';
require\_once 'legajo.php';

// Obtener el legajo desde el parámetro GET
$legajo = isset\($\_GET\['legajo'\]\) ? $\_GET\['legajo'\] : '';

// Verificar si el legajo no está vacío
if \(\!empty\($legajo\)\) {
    $sql = "SELECT \* FROM registro\_horas\_trabajo WHERE legajo = ? AND horas\_trabajadas > 1 ORDER BY fecha ASC";
} else {
    $sql = "SELECT \* FROM registro\_horas\_trabajo WHERE horas\_trabajadas > 1 ORDER BY fecha ASC";
}

// Preparar la sentencia
$stmt = $conexion->prepare\($sql\);

if \(\!empty\($legajo\)\) {
    // Vincular parámetros para la consulta con legajo
    $stmt->bind\_param\("s", $legajo\);
}

// Ejecutar la sentencia
$stmt->execute\(\);

// Obtener los resultados
$resultado = $stmt->get\_result\(\);

// Cerrar la sentencia
$stmt->close\(\);

// Comenzar el HTML
echo "<\!DOCTYPE html><html><head><title>Registro de Horas</title></head><body>";
function obtenerNombreCentroCosto\($codigo\) {
    $nombresCentroCosto = \[
        '1' => 'Maquina de bolsas',
        '2' => 'Boletas y folletería',
        '3' => 'Logistica',
        '4' => 'Administración',
        '5' => 'Club',
        '6' => 'Mantenimiento',
        '7' => 'Comedor',
        '8' => 'Guardia',
        '9' => 'Sistemas',
        '10' => 'Enfermería',

    \];

    return isset\($nombresCentroCosto\[$codigo\]\) ? $nombresCentroCosto\[$codigo\] : 'Desconocido';
}


// Verificar si hay resultados y mostrarlos
if \($resultado->num\_rows > 0\) {
            // Convertir la fecha a día de la semana
            $dia = date\('l', strtotime\($fila\["fecha"\]\)\); // 'l' devuelve el día completo en inglés, p.ej., "Monday"

            // Traducir el día al español
            $diasEnEspañol = \[
                'Monday'    => 'Lunes',
                'Tuesday'   => 'Martes',
                'Wednesday' => 'Miércoles',
                'Thursday'  => 'Jueves',
                'Friday'    => 'Viernes',
                'Saturday'  => 'Sábado',
                'Sunday'    => 'Domingo',
            \];
            $diaEnEspañol = isset\($diasEnEspañol\[$dia\]\) ? $diasEnEspañol\[$dia\] : 'Desconocido';
    
    echo "<table border='1'>
            <tr>
                <th>Legajo</th>
                <th>Fecha</th>
                <th>Día</th>
                <th>Horas</th>
                <th>Centro de costo</th>
                <th>Proceso</th>
            </tr>";
    while\($fila = $resultado->fetch\_assoc\(\)\) {
        echo "<tr>
                <td>".$fila\["legajo"\]."</td>
                <td>".$fila\["fecha"\]."</td>  
                <td>".$diaEnEspañol."</td>  
                <td>".$fila\["horas\_trabajadas"\]."</td><td>";
                echo obtenerNombreCentroCosto\($fila\["centro\_costo"\]\);
                echo "</td><td>".$fila\["proceso"\]."</td> </tr>";
    }
    echo "</table>";
} else {
    echo "No se encontraron resultados.";
}


// Finalizar el HTML
echo "</body></html>";

// Cerrar la conexión
$conexion->close\(\);

```

### C:\AppServ\www\horas\helpers.php
```plaintext
<?php
function obtenerNombreCentroCosto\($codigo\) {
    $nombresCentroCosto = \[
        '1' => 'Maquina de bolsas',
        '2' => 'Boletas y folletería',
        '3' => 'Logistica',
        '4' => 'Administración',
        '5' => 'Club',
        '6' => 'Mantenimiento',
        '7' => 'Comedor',
        '8' => 'Guardia',
        '9' => 'Sistemas',
        '10' => 'Enfermería',
    \];

    return isset\($nombresCentroCosto\[$codigo\]\) ? $nombresCentroCosto\[$codigo\] : 'Desconocido';
}

```

### C:\AppServ\www\horas\horas_1.sql
```plaintext
INSERT INTO \`centro\` \(\`id\_centro\`, \`nombre\`, \`descripcion\`\) VALUES
\(8, '8', 'Guardia'\);
INSERT INTO \`informacion\_asociados\` \(\`id\_asociado\`, \`legajo\`, \`nombre\`, \`apellido\`\) VALUES
\(100, '1497', 'Eymy', 'Najarro'\);
INSERT INTO \`proceso\` \(\`id\_proceso\`, \`id\_centro\`, \`nombre\`, \`descripcion\`\) VALUES
\(13, 6, 'e', 'Efluentes'\);
```

### C:\AppServ\www\horas\importar_datos_horas_trabajo.py
```plaintext
import os
import pandas as pd
from sqlalchemy import create\_engine
from sqlalchemy.exc import IntegrityError

def clear\_screen\(\):
    # para windows
    if os.name == 'nt':
        os.system\('cls'\)
    # para mac y linux\(here, os.name is 'posix'\)
    else:
        os.system\('clear'\)

clear\_screen\(\)
# Obtener el directorio actual del script y la carpeta de archivos CSV
directorio\_actual = os.path.dirname\(os.path.abspath\(\_\_file\_\_\)\)
directorio\_CSV = os.path.join\(directorio\_actual, "CSV"\)

# Obtener credenciales de las variables de entorno
usuario = os.environ.get\('DB\_USER'\)
contraseña = os.environ.get\('DB\_PASS'\)
host = os.environ.get\('DB\_HOST'\)
base\_de\_datos = os.environ.get\('DB\_NAME'\)
tabla = 'registro\_horas\_trabajo'

# Cadena de conexión
cadena\_conexion = f"mysql+mysqlconnector://{usuario}:{contraseña}@{host}/{base\_de\_datos}"

# Crear el motor de conexión a la base de datos
engine = create\_engine\(cadena\_conexion\)

# Listar todos los archivos CSV en el directorio de CSV
archivos\_csv = \[archivo for archivo in os.listdir\(directorio\_CSV\) if archivo.endswith\('.csv'\)\]

if not archivos\_csv:
    print\("No se encontraron archivos CSV en la carpeta 'CSV'."\)
    input\("Presiona Enter para salir."\)
    exit\(\)

# Imprimir la lista de archivos encontrados
print\("Archivos CSV encontrados:"\)
for archivo in archivos\_csv:
    print\(archivo\)


# Procesar cada archivo CSV
for archivo in archivos\_csv:
    print\(f"Procesando archivo: {archivo}..."\)

    ruta\_completa = os.path.join\(directorio\_CSV, archivo\)
    df = pd.read\_csv\(ruta\_completa, delimiter=';'\)
    print\(f"\n df: {df}"\)
    df\_largo = df.melt\(id\_vars=\['legajo'\], var\_name='fecha', value\_name='horas\_trabajadas'\)
    print\(f"\n df\_largo:"\)

    df\_largo\['fecha'\] = pd.to\_datetime\(df\_largo\['fecha'\], format='%d/%m/%Y'\).dt.date
    print \(df\_largo\)
    
    df\_largo.to\_sql\(tabla, con=engine, if\_exists='append', index=False\)

    print\(f"Datos de {archivo} insertados en la base de datos."\)

```

### C:\AppServ\www\horas\index.php
```plaintext
<?php 
//index.php

include 'templates/header.php'; 
require\_once 'includes/db.php';
require\_once 'DatabaseManager.php';

// Crear una instancia de DatabaseManager
$dbManager = new DatabaseManager\($servername, $username, $password, $dbname\);

// Preparar la consulta SQL
$sql = "SELECT \* FROM informacion\_asociados ";

// Preparar la sentencia
//$stmt = $dbManager->conexion->prepare\($sql\); //no anda
$stmt = $conexion->prepare\($sql\);

// Ejecutar la sentencia
$stmt->execute\(\);

// Obtener los resultados
$resultado = $stmt->get\_result\(\);

// Cerrar la sentencia
$stmt->close\(\);

// Verificar si hay resultados y mostrarlos
if \($resultado->num\_rows > 0\) {
    echo "<table border='1'>
            <tr>
                <th>Legajo</th>
                <th>Nombre</th>
                <th>Apellido</th>
            </tr>";
    while\($fila = $resultado->fetch\_assoc\(\)\) {
        echo "<tr>
                <td><a href='mostrar\_horas.php?legajo=" . $fila\["legajo"\] . "'>" . $fila\["legajo"\] . "</a></td>
                <td>" . $fila\["nombre"\] . "</td> 
                <td>" . $fila\["apellido"\] . "</td> 
            </tr>";
    }
    echo "</table>";
} else {
    echo "No se encontraron resultados.";
}


// Cerrar la conexión
$dbManager->close\(\);

include 'templates/footer.php';

```

### C:\AppServ\www\horas\index_test.php
```plaintext
<?
// index.php

require\_once 'DatabaseManager.php';
require\_once 'includes/config.php'; // Asegúrate de que este archivo contenga las credenciales de la base de datos
include 'templates/header.php';

// Crear una instancia de DatabaseManager
$dbManager = new DatabaseManager\($servername, $username, $password, $dbname\);

// Obtener la información de asociados utilizando la función de la clase
$resultado = $dbManager->obtenerInformacionAsociados\(\);














// Verificar si hay resultados y mostrarlos
if \($resultado->num\_rows > 0\) {
    echo "<table border='1'>
            <tr>
                <th>Legajo</th>
                <th>Nombre</th>
                <th>Apellido</th>
            </tr>";
    while\($fila = $resultado->fetch\_assoc\(\)\) {
        echo "<tr>
                <td><a href='mostrar\_horas.php?legajo=" . $fila\["legajo"\] . "'>" . $fila\["legajo"\] . "</a></td>
                <td>" . $fila\["nombre"\] . "</td>
                <td>" . $fila\["apellido"\] . "</td>
            </tr>";
    }
    echo "</table>";
} else {
    echo "No se encontraron resultados.";
}

// Cerrar la conexión
$dbManager->close\(\);

include 'templates/footer.php';

```

### C:\AppServ\www\horas\insertar_centro.php
```plaintext
<?php
include 'templates/header.php'; 
require\_once 'includes/db.php';
require\_once 'legajo.php';




// Obtener el legajo desde el parámetro GET
$legajo = isset\($\_GET\['legajo'\]\) ? $\_GET\['legajo'\] : '';

// Preparar la consulta SQL
$sql = "SELECT \* FROM registro\_horas\_trabajo WHERE legajo = ? AND horas\_trabajadas > 1 AND centro\_costo IS NULL ORDER BY fecha ASC";

// Preparar la sentencia
$stmt = $conexion->prepare\($sql\);

// Vincular parámetros
$stmt->bind\_param\("s", $legajo\);

// Ejecutar la sentencia
$stmt->execute\(\);

// Obtener los resultados
$resultado = $stmt->get\_result\(\);

// Cerrar la sentencia
$stmt->close\(\);

// Comenzar el HTML
echo "<\!DOCTYPE html><html><head><title>Registro de Horas</title></head><body>";

if \($resultado->num\_rows > 0\) {
            // Convertir la fecha a día de la semana
            $dia = date\('l', strtotime\($fila\["fecha"\]\)\); // 'l' devuelve el día completo en inglés, p.ej., "Monday"

            // Traducir el día al español
            $diasEnEspañol = \[
                'Monday'    => 'Lunes',
                'Tuesday'   => 'Martes',
                'Wednesday' => 'Miércoles',
                'Thursday'  => 'Jueves',
                'Friday'    => 'Viernes',
                'Saturday'  => 'Sábado',
                'Sunday'    => 'Domingo',
            \];
            $diaEnEspañol = isset\($diasEnEspañol\[$dia\]\) ? $diasEnEspañol\[$dia\] : 'Desconocido';
    
    echo "<table border='1'>
            <tr>
                <th>Legajo</th>
                <th>Fecha</th>
                <th>Día</th>
                <th>Horas</th>
                <th>Centro de Costo</th>
                <th>Acción</th>
            </tr>";
    while \($fila = $resultado->fetch\_assoc\(\)\) {
        echo "<tr>
                <td>".$fila\["legajo"\]."</td>
                <td>".$fila\["fecha"\]."</td>  
                <td>".$diaEnEspañol."</td>  
                <td>".$fila\["horas\_trabajadas"\]."</td> 
                <form action='procesar.php' method='GET'>
                    <td>
                        <select name='centro\_costo'>
                            <option value=''></option>    
                            <option value='1'>Maquina de bolsas</option>
                            <option value='2'>Boletas y folletería</option>
                            <option value='3'>Logistica</option>
                            <option value='4'>Administración</option>
                            <option value='5'>Club</option>
                            <option value='6'>Mantenimiento</option>
                            <option value='7'>Comedor</option>
                            <option value='8'>Guardia</option>
                            <option value='9'>Sistemas</option>
                            <option value='10'>Enfermería</option>
                        </select> </td>  
                        <input type='hidden' name='legajo' value='".$fila\["legajo"\]."'>
                        <input type='hidden' name='fecha' value='".$fila\["fecha"\]."'>
                    <td><input type='submit' value='Guardar'></td>
                    </form>
                </td>
            </tr>";
    }
    echo "</table>";
} else {
    echo "No se encontraron resultados para agregar centro de costos.";
}

// Finalizar el HTML
echo "</body></html>";

// Cerrar la conexión
$conexion->close\(\);
?>


```

### C:\AppServ\www\horas\legajo.php
```plaintext
<?php
require\_once 'includes/db.php'; // Asegúrate de que la ruta al archivo db.php sea correcta

// Obtener el legajo desde el parámetro GET
$legajo = isset\($\_GET\['legajo'\]\) ? $\_GET\['legajo'\] : '';

// Verificar si el legajo no está vacío
if \(\!empty\($legajo\)\) {
    // Preparar la consulta SQL
    $sql = "SELECT \* FROM informacion\_asociados WHERE legajo = ?";
    
    // Preparar la sentencia
    if \($stmt = $conexion->prepare\($sql\)\) {
        // Vincular parámetros
        $stmt->bind\_param\("s", $legajo\);
        
        // Ejecutar la sentencia
        $stmt->execute\(\);

        // Obtener los resultados
        $resultado = $stmt->get\_result\(\);

        // Verificar si hay resultados
        if \($resultado->num\_rows > 0\) {
            // Iniciar la tabla
            echo "<table border='1'>";
            echo "<tr><th>Legajo</th><th>Nombre</th><th>Apellido</th></tr>"; // Encabezados de la tabla
        
            // Mostrar los resultados en filas de la tabla
            while \($fila = $resultado->fetch\_assoc\(\)\) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars\($fila\['legajo'\]\) . "</td>";
                echo "<td>" . htmlspecialchars\($fila\['nombre'\]\) . "</td>";
                echo "<td>" . htmlspecialchars\($fila\['apellido'\]\) . "</td>";
                echo "</tr>";
            }
        
            // Cerrar la tabla
            echo "</table><br>";
        } else {
            echo "No se encontraron resultados para el legajo: $legajo";
        }
        // Cerrar la sentencia
        $stmt->close\(\);
    } else {
        echo "Error al preparar la consulta: " . $conexion->error;
    }
} else {
    echo "Por favor, proporcione un legajo.<br>";
}



```

### C:\AppServ\www\horas\mostrar_horas.php
```plaintext
<?php
#mostrar\_horas.php
include 'templates/header.php'; 
require\_once 'includes/db.php';
require\_once 'legajo.php';


// Obtener el legajo desde el parámetro GET
$legajo = isset\($\_GET\['legajo'\]\) ? $\_GET\['legajo'\] : '';

// Verificar si el legajo no está vacío
if \(\!empty\($legajo\)\) { $sql = "SELECT \* FROM registro\_horas\_trabajo WHERE legajo = ? AND horas\_trabajadas > 1 ORDER BY fecha ASC";
}else {$sql = "SELECT \* FROM registro\_horas\_trabajo WHERE  horas\_trabajadas > 1 ORDER BY fecha ASC";
}



// Preparar la consulta SQL


// Preparar la sentencia
$stmt = $conexion->prepare\($sql\);

// Vincular parámetros
$stmt->bind\_param\("s", $legajo\);

// Ejecutar la sentencia
$stmt->execute\(\);

// Obtener los resultados
$resultado = $stmt->get\_result\(\);

// Cerrar la sentencia
$stmt->close\(\);

// Comenzar el HTML
echo "<\!DOCTYPE html><html><head><title>Registro de Horas</title></head><body>";
function obtenerNombreCentroCosto\($codigo\) {
    $nombresCentroCosto = \[
        '1' => 'Maquina de bolsas',
        '2' => 'Boletas y folletería',
        '3' => 'Logistica',
        '4' => 'Administración',
        '5' => 'Club',
        '6' => 'Mantenimiento',
        '7' => 'Comedor',
        '8' => 'Guardia',
        '9' => 'Sistemas',
        '10' => 'Enfermería',

    \];

    return isset\($nombresCentroCosto\[$codigo\]\) ? $nombresCentroCosto\[$codigo\] : 'Desconocido';
}


// Verificar si hay resultados y mostrarlos
if \($resultado->num\_rows > 0\) {
            // Convertir la fecha a día de la semana
            $dia = date\('l', strtotime\($fila\["fecha"\]\)\); // 'l' devuelve el día completo en inglés, p.ej., "Monday"

            // Traducir el día al español
            $diasEnEspañol = \[
                'Monday'    => 'Lunes',
                'Tuesday'   => 'Martes',
                'Wednesday' => 'Miércoles',
                'Thursday'  => 'Jueves',
                'Friday'    => 'Viernes',
                'Saturday'  => 'Sábado',
                'Sunday'    => 'Domingo',
            \];
            $diaEnEspañol = isset\($diasEnEspañol\[$dia\]\) ? $diasEnEspañol\[$dia\] : 'Desconocido';
    
    echo "<table border='1'>
            <tr>
                <th>Legajo</th>
                <th>Fecha</th>
                <th>Día</th>
                <th>Horas</th>
                <th>Centro de costo</th>
                <th>Proceso</th>
            </tr>";
    while\($fila = $resultado->fetch\_assoc\(\)\) {
        echo "<tr>
                <td>".$fila\["legajo"\]."</td>
                <td>".$fila\["fecha"\]."</td>  
                <td>".$diaEnEspañol."</td>  
                <td>".$fila\["horas\_trabajadas"\]."</td><td>";
                echo obtenerNombreCentroCosto\($fila\["centro\_costo"\]\);
                echo "</td><td>".$fila\["proceso"\]."</td> </tr>";
    }
    echo "</table>";
} else {
    echo "No se encontraron resultados.";
}


// Finalizar el HTML
echo "</body></html>";

// Cerrar la conexión
$conexion->close\(\);
?>

```

### C:\AppServ\www\horas\procesar.php
```plaintext
<?php
//procesar.php
require\_once 'includes/db.php';

// Verificar si los parámetros GET están establecidos y son válidos
if \(isset\($\_GET\['legajo'\], $\_GET\['centro\_costo'\], $\_GET\['fecha'\]\)\) {
    $legajo = $\_GET\['legajo'\];
    $centro\_costo = $\_GET\['centro\_costo'\];
    $fecha = $\_GET\['fecha'\];

    // Validar los datos aquí
    if \(\!preg\_match\('/^\d{1,4}$/', $legajo\)\) {
        die\("Legajo inválido."\);
    }
    if \(\!preg\_match\('/^\d{1,3}$/', $centro\_costo\)\) {
        die\("Centro de costo inválido."\);
    }
    if \(\!preg\_match\('/^\d{4}-\d{2}-\d{2}$/', $fecha\)\) {
        die\("Fecha inválida."\);
    }

    // Preparar la consulta SQL para actualizar
    $sql = "UPDATE registro\_horas\_trabajo SET centro\_costo = ? WHERE legajo = ? AND fecha = ?";

    // Preparar la sentencia
    if \($stmt = $conexion->prepare\($sql\)\) {
        // Vincular parámetros
        $stmt->bind\_param\("sss", $centro\_costo, $legajo, $fecha\);

        // Ejecutar la sentencia
        if \($stmt->execute\(\)\) {
            // Enviar un mensaje de confirmación a insertar\_centro.php
            header\("Location: insertar\_centro.php?legajo=$legajo&actualizado=1"\);
            exit;
        } else {
            echo "Error al actualizar el centro de costo: " . $conexion->error;
        }

        // Cerrar la sentencia
        $stmt->close\(\);
    } else {
        echo "Error al preparar la consulta: " . $conexion->error;
    }
} else {
    echo "Legajo, centro de costo o fecha no proporcionados.";
}

// Cerrar la conexión
$conexion->close\(\);



```

### C:\AppServ\www\horas\README.md
```plaintext
# Proyecto Horas

## Descripción
Breve descripción del Proyecto Horas, explicando su propósito y funcionalidad general.

## Cambios Recientes
- Implementación de variables de entorno para las credenciales de la base de datos.
- Optimización de consultas SQL en \`centro\_costo.php\`.
- Mejora de la usabilidad en dispositivos móviles en \`templates/header.php\`.
- Refactorización de la función \`obtenerNombreCentroCosto\` en un archivo separado \`helpers.php\`.

## Estructura del Proyecto
Descripción de la estructura principal del proyecto:
- \`templates/\`: Contiene archivos de plantillas HTML, como encabezados y pies de página.
- \`CSS/\`: Archivos CSS para estilizar la aplicación web.
- \`includes/\`: Scripts PHP comunes y configuraciones, incluyendo conexión a la base de datos.
- \`helpers.php\`: Funciones auxiliares usadas en todo el proyecto.
- \`centro\_costo.php\`, \`actualizar\_centro.php\`, etc.: Scripts PHP específicos para distintas funcionalidades del proyecto.

## Configuración
Instrucciones sobre cómo configurar y desplegar el proyecto:
1. Configuración de la base de datos.
2. Establecimiento de variables de entorno.
3. Pasos para desplegar el proyecto en un servidor local o remoto.

## Uso
Instrucciones básicas sobre cómo usar la aplicación, incluyendo cómo navegar por ella y realizar tareas comunes.

## Contribuir
Guía sobre cómo otros desarrolladores pueden contribuir al proyecto. Incluye instrucciones para clonar el repositorio, crear ramas, enviar pull requests, etc.

## Licencia
Información sobre la licencia bajo la cual se distribuye el proyecto.

---

© \[2024\] \[AgustinMadygraf\]

```

### C:\AppServ\www\horas\AMIS\01-ProjectAnalysis.md
```plaintext
## Análisis y Recomendaciones para el Proyecto "Horas"

### Contexto del Proyecto
El proyecto "Horas" es una aplicación web para la gestión de horas de trabajo, implicando una variedad de tecnologías como PHP, MySQL, HTML, CSS, JavaScript y Python.

### Análisis Automatizado del Proyecto

#### Estructura de Código y Prácticas de Codificación
- Evitar duplicación de código, especialmente en funciones comunes como \`obtenerNombreCentroCosto\(\)\`.
- Centralizar la conexión a la base de datos en un único archivo para mejorar la mantenibilidad.

#### Seguridad y Ciberseguridad
- Implementar variables de entorno para almacenar credenciales sensibles.
- Prevenir inyecciones SQL mediante el uso de consultas preparadas y validación de entradas.

#### Pruebas Automáticas y Análisis de Rendimiento
- Desarrollar pruebas unitarias y de integración para los scripts PHP y Python.
- Emplear herramientas de análisis de código para identificar y corregir posibles problemas de rendimiento y seguridad.

### Diseño UX/UI

#### Accesibilidad y Estética
- Utilizar un framework CSS como Bootstrap para mejorar la responsividad y coherencia en el diseño.
- Asegurar la accesibilidad web, verificando el contraste de colores y la navegabilidad con teclado.

#### Funcionalidad y Experiencia del Usuario
- Mejorar la navegación y el diseño interfaz para una experiencia de usuario más intuitiva y agradable.
- Implementar mensajes claros de confirmación o error para las acciones del usuario.

### Tecnologías Utilizadas
- Utilizar herramientas como Pylint para mejorar la calidad del código Python.
- Actualizar PHP a la última versión estable y minimizar y optimizar archivos JavaScript y CSS.

### Automatización y Machine Learning
- Implementar pruebas automáticas usando herramientas como PHPUnit para PHP.
- Explorar el uso de algoritmos de machine learning para análisis y predicción de datos de horas de trabajo.

### Documentación y Conocimiento Compartido
- Mantener una documentación actualizada y detallada del sistema.
- Desarrollar guías para usuarios finales y administradores del sistema.

### Plan de Acción Detallado con Retroalimentación
1. Realizar una auditoría de seguridad.
2. Refactorizar el código para mejorar la mantenibilidad.
3. Implementar pruebas automáticas.
4. Mejorar la experiencia del usuario.
5. Mantener y actualizar la documentación del proyecto.

### Consideraciones Adicionales
- Revisar los scripts de Python para manejo de excepciones y errores de conexión a la base de datos.
- Optimizar las consultas SQL en \`centro\_costo.php\`.
- Mejorar la responsividad y estética en \`header.css\`.
- Actualizar \`README.md\` para reflejar los cambios y configuraciones recientes.
- Considerar añadir índices en la base de datos para mejorar la velocidad de las consultas.
- Revisar la consistencia en la definición de tablas y el uso de AUTO\_INCREMENT.

### Conclusión
El proyecto "Horas" necesita mejoras en áreas como seguridad, eficiencia en el código, diseño UX/UI y documentación. La implementación de

# Análisis y Mejoras del Proyecto "Horas"

## Análisis del Proyecto
Tras revisar los detalles del proyecto "Horas", se identifican varias áreas clave para mejoras y optimización. El proyecto, centrado en la gestión de horas de trabajo, utiliza tecnologías como PHP, Python, MySQL, JavaScript, y CSS. A continuación, se presentan los hallazgos y sugerencias para cada área.

### Desarrollo de Software
- \*\*Refactorización de Código\*\*: Se observa código repetido, especialmente en PHP, como la función \`obtenerNombreCentroCosto\(\)\`. Se recomienda centralizar este tipo de funciones para reducir la duplicación.
- \*\*Seguridad\*\*: Implementar variables de entorno para almacenar información sensible, como credenciales de bases de datos, y asegurar la protección contra inyecciones SQL mediante consultas preparadas.

### Diseño UX/UI
- \*\*Responsive Design\*\*: Mejorar la experiencia del usuario en dispositivos móviles utilizando frameworks como Bootstrap.
- \*\*Accesibilidad\*\*: Verificar el contraste de colores y la navegabilidad para garantizar una mayor accesibilidad.

### Tecnologías
- \*\*Python\*\*: Uso de linters como Pylint para mejorar la calidad del código.
- \*\*PHP\*\*: Actualizar a la última versión estable y realizar pruebas unitarias y de integración.
- \*\*Optimización\*\*: Minimizar y optimizar JavaScript y CSS para reducir los tiempos de carga.

### Automatización y Machine Learning
- Implementar pruebas automáticas, como PHPUnit para PHP, y considerar el uso de Machine Learning para el análisis de datos.

### Documentación
- Mantener la documentación actualizada, incluyendo guías para usuarios y administradores, y asegurarse de que el código esté adecuadamente comentado.

## Plan de Acción Detallado
1. \*\*Auditoría de Seguridad\*\*: Identificar y corregir vulnerabilidades, especialmente en la gestión de la base de datos y la validación de entradas.
2. \*\*Refactorización del Código\*\*: Centralizar funciones comunes y optimizar la conexión a la base de datos.
3. \*\*Implementación de Pruebas Automáticas\*\*: Establecer pruebas para garantizar la calidad y fiabilidad del software.
4. \*\*Mejoras de Diseño UX/UI\*\*: Implementar un diseño más intuitivo y accesible.
5. \*\*Actualización y Mantenimiento de la Documentación\*\*: Reflejar los cambios realizados y ofrecer guías claras para usuarios y desarrolladores.

## Conclusión
El proyecto "Horas" presenta una base funcional sólida pero necesita mejoras en seguridad, eficiencia del código, experiencia de usuario y documentación. Las sugerencias proporcionadas tienen como objetivo fortalecer estos aspectos para un sistema más robusto y amigable.

### Primer Paso: Realizar una Auditoría de Seguridad
¿Deseas iniciar con la auditoría de seguridad o hay algún aspecto específico en el que te gustaría concentrarte primero?
```

### C:\AppServ\www\horas\AMIS\02-ToDoList.md
```plaintext
# To Do List

## database.php
- Situación: Finalizado
- Análisis del Ingeniero de Software: Implementar el uso de variables de entorno para las credenciales de la base de datos, mejorando la seguridad y evitando la exposición de datos sensibles en el código.

## actualizar\_centro.php
- Situación: Finalizado
- Análisis del Ingeniero de Software: Refactorizar el código para extraer la lógica común a una función o clase reutilizable, reduciendo la duplicidad y mejorando la mantenibilidad.

## importar\_datos\_horas\_trabajo.py
- Situación: Pendiente
- Análisis del Ingeniero de Software: Añadir manejo de excepciones más robusto para gestionar errores de conexión a la base de datos y errores durante la importación de datos.

## header.css
- Situación: Pendiente
- Análisis del Ingeniero de Software: Rediseñar para mejorar la responsividad y la estética, utilizando un framework CSS como Bootstrap.


## centro\_costo.php
- Situación: Finalizado
- Análisis del Ingeniero de Software: Optimizar las consultas SQL para mejorar el rendimiento y asegurar que se utilicen consultas preparadas para prevenir inyecciones SQL.

## templates/header.php
- Situación: En Proceso
- Análisis del Ingeniero de Software: Mejorar la usabilidad en dispositivos móviles asegurando que todos los elementos de navegación sean accesibles y visibles en pantallas pequeñas.

## README.md
- Situación: Pendiente
- Análisis del Ingeniero de Software: Actualizar la documentación para reflejar los cambios recientes y proporcionar una guía clara sobre la estructura del proyecto y su configuración.

## horas.sql
- Situación: Finalizado
- Análisis del Ingeniero de Software: Revisar y asegurar que las estructuras de las tablas y los índices estén optimizados para consultas eficientes.

## procesar.php
- Situación: En Proceso
- Análisis del Ingeniero de Software: Implementar validaciones de entrada para evitar datos incorrectos o maliciosos en las actualizaciones de la base de datos.

```

### C:\AppServ\www\horas\AMIS\03-MySQL.md
```plaintext


1. \*\*Índices en Tablas:\*\*
   - Asegúrate de que las columnas utilizadas frecuentemente en las cláusulas JOIN, WHERE y ORDER BY tengan índices para mejorar la velocidad de las consultas.
   - En la tabla \`registro\_horas\_trabajo\`, podrías considerar añadir índices a las columnas \`legajo\`, \`fecha\`, \`centro\_costo\` y \`proceso\`, si estas se utilizan a menudo en las consultas.

2. \*\*Tipos de Datos y Tamaños de Columna:\*\*
   - Revisa los tipos de datos y tamaños de las columnas para asegurarte de que son adecuados para los datos almacenados. Por ejemplo, para \`nombre\` y \`descripcion\` en las tablas \`centro\` y \`proceso\`, verifica si realmente necesitas \`varchar\(50\)\` y \`varchar\(100\)\` o si podrías optimizarlos.

3. \*\*Normalización:\*\*
   - Considera si tu esquema está normalizado adecuadamente para evitar la redundancia de datos. La normalización ayuda a mantener la integridad de los datos y reduce el almacenamiento.

4. \*\*Restricciones de Integridad:\*\*
   - Asegúrate de que todas las relaciones entre tablas estén correctamente definidas con restricciones de clave foránea, como ya lo has hecho con \`proceso\_ibfk\_1\`.

5. \*\*Almacenamiento de Fechas:\*\*
   - En lugar de tener columnas separadas para \`año\` y \`mes\` en \`registro\_horas\_trabajo\`, podrías considerar utilizar funciones de fecha en tus consultas SQL para extraer el año y el mes directamente de la columna \`fecha\`. Esto podría simplificar la estructura de tu tabla.

6. \*\*Uso de AUTO\_INCREMENT:\*\*
   - Verifica que el uso de AUTO\_INCREMENT en las claves primarias sea apropiado y que se ajuste a tus necesidades.

7. \*\*Documentación y Comentarios en el Script SQL:\*\*
   - Asegúrate de que tu script SQL esté bien documentado con comentarios que expliquen las decisiones de

diseño, especialmente para estructuras más complejas o para explicar la lógica detrás de ciertas decisiones de normalización o indexación.

8. \*\*Optimización para Consultas Específicas:\*\*
   - Si hay consultas que son particularmente críticas para el rendimiento de tu aplicación, considera optimizar la estructura de la base de datos específicamente para esas consultas. Esto podría incluir ajustes en la indexación o cambios en la estructura de la tabla.

9. \*\*Revisión de los Datos de Prueba:\*\*
   - Asegúrate de que los datos de prueba \(los valores insertados en las tablas\) sean representativos de los casos de uso reales. Esto te ayudará a probar más eficazmente el rendimiento y la funcionalidad de la base de datos.

10. \*\*Consistencia en la Definición de Tablas:\*\*
    - Mantén la consistencia en la definición de tablas, como el uso uniforme de tipos de datos, collations, y configuraciones de CHARSET. Esto es importante para la integridad y el rendimiento de la base de datos.

```

### C:\AppServ\www\horas\CSS\header.css
```plaintext

/\*-----------------------HEADER.CSS--------------\*/

div.content {
  margin-left: 250px;
  padding: 1px 16px;
}

@media screen and \(max-width: 750px\) {
  div.content {
    margin-left: 0;
    padding: 220px 16px;
  }
}

@media screen and \(max-width: 690px\) {
  div.content {
    padding: 1px 8px;
  }
}

@media screen and \(max-width: 680px\) {
  div.content {
    padding: 1px 6px;
  }
}

@media screen and \(max-width: 670px\) {
  div.content {
    padding: 1px 4px;
  }
}

@media screen and \(max-width: 660px\) {
  div.content {
    padding: 1px 2px;
  }
}



@media screen and \(max-width: 650px\) {
  div.content {
    padding: 1px 1px;
  }
}

/\*---------------------------TOPNAV--------------------------\*/

.topnav ul {
  list-style-type: none;
  margin: 0;
  padding: 0;
  overflow: hidden;
  background-color: #333;
  position: fixed;
  top: 0;
  width: 100%;
}

.topnav li {
  float: left;
  font-size: 16px;
}

.topnav li a {
  display: block;
  color: white;
  text-align: center;
  padding: 12px 8px;
  text-decoration: none;
}

.topnav li a:hover:not\(.active\) {
  background-color: #111;
}

.topnav .active {
  background-color: #4CAF50;
}

@media screen and \(max-width: 1275px\) {
  .topnav li {
    font-size: 15px;
  }
}

@media screen and \(max-width: 1200px\) {
  .topnav li {
    font-size: 14px;
  }
}

@media screen and \(max-width: 1150px\) {
  .topnav li {
    font-size: 13px;
  }
}

@media screen and

\(max-width: 1100px\) {
  .topnav li {
    font-size: 12px;
  }
}

@media screen and \(max-width: 1075px\) {
  .topnav li {
    font-size: 11px;
  }
}

@media screen and \(max-width: 1010px\) {
  .topnav li {
    font-size: 10px;
  }
}

@media screen and \(max-width: 995px\) {
  .topnav li {
    font-size: 9px;
  }
}

/\* Estilos para alertas \*/
.alert-danger {
  color: #721c24;
  background-color: #f8d7da;
  border-color: #f5c6cb;
}

.alert-danger hr {
  border-top-color: #f1b0b7;
}

.alert-danger .alert-link {
  color: #491217;
}
```

### C:\AppServ\www\horas\CSV\config.php
```plaintext
<?php
define\('DB\_SERVER', 'localhost'\);
define\('DB\_USERNAME', 'root'\);
define\('DB\_PASSWORD', '12346578'\);
define\('DB\_NAME', 'horas'\);
?>

```

### C:\AppServ\www\horas\includes\config.php
```plaintext
<?php
$servername = "localhost";
$username = "root";
$password = "12345678";
$dbname = "horas";

```

### C:\AppServ\www\horas\includes\db.php
```plaintext
<?php
require\_once 'config.php';

// Crear conexión
$conexion = new mysqli\($servername, $username, $password, $dbname\);

// Verificar conexión
if \($conexion->connect\_error\) {
    die\("Conexión fallida: " . $conexion->connect\_error\);
}
?>

```

### C:\AppServ\www\horas\templates\footer.php
```plaintext
<footer>
    <\!-- Contenido del pie de página -->
</footer>
</body>
</html>

```

### C:\AppServ\www\horas\templates\header.php
```plaintext
<?php 
//header.php

$legajo = isset\($\_GET\['legajo'\]\) ? $\_GET\['legajo'\] : '';



echo '<\!DOCTYPE html><html><head>     <meta charset="UTF-8"> <link rel="stylesheet" type="text/css" href="CSS/index.css"> <link rel="stylesheet" type="text/css" href="CSS/header.css"> <meta name="viewport" content="width=device-width, initial-scale=1.0"> <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon"> <link rel="icon" href="/imagenes/favicon.ico" type="image/x-icon"> <title>Registro de Horas</title></head><body>';
echo "<header> <br><br><br>    <div class='topnav'> <ul>";
echo "<li><a href='index.php' >Inicio</a></li>";
echo "<li><a href='insertar\_centro.php?legajo=".$legajo."'>Insertar</a></li>";
echo "<li><a href='mostrar\_horas.php?legajo=".$legajo."'>Visualizar</a></li>";
echo "<li><a href='actualizar\_centro.php?legajo=".$legajo."'>Actualizar</a></li>";
echo "<li><a href='centro\_costo.php'>Centro de Costos</a></li>";
echo "<li><a href='/phpMyAdmin/' target='\_blank'>Visit the AppServ Open Project</a></li>";
echo "</ul></div></header>"
?>
```
