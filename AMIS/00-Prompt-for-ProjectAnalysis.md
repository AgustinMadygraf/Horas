
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
Horas/
    actualizar_centro.php
    centro_costo.php
    database.php
    helpers.php
    horas.sql
    importar_datos_horas_trabajo.py
    index.php
    insertar_centro.php
    legajo.php
    mostrar_horas.php
    novus_usb_v0.py
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

### C:\AppServ\www\Horas\actualizar_centro.php
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

### C:\AppServ\www\Horas\centro_costo.php
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

### C:\AppServ\www\Horas\database.php
```plaintext
<?php
class Database {
    private $host;
    private $db\_name;
    private $username;
    private $password;
    public $conn;


    public function getConnection\(\) {
        $this->conn = null;

        try {
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db\_name;
            $this->conn = new PDO\($dsn, $this->username, $this->password\);
            $this->conn->exec\("set names utf8"\);
        } catch\(PDOException $exception\) {
            echo "Error de conexión: " . $exception->getMessage\(\);
        }

        return $this->conn;
    }
}

```

### C:\AppServ\www\Horas\helpers.php
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
?>

```

### C:\AppServ\www\Horas\horas.sql
```plaintext
-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 02-01-2024 a las 19:07:46
-- Versión del servidor: 8.0.17
-- Versión de PHP: 7.3.10

SET SQL\_MODE = "NO\_AUTO\_VALUE\_ON\_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time\_zone = "+00:00";


/\*\!40101 SET @OLD\_CHARACTER\_SET\_CLIENT=@@CHARACTER\_SET\_CLIENT \*/;
/\*\!40101 SET @OLD\_CHARACTER\_SET\_RESULTS=@@CHARACTER\_SET\_RESULTS \*/;
/\*\!40101 SET @OLD\_COLLATION\_CONNECTION=@@COLLATION\_CONNECTION \*/;
/\*\!40101 SET NAMES utf8mb4 \*/;

--
-- Base de datos: \`horas\`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla \`centro\`
--

CREATE TABLE \`centro\` \(
  \`id\_centro\` int\(11\) NOT NULL,
  \`nombre\` varchar\(50\) DEFAULT NULL,
  \`descripcion\` varchar\(100\) DEFAULT NULL
\) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4\_0900\_ai\_ci;

--
-- Volcado de datos para la tabla \`centro\`
--

INSERT INTO \`centro\` \(\`id\_centro\`, \`nombre\`, \`descripcion\`\) VALUES
\(1, '1', 'Maquina de bolsas'\),
\(2, '2', 'Boletas y folleteria'\),
\(3, '3', 'Logistica'\),
\(4, '4', 'Administracion'\),
\(5, '5', 'Club'\),
\(6, '6', 'Mantenimiento'\),
\(7, '7', 'Comedor'\),
\(8, '8', 'Guardia'\);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla \`informacion\_asociados\`
--

CREATE TABLE \`informacion\_asociados\` \(
  \`id\_asociado\` int\(11\) NOT NULL,
  \`legajo\` varchar\(4\) DEFAULT NULL,
  \`nombre\` varchar\(20\) DEFAULT NULL,
  \`apellido\` varchar\(20\) DEFAULT NULL
\) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4\_0900\_ai\_ci;

--
-- Volcado de datos para la tabla \`informacion\_asociados\`
--

INSERT INTO \`informacion\_asociados\` \(\`id\_asociado\`, \`legajo\`, \`nombre\`, \`apellido\`\) VALUES
\(1, '107', 'Antonio', 'Lopez'\),
\(2, '238', 'Oreste Mariano', 'Montenegro'\),
\(3, '240', 'Julian', 'Ortiz'\),
\(4, '298', 'Jose Andres', 'Ponce'\),
\(5, '493', 'Eduardo', 'Ayala'\),
\(6, '532', 'Hugo', 'Vera'\),
\(7, '574', 'Daniel', 'Arriondo'\),
\(8, '591', 'Julio', 'Hidalgo'\),
\(9, '666', 'Gustavo', 'Medrano'\),
\(10, '835', 'Cristian German', 'Pena'\),
\(11, '852', 'Ramon', 'Zalazar'\),
\(12, '853', 'Pablo', 'Paz'\),
\(13, '857', 'Oscar', 'Bentancourt'\),
\(14, '932', 'Martin', 'Killing'\),
\(15, '962', 'Hector Javier', 'Ballesteros'\),
\(16, '970', 'Miguel Angel', 'Gomez'\),
\(17, '971', 'Fernando Ariel', 'Utrera'\),
\(18, '974', 'Facundo Matias', 'Gomez'\),
\(19, '986', 'Ariel Gustavo', 'Fernandez'\),
\(20, '1032', 'Rolando Hector', 'Falcon'\),
\(21, '1035', 'Luis Fernando', 'Serrano'\),
\(23, '1038', 'Cristian Fabian', 'Ferreyra'\),
\(24, '1046', 'Silverio', 'Sanchez'\),
\(25, '1047', 'Angel Diego Jose', 'Galeano'\),
\(26, '1050', 'Gerardo Gaston', 'Leguizamon'\),
\(27, '1056', 'Matias Osvaldo', 'Hug'\),
\(28, '1072', 'Damian Emilio', 'Conti'\),
\(29, '1081', 'Cristian Gabriel', 'Cañete'\),
\(30, '1083', 'Jorge Gabriel', 'Medina'\),
\(31, '1118', 'Sebastian Anibal', 'Arrascaeta'\),
\(32, '1122', 'Mauro Maximiliano', 'Zuccarotto'\),
\(33, '1129', 'Alcira Amalia', 'Landeira'\),
\(34, '1137', 'Enrique Emiliano', 'Diaz'\),
\(35, '1153', 'Sandro Ariel', 'Salazar'\),
\(36, '1163', 'Gustavo David', 'Brito'\),
\(37, '1189', 'Martin Andres', 'Arari'\),
\(38, '1202', 'Rodolfo Walter', 'Osuna'\),
\(39, '1216', 'Ruben Martin', 'Dirroco'\),
\(40, '1228', 'Adrian Israel', 'Mancilla'\),
\(41, '1236', 'Oscar', 'Velazco'\),
\(42, '1241', 'Jonathan Ezequiel', 'Ledesma'\),
\(43, '1244', 'Marcelo Adrian Elise', 'Almada'\),
\(44, '1245', 'Jonatan', 'Gue'\),
\(45, '1291', 'Jose Antonio', 'Zarate'\),
\(46, '1310', 'Abel Alejandro', 'Silva'\),
\(47, '1315', 'Armando Federico', 'Pignataro'\),
\(48, '1317', 'Emanuel Omar', 'Garzia'\),
\(49, '1320', 'Carlos Eduardo', 'Cardozo'\),
\(50, '1325', 'Rodrigo Cristian', 'Rosales Arias'\),
\(51, '1340', 'David Leonardo', 'Valera Vasquez'\),
\(52, '1346', 'Julio Dario', 'Almaraz'\),
\(53, '1347', 'Carlos Alberto', 'Lescano'\),
\(54, '1352', 'Ricardo German', 'Miño'\),
\(55, '1388', 'German Diego', 'Gassibe'\),
\(56, '1395', 'Marcelo Omar', 'Ortega'\),
\(57, '1397', 'Martin Ezequiel', 'Chaile'\),
\(58, '1404', 'Agustin Leonardo', 'Bustos'\),
\(59, '1406', 'Jimena', 'Caruso'\),
\(60, '1407', 'Vanina', 'Mancuso'\),
\(61, '1412', 'Maria del Carmen', 'Vallejos'\),
\(62, '1413', 'Juana Rosa', 'Laime'\),
\(63, '1414', 'Leandro', 'Quinzano'\),
\(64, '1415', 'Monica Patricia', 'Butiler'\),
\(65, '1417', 'Ingrid Geraldina', 'Alarcon'\),
\(66, '1418', 'Lucrecia Viviana', 'Borge'\),
\(67, '1420', 'Maria Celeste', 'Paz'\),
\(68, '1421', 'Anahi Daiana', 'Almada'\),
\(69, '1422', 'Erica Victoria', 'Gramajo'\),
\(70, '1423', 'Rocio Noemi', 'Fernandez'\),
\(71, '1424', 'Monica', 'Salazar'\),
\(72, '1425', 'Maria de los Angeles', 'Plett'\),
\(73, '1426', 'Maria Alejandra Leon', 'Cortellcubi'\),
\(74, '1428', 'Emiliana Hilda', 'Andrade'\),
\(75, '1430', 'Eliana Edith', 'Villanueva'\),
\(76, '1431', 'Norma Beatriz', 'Barrientos'\),
\(77, '1432', 'Maria Vanina', 'Reboredo'\),
\(78, '1441', 'Gabriela del Carmen', 'Vera'\),
\(79, '1442', 'Juan Domingo', 'Peralta'\),
\(80, '1446', 'Nicolas', 'Almarante'\),
\(81, '1456', 'Mariana Soledad', 'Hogas'\),
\(82, '1460', 'Roberto Cesar', 'Amador'\),
\(83, '1461', 'Hugo Ricardo', 'Santillan'\),
\(84, '1466', 'Martin', 'Gonzalez Rojas'\),
\(85, '1469', 'Jonatan Eduardo', 'Guereñu'\),
\(86, '1471', 'Homero Miguel', 'Agüero'\),
\(87, '1474', 'Noelia Anahi', 'Oviedo'\),
\(88, '1478', 'Jorge Claudio Jesus', 'Gomez'\),
\(89, '1481', 'Roberto Antonio', 'Torres'\),
\(90, '1482', 'Gustavo Orlando', 'Frias'\),
\(91, '1484', 'Arnaldo Ramon', 'Sanchez'\),
\(92, '1485', 'Lucas Gaston', 'Vera'\),
\(93, '1487', 'Franco Gabriel', 'Urquiza'\),
\(94, '1488', 'Maria Belen', 'Medina'\),
\(95, '1490', 'Mercedes Liliana', 'Fretes'\),
\(96, '1491', 'Claudia Ester', 'D´elelessis'\),
\(97, '1492', 'Cintia Mariela', 'Chaves'\),
\(98, '1495', 'Silvina Valeria', 'Castro'\),
\(99, '1496', 'Agustin', 'Frers'\),
\(100, '1497', 'Eymy', 'Najarro'\);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla \`proceso\`
--

CREATE TABLE \`proceso\` \(
  \`id\_proceso\` int\(11\) NOT NULL,
  \`id\_centro\` int\(11\) DEFAULT NULL,
  \`nombre\` varchar\(50\) DEFAULT NULL,
  \`descripcion\` varchar\(100\) DEFAULT NULL
\) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4\_0900\_ai\_ci;

--
-- Volcado de datos para la tabla \`proceso\`
--

INSERT INTO \`proceso\` \(\`id\_proceso\`, \`id\_centro\`, \`nombre\`, \`descripcion\`\) VALUES
\(1, 1, 'a', 'Confección de bolsas de papel'\),
\(2, 1, 'b', 'Impresión de bolsas de papel'\),
\(3, 1, 'c', 'Confeción y pegado manual de manijas'\),
\(4, 1, 'd', 'Ventas y Marketing de bolsas'\),
\(5, 2, 'a', 'Impresión'\),
\(6, 2, 'b', 'Encuadernación'\),
\(7, 2, 'c', 'Preimpresión'\),
\(8, 2, 'd', 'Despacho'\),
\(9, 6, 'a', 'Maquina de bolsas'\),
\(10, 6, 'b', 'Logistica'\),
\(11, 6, 'c', 'Energía'\),
\(12, 6, 'd', 'Servicios generales'\),
\(13, 6, 'e', 'Efluentes'\);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla \`registro\_horas\_trabajo\`
--

CREATE TABLE \`registro\_horas\_trabajo\` \(
  \`id\_registro\` int\(11\) NOT NULL,
  \`legajo\` varchar\(4\) DEFAULT NULL,
  \`fecha\` date DEFAULT NULL,
  \`año\` year\(4\) GENERATED ALWAYS AS \(year\(\`fecha\`\)\) STORED,
  \`mes\` int\(11\) GENERATED ALWAYS AS \(month\(\`fecha\`\)\) STORED,
  \`horas\_trabajadas\` decimal\(5,2\) DEFAULT NULL,
  \`centro\_costo\` varchar\(3\) DEFAULT NULL,
  \`proceso\` varchar\(3\) CHARACTER SET utf8mb4 COLLATE utf8mb4\_0900\_ai\_ci DEFAULT NULL
\) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4\_0900\_ai\_ci;

--
-- Volcado de datos para la tabla \`registro\_horas\_trabajo\`
--

INSERT INTO \`registro\_horas\_trabajo\` \(\`id\_registro\`, \`legajo\`, \`fecha\`, \`horas\_trabajadas\`, \`centro\_costo\`, \`proceso\`\) VALUES
\(1, '107', '2023-11-01', '0.00', NULL, NULL\),
\(2, '238', '2023-11-01', '8.00', '1', NULL\),
\(3, '240', '2023-11-01', '7.60', NULL, NULL\),
\(4, '298', '2023-11-01', '8.00', NULL, NULL\),
\(5, '493', '2023-11-01', '6.20', NULL, NULL\),
\(6, '532', '2023-11-01', '0.00', NULL, NULL\),
\(7, '574', '2023-11-01', '8.00', '1', NULL\),
\(8, '591', '2023-11-01', '0.00', NULL, NULL\),
\(9, '666', '2023-11-01', '0.00', NULL, NULL\),
\(10, '835', '2023-11-01', '0.00', NULL, NULL\),
\(11, '852', '2023-11-01', '0.00', NULL, NULL\),
\(12, '853', '2023-11-01', '8.20', NULL, NULL\),
\(13, '857', '2023-11-01', '0.00', NULL, NULL\),
\(14, '932', '2023-11-01', '0.00', NULL, NULL\),
\(15, '962', '2023-11-01', '8.00', NULL, NULL\),
\(16, '970', '2023-11-01', '8.00', NULL, NULL\),
\(17, '971', '2023-11-01', '0.00', NULL, NULL\),
\(18, '974', '2023-11-01', '0.00', NULL, NULL\),
\(19, '986', '2023-11-01', '0.00', '1', NULL\),
\(20, '1032', '2023-11-01', '0.00', NULL, NULL\),
\(21, '1035', '2023-11-01', '8.00', NULL, NULL\),
\(22, '1038', '2023-11-01', '8.00', NULL, NULL\),
\(23, '1046', '2023-11-01', '8.00', '3', NULL\),
\(24, '1047', '2023-11-01', '8.00', NULL, NULL\),
\(25, '1050', '2023-11-01', '8.00', NULL, NULL\),
\(26, '1056', '2023-11-01', '8.90', NULL, NULL\),
\(27, '1072', '2023-11-01', '4.50', NULL, NULL\),
\(28, '1081', '2023-11-01', '0.00', NULL, NULL\),
\(29, '1083', '2023-11-01', '6.30', '1', NULL\),
\(30, '1118', '2023-11-01', '0.00', NULL, NULL\),
\(31, '1122', '2023-11-01', '8.00', NULL, NULL\),
\(32, '1129', '2023-11-01', '8.00', NULL, NULL\),
\(33, '1137', '2023-11-01', '0.00', NULL, NULL\),
\(34, '1153', '2023-11-01', '7.50', NULL, NULL\),
\(35, '1163', '2023-11-01', '0.00', NULL, NULL\),
\(36, '1189', '2023-11-01', '7.00', NULL, NULL\),
\(37, '1202', '2023-11-01', '9.00', NULL, NULL\),
\(38, '1216', '2023-11-01', '6.20', NULL, NULL\),
\(39, '1228', '2023-11-01', '0.00', NULL, NULL\),
\(40, '1236', '2023-11-01', '0.00', NULL, NULL\),
\(41, '1241', '2023-11-01', '0.00', NULL, NULL\),
\(42, '1244', '2023-11-01', '7.30', NULL, NULL\),
\(43, '1245', '2023-11-01', '8.00', NULL, NULL\),
\(44, '1291', '2023-11-01', '8.00', NULL, NULL\),
\(45, '1310', '2023-11-01', '7.40', NULL, NULL\),
\(46, '1315', '2023-11-01', '0.00', NULL, NULL\),
\(47, '1317', '2023-11-01', '8.00', NULL, NULL\),
\(48, '1320', '2023-11-01', '5.10', NULL, NULL\),
\(49, '1325', '2023-11-01', '8.00', NULL, NULL\),
\(50, '1340', '2023-11-01', '8.00', NULL, NULL\),
\(51, '1346', '2023-11-01', '8.00', NULL, NULL\),
\(52, '1347', '2023-11-01', '8.00', NULL, NULL\),
\(53, '1352', '2023-11-01', '0.00', NULL, NULL\),
\(54, '1388', '2023-11-01', '7.30', NULL, NULL\),
\(55, '1395', '2023-11-01', '0.00', NULL, NULL\),
\(56, '1397', '2023-11-01', '7.50', NULL, NULL\),
\(57, '1404', '2023-11-01', '0.00', '1', NULL\),
\(58, '1406', '2023-11-01', '8.00', NULL, NULL\),
\(59, '1407', '2023-11-01', '5.10', NULL, NULL\),
\(60, '1412', '2023-11-01', '8.00', NULL, NULL\),
\(61, '1413', '2023-11-01', '8.00', NULL, NULL\),
\(62, '1414', '2023-11-01', '0.00', NULL, NULL\),
\(63, '1415', '2023-11-01', '6.00', NULL, NULL\),
\(64, '1417', '2023-11-01', '0.00', NULL, NULL\),
\(65, '1418', '2023-11-01', '0.00', '4', NULL\),
\(66, '1420', '2023-11-01', '8.00', '1', NULL\),
\(67, '1421', '2023-11-01', '9.30', '4', NULL\),
\(68, '1422', '2023-11-01', '7.00', '4', NULL\),
\(69, '1423', '2023-11-01', '0.00', '8', NULL\),
\(70, '1424', '2023-11-01', '8.00', NULL, NULL\),
\(71, '1425', '2023-11-01', '8.00', '1', NULL\),
\(72, '1426', '2023-11-01', '0.00', NULL, NULL\),
\(73, '1428', '2023-11-01', '8.00', NULL, NULL\),
\(74, '1430', '2023-11-01', '0.00', NULL, NULL\),
\(75, '1431', '2023-11-01', '8.00', NULL, NULL\),
\(76, '1432', '2023-11-01', '8.00', NULL, NULL\),
\(77, '1441', '2023-11-01', '8.40', NULL, NULL\),
\(78, '1442', '2023-11-01', '7.00', NULL, NULL\),
\(79, '1446', '2023-11-01', '0.00', NULL, NULL\),
\(80, '1456', '2023-11-01', '8.00', NULL, NULL\),
\(81, '1460', '2023-11-01', '5.20', NULL, NULL\),
\(82, '1461', '2023-11-01', '8.70', NULL, NULL\),
\(83, '1466', '2023-11-01', '0.00', NULL, NULL\),
\(84, '1469', '2023-11-01', '8.00', NULL, NULL\),
\(85, '1471', '2023-11-01', '8.00', NULL, NULL\),
\(86, '1474', '2023-11-01', '10.00', NULL, NULL\),
\(87, '1478', '2023-11-01', '9.00', NULL, NULL\),
\(88, '1481', '2023-11-01', '5.70', NULL, NULL\),
\(89, '1482', '2023-11-01', '5.80', NULL, NULL\),
\(90, '1484', '2023-11-01', '8.00', NULL, NULL\),
\(91, '1485', '2023-11-01', '0.00', NULL, NULL\),
\(92, '1487', '2023-11-01', '6.00', NULL, NULL\),
\(93, '1488', '2023-11-01', '9.00', NULL, NULL\),
\(94, '1490', '2023-11-01', '5.00', NULL, NULL\),
\(95, '1491', '2023-11-01', '0.00', NULL, NULL\),
\(96, '1492', '2023-11-01', '7.50', NULL, NULL\),
\(97, '1495', '2023-11-01', '4.80', NULL, NULL\),
\(98, '1496', '2023-11-01', '6.40', NULL, NULL\),
\(99, '1497', '2023-11-01', '7.50', NULL, NULL\),
\(100, '1498', '2023-11-01', '8.00', NULL, NULL\),
\(101, '107', '2023-11-02', '5.30', NULL, NULL\),
\(102, '238', '2023-11-02', '0.00', NULL, NULL\),
\(103, '240', '2023-11-02', '5.00', NULL, NULL\),
\(104, '298', '2023-11-02', '8.00', NULL, NULL\),
\(105, '493', '2023-11-02', '7.80', NULL, NULL\),
\(106, '532', '2023-11-02', '0.00', NULL, NULL\),
\(107, '574', '2023-11-02', '8.00', NULL, NULL\),
\(108, '591', '2023-11-02', '0.00', NULL, NULL\),
\(109, '666', '2023-11-02', '0.00', NULL, NULL\),
\(110, '835', '2023-11-02', '0.00', NULL, NULL\),
\(111, '852', '2023-11-02', '0.00', NULL, NULL\),
\(112, '853', '2023-11-02', '8.00', NULL, NULL\),
\(113, '857', '2023-11-02', '0.00', NULL, NULL\),
\(114, '932', '2023-11-02', '7.00', NULL, NULL\),
\(115, '962', '2023-11-02', '7.00', NULL, NULL\),
\(116, '970', '2023-11-02', '8.00', NULL, NULL\),
\(117, '971', '2023-11-02', '0.00', NULL, NULL\),
\(118, '974', '2023-11-02', '0.00', NULL, NULL\),
\(119, '986', '2023-11-02', '0.00', '1', NULL\),
\(120, '1032', '2023-11-02', '0.00', NULL, NULL\),
\(121, '1035', '2023-11-02', '4.00', NULL, NULL\),
\(122, '1038', '2023-11-02', '8.50', NULL, NULL\),
\(123, '1046', '2023-11-02', '8.00', '3', NULL\),
\(124, '1047', '2023-11-02', '8.00', NULL, NULL\),
\(125, '1050', '2023-11-02', '8.00', NULL, NULL\),
\(126, '1056', '2023-11-02', '9.30', NULL, NULL\),
\(127, '1072', '2023-11-02', '4.50', NULL, NULL\),
\(128, '1081', '2023-11-02', '0.00', NULL, NULL\),
\(129, '1083', '2023-11-02', '7.00', NULL, NULL\),
\(130, '1118', '2023-11-02', '0.00', NULL, NULL\),
\(131, '1122', '2023-11-02', '0.00', NULL, NULL\),
\(132, '1129', '2023-11-02', '8.00', NULL, NULL\),
\(133, '1137', '2023-11-02', '0.00', NULL, NULL\),
\(134, '1153', '2023-11-02', '2.50', NULL, NULL\),
\(135, '1163', '2023-11-02', '0.00', NULL, NULL\),
\(136, '1189', '2023-11-02', '7.00', NULL, NULL\),
\(137, '1202', '2023-11-02', '5.80', NULL, NULL\),
\(138, '1216', '2023-11-02', '0.00', NULL, NULL\),
\(139, '1228', '2023-11-02', '8.00', NULL, NULL\),
\(140, '1236', '2023-11-02', '0.00', NULL, NULL\),
\(141, '1241', '2023-11-02', '9.80', NULL, NULL\),
\(142, '1244', '2023-11-02', '0.00', NULL, NULL\),
\(143, '1245', '2023-11-02', '8.00', NULL, NULL\),
\(144, '1291', '2023-11-02', '0.00', NULL, NULL\),
\(145, '1310', '2023-11-02', '6.80', NULL, NULL\),
\(146, '1315', '2023-11-02', '0.00', NULL, NULL\),
\(147, '1317', '2023-11-02', '8.00', NULL, NULL\),
\(148, '1320', '2023-11-02', '7.00', NULL, NULL\),
\(149, '1325', '2023-11-02', '0.00', NULL, NULL\),
\(150, '1340', '2023-11-02', '0.00', NULL, NULL\),
\(151, '1346', '2023-11-02', '7.50', NULL, NULL\),
\(152, '1347', '2023-11-02', '8.00', NULL, NULL\),
\(153, '1352', '2023-11-02', '8.00', NULL, NULL\),
\(154, '1388', '2023-11-02', '6.50', NULL, NULL\),
\(155, '1395', '2023-11-02', '9.00', '1', NULL\),
\(156, '1397', '2023-11-02', '0.00', NULL, NULL\),
\(157, '1404', '2023-11-02', '4.00', '6', NULL\),
\(158, '1406', '2023-11-02', '8.00', NULL, NULL\),
\(159, '1407', '2023-11-02', '7.80', NULL, NULL\),
\(160, '1412', '2023-11-02', '8.00', NULL, NULL\),
\(161, '1413', '2023-11-02', '0.00', NULL, NULL\),
\(162, '1414', '2023-11-02', '0.00', NULL, NULL\),
\(163, '1415', '2023-11-02', '5.30', NULL, NULL\),
\(164, '1417', '2023-11-02', '7.70', NULL, NULL\),
\(165, '1418', '2023-11-02', '0.00', '4', NULL\),
\(166, '1420', '2023-11-02', '0.00', '1', NULL\),
\(167, '1421', '2023-11-02', '10.00', '4', NULL\),
\(168, '1422', '2023-11-02', '0.00', '4', NULL\),
\(169, '1423', '2023-11-02', '8.00', '8', NULL\),
\(170, '1424', '2023-11-02', '2.00', NULL, NULL\),
\(171, '1425', '2023-11-02', '0.00', NULL, NULL\),
\(172, '1426', '2023-11-02', '0.00', NULL, NULL\),
\(173, '1428', '2023-11-02', '0.00', NULL, NULL\),
\(174, '1430', '2023-11-02', '0.00', NULL, NULL\),
\(175, '1431', '2023-11-02', '7.50', NULL, NULL\),
\(176, '1432', '2023-11-02', '8.00', NULL, NULL\),
\(177, '1441', '2023-11-02', '0.00', NULL, NULL\),
\(178, '1442', '2023-11-02', '8.00', NULL, NULL\),
\(179, '1446', '2023-11-02', '0.00', NULL, NULL\),
\(180, '1456', '2023-11-02', '0.00', NULL, NULL\),
\(181, '1460', '2023-11-02', '7.00', NULL, NULL\),
\(182, '1461', '2023-11-02', '7.00', NULL, NULL\),
\(183, '1466', '2023-11-02', '8.00', NULL, NULL\),
\(184, '1469', '2023-11-02', '7.00', NULL, NULL\),
\(185, '1471', '2023-11-02', '10.00', NULL, NULL\),
\(186, '1474', '2023-11-02', '7.00', NULL, NULL\),
\(187, '1478', '2023-11-02', '8.00', NULL, NULL\),
\(188, '1481', '2023-11-02', '7.00', NULL, NULL\),
\(189, '1482', '2023-11-02', '0.00', NULL, NULL\),
\(190, '1484', '2023-11-02', '8.00', NULL, NULL\),
\(191, '1485', '2023-11-02', '7.60', NULL, NULL\),
\(192, '1487', '2023-11-02', '4.50', NULL, NULL\),
\(193, '1488', '2023-11-02', '7.00', NULL, NULL\),
\(194, '1490', '2023-11-02', '9.50', NULL, NULL\),
\(195, '1491', '2023-11-02', '0.00', NULL, NULL\),
\(196, '1492', '2023-11-02', '8.00', NULL, NULL\),
\(197, '1495', '2023-11-02', '10.00', NULL, NULL\),
\(198, '1496', '2023-11-02', '4.00', NULL, NULL\),
\(199, '1497', '2023-11-02', '7.00', NULL, NULL\),
\(200, '1498', '2023-11-02', '8.00', NULL, NULL\),
\(201, '107', '2023-11-03', '0.00', NULL, NULL\),
\(202, '238', '2023-11-03', '8.00', '1', NULL\),
\(203, '240', '2023-11-03', '0.00', NULL, NULL\),
\(204, '298', '2023-11-03', '0.00', NULL, NULL\),
\(205, '493', '2023-11-03', '0.00', NULL, NULL\),
\(206, '532', '2023-11-03', '0.00', NULL, NULL\),
\(207, '574', '2023-11-03', '0.00', NULL, NULL\),
\(208, '591', '2023-11-03', '0.00', NULL, NULL\),
\(209, '666', '2023-11-03', '0.00', NULL, NULL\),
\(210, '835', '2023-11-03', '0.00', NULL, NULL\),
\(211, '852', '2023-11-03', '0.00', NULL, NULL\),
\(212, '853', '2023-11-03', '0.00', NULL, NULL\),
\(213, '857', '2023-11-03', '0.00', NULL, NULL\),
\(214, '932', '2023-11-03', '0.00', NULL, NULL\),
\(215, '962', '2023-11-03', '0.00', NULL, NULL\),
\(216, '970', '2023-11-03', '0.00', NULL, NULL\),
\(217, '971', '2023-11-03', '0.00', NULL, NULL\),
\(218, '974', '2023-11-03', '0.00', NULL, NULL\),
\(219, '986', '2023-11-03', '0.00', '1', NULL\),
\(220, '1032', '2023-11-03', '0.00', NULL, NULL\),
\(221, '1035', '2023-11-03', '4.00', NULL, NULL\),
\(222, '1038', '2023-11-03', '7.00', NULL, NULL\),
\(223, '1046', '2023-11-03', '0.00', NULL, NULL\),
\(224, '1047', '2023-11-03', '8.00', NULL, NULL\),
\(225, '1050', '2023-11-03', '8.00', NULL, NULL\),
\(226, '1056', '2023-11-03', '5.80', NULL, NULL\),
\(227, '1072', '2023-11-03', '4.50', NULL, NULL\),
\(228, '1081', '2023-11-03', '0.00', NULL, NULL\),
\(229, '1083', '2023-11-03', '6.00', NULL, NULL\),
\(230, '1118', '2023-11-03', '0.00', NULL, NULL\),
\(231, '1122', '2023-11-03', '0.00', NULL, NULL\),
\(232, '1129', '2023-11-03', '0.00', NULL, NULL\),
\(233, '1137', '2023-11-03', '0.00', NULL, NULL\),
\(234, '1153', '2023-11-03', '7.50', NULL, NULL\),
\(235, '1163', '2023-11-03', '0.00', NULL, NULL\),
\(236, '1189', '2023-11-03', '5.00', NULL, NULL\),
\(237, '1202', '2023-11-03', '0.00', NULL, NULL\),
\(238, '1216', '2023-11-03', '7.30', NULL, NULL\),
\(239, '1228', '2023-11-03', '7.00', NULL, NULL\),
\(240, '1236', '2023-11-03', '0.00', NULL, NULL\),
\(241, '1241', '2023-11-03', '0.00', NULL, NULL\),
\(242, '1244', '2023-11-03', '0.00', NULL, NULL\),
\(243, '1245', '2023-11-03', '5.00', NULL, NULL\),
\(244, '1291', '2023-11-03', '0.00', NULL, NULL\),
\(245, '1310', '2023-11-03', '0.00', NULL, NULL\),
\(246, '1315', '2023-11-03', '0.00', NULL, NULL\),
\(247, '1317', '2023-11-03', '0.00', NULL, NULL\),
\(248, '1320', '2023-11-03', '8.00', NULL, NULL\),
\(249, '1325', '2023-11-03', '8.00', NULL, NULL\),
\(250, '1340', '2023-11-03', '8.00', NULL, NULL\),
\(251, '1346', '2023-11-03', '5.20', NULL, NULL\),
\(252, '1347', '2023-11-03', '0.00', NULL, NULL\),
\(253, '1352', '2023-11-03', '8.00', NULL, NULL\),
\(254, '1388', '2023-11-03', '6.30', NULL, NULL\),
\(255, '1395', '2023-11-03', '0.00', NULL, NULL\),
\(256, '1397', '2023-11-03', '0.00', NULL, NULL\),
\(257, '1404', '2023-11-03', '0.00', '1', NULL\),
\(258, '1406', '2023-11-03', '0.00', NULL, NULL\),
\(259, '1407', '2023-11-03', '0.00', NULL, NULL\),
\(260, '1412', '2023-11-03', '0.00', NULL, NULL\),
\(261, '1413', '2023-11-03', '8.00', NULL, NULL\),
\(262, '1414', '2023-11-03', '0.00', NULL, NULL\),
\(263, '1415', '2023-11-03', '0.00', NULL, NULL\),
\(264, '1417', '2023-11-03', '0.00', NULL, NULL\),
\(265, '1418', '2023-11-03', '0.00', '4', NULL\),
\(266, '1420', '2023-11-03', '7.50', '1', NULL\),
\(267, '1421', '2023-11-03', '0.00', '4', NULL\),
\(268, '1422', '2023-11-03', '7.50', '4', NULL\),
\(269, '1423', '2023-11-03', '8.00', '8', NULL\),
\(270, '1424', '2023-11-03', '8.00', NULL, NULL\),
\(271, '1425', '2023-11-03', '0.00', NULL, NULL\),
\(272, '1426', '2023-11-03', '0.00', NULL, NULL\),
\(273, '1428', '2023-11-03', '7.30', NULL, NULL\),
\(274, '1430', '2023-11-03', '0.00', NULL, NULL\),
\(275, '1431', '2023-11-03', '8.00', NULL, NULL\),
\(276, '1432', '2023-11-03', '0.00', NULL, NULL\),
\(277, '1441', '2023-11-03', '7.50', NULL, NULL\),
\(278, '1442', '2023-11-03', '0.00', NULL, NULL\),
\(279, '1446', '2023-11-03', '0.00', NULL, NULL\),
\(280, '1456', '2023-11-03', '8.00', NULL, NULL\),
\(281, '1460', '2023-11-03', '5.60', NULL, NULL\),
\(282, '1461', '2023-11-03', '7.80', NULL, NULL\),
\(283, '1466', '2023-11-03', '7.00', NULL, NULL\),
\(284, '1469', '2023-11-03', '7.00', NULL, NULL\),
\(285, '1471', '2023-11-03', '8.00', NULL, NULL\),
\(286, '1474', '2023-11-03', '0.00', NULL, NULL\),
\(287, '1478', '2023-11-03', '0.00', NULL, NULL\),
\(288, '1481', '2023-11-03', '0.00', NULL, NULL\),
\(289, '1482', '2023-11-03', '0.00', NULL, NULL\),
\(290, '1484', '2023-11-03', '8.00', NULL, NULL\),
\(291, '1485', '2023-11-03', '6.80', NULL, NULL\),
\(292, '1487', '2023-11-03', '5.30', NULL, NULL\),
\(293, '1488', '2023-11-03', '0.00', NULL, NULL\),
\(294, '1490', '2023-11-03', '8.50', NULL, NULL\),
\(295, '1491', '2023-11-03', '0.00', NULL, NULL\),
\(296, '1492', '2023-11-03', '8.00', NULL, NULL\),
\(297, '1495', '2023-11-03', '0.00', NULL, NULL\),
\(298, '1496', '2023-11-03', '0.00', NULL, NULL\),
\(299, '1497', '2023-11-03', '0.00', NULL, NULL\),
\(300, '1498', '2023-11-03', '8.00', NULL, NULL\),
\(301, '107', '2023-11-04', '0.00', NULL, NULL\),
\(302, '238', '2023-11-04', '0.00', NULL, NULL\),
\(303, '240', '2023-11-04', '0.00', NULL, NULL\),
\(304, '298', '2023-11-04', '0.00', NULL, NULL\),
\(305, '493', '2023-11-04', '0.00', NULL, NULL\),
\(306, '532', '2023-11-04', '0.00', NULL, NULL\),
\(307, '574', '2023-11-04', '0.00', NULL, NULL\),
\(308, '591', '2023-11-04', '0.00', NULL, NULL\),
\(309, '666', '2023-11-04', '0.00', NULL, NULL\),
\(310, '835', '2023-11-04', '0.00', NULL, NULL\),
\(311, '852', '2023-11-04', '0.00', NULL, NULL\),
\(312, '853', '2023-11-04', '0.00', NULL, NULL\),
\(313, '857', '2023-11-04', '0.00', NULL, NULL\),
\(314, '932', '2023-11-04', '0.00', NULL, NULL\),
\(315, '962', '2023-11-04', '0.00', NULL, NULL\),
\(316, '970', '2023-11-04', '0.00', NULL, NULL\),
\(317, '971', '2023-11-04', '0.00', NULL, NULL\),
\(318, '974', '2023-11-04', '0.00', NULL, NULL\),
\(319, '986', '2023-11-04', '0.00', '1', NULL\),
\(320, '1032', '2023-11-04', '0.00', NULL, NULL\),
\(321, '1035', '2023-11-04', '0.00', NULL, NULL\),
\(322, '1038', '2023-11-04', '0.00', NULL, NULL\),
\(323, '1046', '2023-11-04', '5.00', '3', NULL\),
\(324, '1047', '2023-11-04', '5.00', NULL, NULL\),
\(325, '1050', '2023-11-04', '0.00', NULL, NULL\),
\(326, '1056', '2023-11-04', '0.00', NULL, NULL\),
\(327, '1072', '2023-11-04', '0.00', NULL, NULL\),
\(328, '1081', '2023-11-04', '0.00', NULL, NULL\),
\(329, '1083', '2023-11-04', '0.00', NULL, NULL\),
\(330, '1118', '2023-11-04', '0.00', NULL, NULL\),
\(331, '1122', '2023-11-04', '0.00', NULL, NULL\),
\(332, '1129', '2023-11-04', '0.00', NULL, NULL\),
\(333, '1137', '2023-11-04', '0.00', NULL, NULL\),
\(334, '1153', '2023-11-04', '0.00', NULL, NULL\),
\(335, '1163', '2023-11-04', '0.00', NULL, NULL\),
\(336, '1189', '2023-11-04', '0.00', NULL, NULL\),
\(337, '1202', '2023-11-04', '0.00', NULL, NULL\),
\(338, '1216', '2023-11-04', '0.00', NULL, NULL\),
\(339, '1228', '2023-11-04', '0.00', NULL, NULL\),
\(340, '1236', '2023-11-04', '0.00', NULL, NULL\),
\(341, '1241', '2023-11-04', '0.00', NULL, NULL\),
\(342, '1244', '2023-11-04', '0.00', NULL, NULL\),
\(343, '1245', '2023-11-04', '0.00', NULL, NULL\),
\(344, '1291', '2023-11-04', '0.00', NULL, NULL\),
\(345, '1310', '2023-11-04', '4.00', NULL, NULL\),
\(346, '1315', '2023-11-04', '0.00', NULL, NULL\),
\(347, '1317', '2023-11-04', '0.00', NULL, NULL\),
\(348, '1320', '2023-11-04', '0.00', NULL, NULL\),
\(349, '1325', '2023-11-04', '0.00', NULL, NULL\),
\(350, '1340', '2023-11-04', '0.00', NULL, NULL\),
\(351, '1346', '2023-11-04', '0.00', NULL, NULL\),
\(352, '1347', '2023-11-04', '0.00', NULL, NULL\),
\(353, '1352', '2023-11-04', '5.00', NULL, NULL\),
\(354, '1388', '2023-11-04', '4.00', NULL, NULL\),
\(355, '1395', '2023-11-04', '0.00', NULL, NULL\),
\(356, '1397', '2023-11-04', '0.00', NULL, NULL\),
\(357, '1404', '2023-11-04', '0.00', '1', NULL\),
\(358, '1406', '2023-11-04', '0.00', NULL, NULL\),
\(359, '1407', '2023-11-04', '0.00', NULL, NULL\),
\(360, '1412', '2023-11-04', '0.00', NULL, NULL\),
\(361, '1413', '2023-11-04', '0.00', NULL, NULL\),
\(362, '1414', '2023-11-04', '0.00', NULL, NULL\),
\(363, '1415', '2023-11-04', '0.00', NULL, NULL\),
\(364, '1417', '2023-11-04', '0.00', NULL, NULL\),
\(365, '1418', '2023-11-04', '0.00', '4', NULL\),
\(366, '1420', '2023-11-04', '0.00', '1', NULL\),
\(367, '1421', '2023-11-04', '0.00', '4', NULL\),
\(368, '1422', '2023-11-04', '0.00', '4', NULL\),
\(369, '1423', '2023-11-04', '0.00', '8', NULL\),
\(370, '1424', '2023-11-04', '0.00', NULL, NULL\),
\(371, '1425', '2023-11-04', '0.00', NULL, NULL\),
\(372, '1426', '2023-11-04', '0.00', NULL, NULL\),
\(373, '1428', '2023-11-04', '0.00', NULL, NULL\),
\(374, '1430', '2023-11-04', '0.00', NULL, NULL\),
\(375, '1431', '2023-11-04', '10.00', NULL, NULL\),
\(376, '1432', '2023-11-04', '12.00', NULL, NULL\),
\(377, '1441', '2023-11-04', '12.00', NULL, NULL\),
\(378, '1442', '2023-11-04', '0.00', NULL, NULL\),
\(379, '1446', '2023-11-04', '0.00', NULL, NULL\),
\(380, '1456', '2023-11-04', '0.00', NULL, NULL\),
\(381, '1460', '2023-11-04', '0.00', NULL, NULL\),
\(382, '1461', '2023-11-04', '0.00', NULL, NULL\),
\(383, '1466', '2023-11-04', '0.00', NULL, NULL\),
\(384, '1469', '2023-11-04', '0.00', NULL, NULL\),
\(385, '1471', '2023-11-04', '0.00', NULL, NULL\),
\(386, '1474', '2023-11-04', '0.00', NULL, NULL\),
\(387, '1478', '2023-11-04', '0.00', NULL, NULL\),
\(388, '1481', '2023-11-04', '0.00', NULL, NULL\),
\(389, '1482', '2023-11-04', '0.00', NULL, NULL\),
\(390, '1484', '2023-11-04', '0.00', NULL, NULL\),
\(391, '1485', '2023-11-04', '0.00', NULL, NULL\),
\(392, '1487', '2023-11-04', '0.00', NULL, NULL\),
\(393, '1488', '2023-11-04', '0.00', NULL, NULL\),
\(394, '1490', '2023-11-04', '0.00', NULL, NULL\),
\(395, '1491', '2023-11-04', '0.00', NULL, NULL\),
\(396, '1492', '2023-11-04', '0.00', NULL, NULL\),
\(397, '1495', '2023-11-04', '0.00', NULL, NULL\),
\(398, '1496', '2023-11-04', '0.00', NULL, NULL\),
\(399, '1497', '2023-11-04', '0.00', NULL, NULL\),
\(400, '1498', '2023-11-04', '12.00', NULL, NULL\),
\(401, '107', '2023-11-05', '0.00', NULL, NULL\),
\(402, '238', '2023-11-05', '12.00', '8', NULL\),
\(403, '240', '2023-11-05', '0.00', NULL, NULL\),
\(404, '298', '2023-11-05', '0.00', NULL, NULL\),
\(405, '493', '2023-11-05', '0.00', NULL, NULL\),
\(406, '532', '2023-11-05', '0.00', NULL, NULL\),
\(407, '574', '2023-11-05', '0.00', NULL, NULL\),
\(408, '591', '2023-11-05', '0.00', NULL, NULL\),
\(409, '666', '2023-11-05', '0.00', NULL, NULL\),
\(410, '835', '2023-11-05', '0.00', NULL, NULL\),
\(411, '852', '2023-11-05', '0.00', NULL, NULL\),
\(412, '853', '2023-11-05', '0.00', NULL, NULL\),
\(413, '857', '2023-11-05', '0.00', NULL, NULL\),
\(414, '932', '2023-11-05', '0.00', NULL, NULL\),
\(415, '962', '2023-11-05', '0.00', NULL, NULL\),
\(416, '970', '2023-11-05', '0.00', NULL, NULL\),
\(417, '971', '2023-11-05', '0.00', NULL, NULL\),
\(418, '974', '2023-11-05', '0.00', NULL, NULL\),
\(419, '986', '2023-11-05', '0.00', '1', NULL\),
\(420, '1032', '2023-11-05', '0.00', NULL, NULL\),
\(421, '1035', '2023-11-05', '0.00', NULL, NULL\),
\(422, '1038', '2023-11-05', '0.00', NULL, NULL\),
\(423, '1046', '2023-11-05', '0.00', NULL, NULL\),
\(424, '1047', '2023-11-05', '0.00', NULL, NULL\),
\(425, '1050', '2023-11-05', '0.00', NULL, NULL\),
\(426, '1056', '2023-11-05', '0.00', NULL, NULL\),
\(427, '1072', '2023-11-05', '0.00', NULL, NULL\),
\(428, '1081', '2023-11-05', '0.00', NULL, NULL\),
\(429, '1083', '2023-11-05', '0.00', NULL, NULL\),
\(430, '1118', '2023-11-05', '0.00', NULL, NULL\),
\(431, '1122', '2023-11-05', '0.00', NULL, NULL\),
\(432, '1129', '2023-11-05', '0.00', NULL, NULL\),
\(433, '1137', '2023-11-05', '0.00', NULL, NULL\),
\(434, '1153', '2023-11-05', '0.00', NULL, NULL\),
\(435, '1163', '2023-11-05', '0.00', NULL, NULL\),
\(436, '1189', '2023-11-05', '0.00', NULL, NULL\),
\(437, '1202', '2023-11-05', '0.00', NULL, NULL\),
\(438, '1216', '2023-11-05', '0.00', NULL, NULL\),
\(439, '1228', '2023-11-05', '0.00', NULL, NULL\),
\(440, '1236', '2023-11-05', '0.00', NULL, NULL\),
\(441, '1241', '2023-11-05', '0.00', NULL, NULL\),
\(442, '1244', '2023-11-05', '0.00', NULL, NULL\),
\(443, '1245', '2023-11-05', '0.00', NULL, NULL\),
\(444, '1291', '2023-11-05', '0.00', NULL, NULL\),
\(445, '1310', '2023-11-05', '0.00', NULL, NULL\),
\(446, '1315', '2023-11-05', '0.00', NULL, NULL\),
\(447, '1317', '2023-11-05', '0.00', NULL, NULL\),
\(448, '1320', '2023-11-05', '0.00', NULL, NULL\),
\(449, '1325', '2023-11-05', '0.00', NULL, NULL\),
\(450, '1340', '2023-11-05', '0.00', NULL, NULL\),
\(451, '1346', '2023-11-05', '0.00', NULL, NULL\),
\(452, '1347', '2023-11-05', '0.00', NULL, NULL\),
\(453, '1352', '2023-11-05', '0.00', NULL, NULL\),
\(454, '1388', '2023-11-05', '0.00', NULL, NULL\),
\(455, '1395', '2023-11-05', '0.00', NULL, NULL\),
\(456, '1397', '2023-11-05', '0.00', NULL, NULL\),
\(457, '1404', '2023-11-05', '0.00', '1', NULL\),
\(458, '1406', '2023-11-05', '0.00', NULL, NULL\),
\(459, '1407', '2023-11-05', '0.00', NULL, NULL\),
\(460, '1412', '2023-11-05', '0.00', NULL, NULL\),
\(461, '1413', '2023-11-05', '0.00', NULL, NULL\),
\(462, '1414', '2023-11-05', '0.00', NULL, NULL\),
\(463, '1415', '2023-11-05', '0.00', NULL, NULL\),
\(464, '1417', '2023-11-05', '0.00', NULL, NULL\),
\(465, '1418', '2023-11-05', '0.00', '4', NULL\),
\(466, '1420', '2023-11-05', '0.00', '1', NULL\),
\(467, '1421', '2023-11-05', '0.00', '4', NULL\),
\(468, '1422', '2023-11-05', '0.00', '4', NULL\),
\(469, '1423', '2023-11-05', '0.00', '8', NULL\),
\(470, '1424', '2023-11-05', '0.00', NULL, NULL\),
\(471, '1425', '2023-11-05', '0.00', NULL, NULL\),
\(472, '1426', '2023-11-05', '0.00', NULL, NULL\),
\(473, '1428', '2023-11-05', '0.00', NULL, NULL\),
\(474, '1430', '2023-11-05', '0.00', NULL, NULL\),
\(475, '1431', '2023-11-05', '0.00', NULL, NULL\),
\(476, '1432', '2023-11-05', '0.00', NULL, NULL\),
\(477, '1441', '2023-11-05', '0.00', NULL, NULL\),
\(478, '1442', '2023-11-05', '0.00', NULL, NULL\),
\(479, '1446', '2023-11-05', '0.00', NULL, NULL\),
\(480, '1456', '2023-11-05', '0.00', NULL, NULL\),
\(481, '1460', '2023-11-05', '0.00', NULL, NULL\),
\(482, '1461', '2023-11-05', '0.00', NULL, NULL\),
\(483, '1466', '2023-11-05', '12.00', NULL, NULL\),
\(484, '1469', '2023-11-05', '10.40', NULL, NULL\),
\(485, '1471', '2023-11-05', '12.00', NULL, NULL\),
\(486, '1474', '2023-11-05', '0.00', NULL, NULL\),
\(487, '1478', '2023-11-05', '0.00', NULL, NULL\),
\(488, '1481', '2023-11-05', '0.00', NULL, NULL\),
\(489, '1482', '2023-11-05', '0.00', NULL, NULL\),
\(490, '1484', '2023-11-05', '0.00', NULL, NULL\),
\(491, '1485', '2023-11-05', '0.00', NULL, NULL\),
\(492, '1487', '2023-11-05', '0.00', NULL, NULL\),
\(493, '1488', '2023-11-05', '0.00', NULL, NULL\),
\(494, '1490', '2023-11-05', '0.00', NULL, NULL\),
\(495, '1491', '2023-11-05', '0.00', NULL, NULL\),
\(496, '1492', '2023-11-05', '0.00', NULL, NULL\),
\(497, '1495', '2023-11-05', '0.00', NULL, NULL\),
\(498, '1496', '2023-11-05', '0.00', NULL, NULL\),
\(499, '1497', '2023-11-05', '0.00', NULL, NULL\),
\(500, '1498', '2023-11-05', '0.00', NULL, NULL\),
\(501, '107', '2023-11-06', '0.00', NULL, NULL\),
\(502, '238', '2023-11-06', '0.00', NULL, NULL\),
\(503, '240', '2023-11-06', '6.00', NULL, NULL\),
\(504, '298', '2023-11-06', '9.00', NULL, NULL\),
\(505, '493', '2023-11-06', '0.00', NULL, NULL\),
\(506, '532', '2023-11-06', '0.00', NULL, NULL\),
\(507, '574', '2023-11-06', '0.00', NULL, NULL\),
\(508, '591', '2023-11-06', '0.00', NULL, NULL\),
\(509, '666', '2023-11-06', '0.00', NULL, NULL\),
\(510, '835', '2023-11-06', '0.00', NULL, NULL\),
\(511, '852', '2023-11-06', '0.00', NULL, NULL\),
\(512, '853', '2023-11-06', '7.80', NULL, NULL\),
\(513, '857', '2023-11-06', '0.00', NULL, NULL\),
\(514, '932', '2023-11-06', '0.00', NULL, NULL\),
\(515, '962', '2023-11-06', '6.00', NULL, NULL\),
\(516, '970', '2023-11-06', '0.00', NULL, NULL\),
\(517, '971', '2023-11-06', '0.00', NULL, NULL\),
\(518, '974', '2023-11-06', '0.00', NULL, NULL\),
\(519, '986', '2023-11-06', '0.00', '1', NULL\),
\(520, '1032', '2023-11-06', '0.00', NULL, NULL\),
\(521, '1035', '2023-11-06', '8.00', NULL, NULL\),
\(522, '1038', '2023-11-06', '8.00', NULL, NULL\),
\(523, '1046', '2023-11-06', '0.00', NULL, NULL\),
\(524, '1047', '2023-11-06', '8.00', NULL, NULL\),
\(525, '1050', '2023-11-06', '8.00', NULL, NULL\),
\(526, '1056', '2023-11-06', '4.50', NULL, NULL\),
\(527, '1072', '2023-11-06', '4.50', NULL, NULL\),
\(528, '1081', '2023-11-06', '0.00', NULL, NULL\),
\(529, '1083', '2023-11-06', '4.40', NULL, NULL\),
\(530, '1118', '2023-11-06', '0.00', NULL, NULL\),
\(531, '1122', '2023-11-06', '0.00', NULL, NULL\),
\(532, '1129', '2023-11-06', '8.00', NULL, NULL\),
\(533, '1137', '2023-11-06', '0.00', NULL, NULL\),
\(534, '1153', '2023-11-06', '8.00', NULL, NULL\),
\(535, '1163', '2023-11-06', '0.00', NULL, NULL\),
\(536, '1189', '2023-11-06', '0.00', NULL, NULL\),
\(537, '1202', '2023-11-06', '8.50', NULL, NULL\),
\(538, '1216', '2023-11-06', '0.00', NULL, NULL\),
\(539, '1228', '2023-11-06', '8.00', NULL, NULL\),
\(540, '1236', '2023-11-06', '0.00', NULL, NULL\),
\(541, '1241', '2023-11-06', '6.00', NULL, NULL\),
\(542, '1244', '2023-11-06', '0.00', NULL, NULL\),
\(543, '1245', '2023-11-06', '6.50', NULL, NULL\),
\(544, '1291', '2023-11-06', '0.00', NULL, NULL\),
\(545, '1310', '2023-11-06', '6.60', NULL, NULL\),
\(546, '1315', '2023-11-06', '0.00', NULL, NULL\),
\(547, '1317', '2023-11-06', '0.00', NULL, NULL\),
\(548, '1320', '2023-11-06', '0.00', NULL, NULL\),
\(549, '1325', '2023-11-06', '8.00', NULL, NULL\),
\(550, '1340', '2023-11-06', '8.00', NULL, NULL\),
\(551, '1346', '2023-11-06', '7.20', NULL, NULL\),
\(552, '1347', '2023-11-06', '0.00', NULL, NULL\),
\(553, '1352', '2023-11-06', '8.00', NULL, NULL\),
\(554, '1388', '2023-11-06', '0.00', NULL, NULL\),
\(555, '1395', '2023-11-06', '0.00', NULL, NULL\),
\(556, '1397', '2023-11-06', '7.50', NULL, NULL\),
\(557, '1404', '2023-11-06', '3.00', '1', NULL\),
\(558, '1406', '2023-11-06', '3.60', NULL, NULL\),
\(559, '1407', '2023-11-06', '3.20', NULL, NULL\),
\(560, '1412', '2023-11-06', '0.00', NULL, NULL\),
\(561, '1413', '2023-11-06', '8.00', NULL, NULL\),
\(562, '1414', '2023-11-06', '4.70', NULL, NULL\),
\(563, '1415', '2023-11-06', '5.50', NULL, NULL\),
\(564, '1417', '2023-11-06', '0.00', NULL, NULL\),
\(565, '1418', '2023-11-06', '0.00', '4', NULL\),
\(566, '1420', '2023-11-06', '8.00', '1', NULL\),
\(567, '1421', '2023-11-06', '6.50', '4', NULL\),
\(568, '1422', '2023-11-06', '8.00', '4', NULL\),
\(569, '1423', '2023-11-06', '8.00', '8', NULL\),
\(570, '1424', '2023-11-06', '8.00', NULL, NULL\),
\(571, '1425', '2023-11-06', '0.00', NULL, NULL\),
\(572, '1426', '2023-11-06', '0.00', NULL, NULL\),
\(573, '1428', '2023-11-06', '0.00', NULL, NULL\),
\(574, '1430', '2023-11-06', '0.00', NULL, NULL\),
\(575, '1431', '2023-11-06', '0.00', NULL, NULL\),
\(576, '1432', '2023-11-06', '8.00', NULL, NULL\),
\(577, '1441', '2023-11-06', '7.50', NULL, NULL\),
\(578, '1442', '2023-11-06', '0.00', NULL, NULL\),
\(579, '1446', '2023-11-06', '0.00', NULL, NULL\),
\(580, '1456', '2023-11-06', '0.00', NULL, NULL\),
\(581, '1460', '2023-11-06', '7.60', NULL, NULL\),
\(582, '1461', '2023-11-06', '6.30', NULL, NULL\),
\(583, '1466', '2023-11-06', '5.80', NULL, NULL\),
\(584, '1469', '2023-11-06', '7.00', NULL, NULL\),
\(585, '1471', '2023-11-06', '0.00', NULL, NULL\),
\(586, '1474', '2023-11-06', '0.00', NULL, NULL\),
\(587, '1478', '2023-11-06', '0.00', NULL, NULL\),
\(588, '1481', '2023-11-06', '0.00', NULL, NULL\),
\(589, '1482', '2023-11-06', '0.00', NULL, NULL\),
\(590, '1484', '2023-11-06', '8.00', NULL, NULL\),
\(591, '1485', '2023-11-06', '7.00', NULL, NULL\),
\(592, '1487', '2023-11-06', '6.00', NULL, NULL\),
\(593, '1488', '2023-11-06', '8.00', NULL, NULL\),
\(594, '1490', '2023-11-06', '0.00', NULL, NULL\),
\(595, '1491', '2023-11-06', '0.00', NULL, NULL\),
\(596, '1492', '2023-11-06', '0.00', NULL, NULL\),
\(597, '1495', '2023-11-06', '0.00', NULL, NULL\),
\(598, '1496', '2023-11-06', '7.90', NULL, NULL\),
\(599, '1497', '2023-11-06', '3.80', NULL, NULL\),
\(600, '1498', '2023-11-06', '8.00', NULL, NULL\),
\(601, '107', '2023-11-07', '0.00', NULL, NULL\),
\(602, '238', '2023-11-07', '8.00', '1', NULL\),
\(603, '240', '2023-11-07', '0.00', NULL, NULL\),
\(604, '298', '2023-11-07', '8.00', NULL, NULL\),
\(605, '493', '2023-11-07', '8.80', NULL, NULL\),
\(606, '532', '2023-11-07', '0.00', NULL, NULL\),
\(607, '574', '2023-11-07', '8.00', NULL, NULL\),
\(608, '591', '2023-11-07', '0.00', NULL, NULL\),
\(609, '666', '2023-11-07', '0.00', NULL, NULL\),
\(610, '835', '2023-11-07', '0.00', NULL, NULL\),
\(611, '852', '2023-11-07', '7.00', NULL, NULL\),
\(612, '853', '2023-11-07', '7.00', NULL, NULL\),
\(613, '857', '2023-11-07', '0.00', NULL, NULL\),
\(614, '932', '2023-11-07', '0.00', NULL, NULL\),
\(615, '962', '2023-11-07', '0.00', NULL, NULL\),
\(616, '970', '2023-11-07', '0.00', NULL, NULL\),
\(617, '971', '2023-11-07', '0.00', NULL, NULL\),
\(618, '974', '2023-11-07', '0.00', NULL, NULL\),
\(619, '986', '2023-11-07', '0.00', '1', NULL\),
\(620, '1032', '2023-11-07', '0.00', NULL, NULL\),
\(621, '1035', '2023-11-07', '8.00', NULL, NULL\),
\(622, '1038', '2023-11-07', '7.50', NULL, NULL\),
\(623, '1046', '2023-11-07', '0.00', NULL, NULL\),
\(624, '1047', '2023-11-07', '8.00', NULL, NULL\),
\(625, '1050', '2023-11-07', '0.00', NULL, NULL\),
\(626, '1056', '2023-11-07', '9.80', NULL, NULL\),
\(627, '1072', '2023-11-07', '4.50', NULL, NULL\),
\(628, '1081', '2023-11-07', '0.00', NULL, NULL\),
\(629, '1083', '2023-11-07', '5.70', NULL, NULL\),
\(630, '1118', '2023-11-07', '0.00', NULL, NULL\),
\(631, '1122', '2023-11-07', '0.00', NULL, NULL\),
\(632, '1129', '2023-11-07', '8.00', NULL, NULL\),
\(633, '1137', '2023-11-07', '0.00', NULL, NULL\),
\(634, '1153', '2023-11-07', '8.00', NULL, NULL\),
\(635, '1163', '2023-11-07', '0.00', NULL, NULL\),
\(636, '1189', '2023-11-07', '7.00', NULL, NULL\),
\(637, '1202', '2023-11-07', '0.00', NULL, NULL\),
\(638, '1216', '2023-11-07', '8.00', NULL, NULL\),
\(639, '1228', '2023-11-07', '8.00', NULL, NULL\),
\(640, '1236', '2023-11-07', '0.00', NULL, NULL\),
\(641, '1241', '2023-11-07', '0.00', NULL, NULL\),
\(642, '1244', '2023-11-07', '0.00', NULL, NULL\),
\(643, '1245', '2023-11-07', '8.00', NULL, NULL\),
\(644, '1291', '2023-11-07', '8.00', NULL, NULL\),
\(645, '1310', '2023-11-07', '5.60', NULL, NULL\),
\(646, '1315', '2023-11-07', '0.00', NULL, NULL\),
\(647, '1317', '2023-11-07', '0.00', NULL, NULL\),
\(648, '1320', '2023-11-07', '7.00', NULL, NULL\),
\(649, '1325', '2023-11-07', '8.00', NULL, NULL\),
\(650, '1340', '2023-11-07', '8.00', NULL, NULL\),
\(651, '1346', '2023-11-07', '5.30', NULL, NULL\),
\(652, '1347', '2023-11-07', '0.00', NULL, NULL\),
\(653, '1352', '2023-11-07', '8.00', NULL, NULL\),
\(654, '1388', '2023-11-07', '9.00', NULL, NULL\),
\(655, '1395', '2023-11-07', '0.00', NULL, NULL\),
\(656, '1397', '2023-11-07', '7.50', NULL, NULL\),
\(657, '1404', '2023-11-07', '0.00', '1', NULL\),
\(658, '1406', '2023-11-07', '8.00', NULL, NULL\),
\(659, '1407', '2023-11-07', '7.90', NULL, NULL\),
\(660, '1412', '2023-11-07', '8.00', NULL, NULL\),
\(661, '1413', '2023-11-07', '8.00', NULL, NULL\),
\(662, '1414', '2023-11-07', '6.20', NULL, NULL\),
\(663, '1415', '2023-11-07', '5.70', NULL, NULL\),
\(664, '1417', '2023-11-07', '0.00', NULL, NULL\),
\(665, '1418', '2023-11-07', '0.00', '4', NULL\),
\(666, '1420', '2023-11-07', '8.00', '1', NULL\),
\(667, '1421', '2023-11-07', '8.50', '4', NULL\),
\(668, '1422', '2023-11-07', '7.00', '4', NULL\),
\(669, '1423', '2023-11-07', '8.00', '8', NULL\),
\(670, '1424', '2023-11-07', '8.00', NULL, NULL\),
\(671, '1425', '2023-11-07', '7.00', '1', NULL\),
\(672, '1426', '2023-11-07', '0.00', NULL, NULL\),
\(673, '1428', '2023-11-07', '6.00', NULL, NULL\),
\(674, '1430', '2023-11-07', '0.00', NULL, NULL\),
\(675, '1431', '2023-11-07', '8.00', NULL, NULL\),
\(676, '1432', '2023-11-07', '8.00', NULL, NULL\),
\(677, '1441', '2023-11-07', '8.00', NULL, NULL\),
\(678, '1442', '2023-11-07', '8.00', NULL, NULL\),
\(679, '1446', '2023-11-07', '0.00', NULL, NULL\),
\(680, '1456', '2023-11-07', '8.00', NULL, NULL\),
\(681, '1460', '2023-11-07', '0.00', NULL, NULL\),
\(682, '1461', '2023-11-07', '3.80', NULL, NULL\),
\(683, '1466', '2023-11-07', '5.30', NULL, NULL\),
\(684, '1469', '2023-11-07', '7.00', NULL, NULL\),
\(685, '1471', '2023-11-07', '9.00', NULL, NULL\),
\(686, '1474', '2023-11-07', '9.60', NULL, NULL\),
\(687, '1478', '2023-11-07', '0.00', NULL, NULL\),
\(688, '1481', '2023-11-07', '8.00', NULL, NULL\),
\(689, '1482', '2023-11-07', '0.00', NULL, NULL\),
\(690, '1484', '2023-11-07', '8.00', NULL, NULL\),
\(691, '1485', '2023-11-07', '0.00', NULL, NULL\),
\(692, '1487', '2023-11-07', '7.90', NULL, NULL\),
\(693, '1488', '2023-11-07', '7.50', NULL, NULL\),
\(694, '1490', '2023-11-07', '0.00', NULL, NULL\),
\(695, '1491', '2023-11-07', '0.00', NULL, NULL\),
\(696, '1492', '2023-11-07', '0.00', NULL, NULL\),
\(697, '1495', '2023-11-07', '7.00', NULL, NULL\),
\(698, '1496', '2023-11-07', '5.90', NULL, NULL\),
\(699, '1497', '2023-11-07', '8.00', NULL, NULL\),
\(700, '1498', '2023-11-07', '0.00', NULL, NULL\),
\(701, '107', '2023-11-08', '0.00', NULL, NULL\),
\(702, '238', '2023-11-08', '8.00', '1', NULL\),
\(703, '240', '2023-11-08', '0.00', NULL, NULL\),
\(704, '298', '2023-11-08', '7.50', NULL, NULL\),
\(705, '493', '2023-11-08', '0.00', NULL, NULL\),
\(706, '532', '2023-11-08', '0.00', NULL, NULL\),
\(707, '574', '2023-11-08', '8.00', NULL, NULL\),
\(708, '591', '2023-11-08', '0.00', NULL, NULL\),
\(709, '666', '2023-11-08', '0.00', NULL, NULL\),
\(710, '835', '2023-11-08', '0.00', NULL, NULL\),
\(711, '852', '2023-11-08', '7.00', NULL, NULL\),
\(712, '853', '2023-11-08', '7.90', NULL, NULL\),
\(713, '857', '2023-11-08', '0.00', NULL, NULL\),
\(714, '932', '2023-11-08', '6.20', NULL, NULL\),
\(715, '962', '2023-11-08', '8.00', NULL, NULL\),
\(716, '970', '2023-11-08', '0.00', NULL, NULL\),
\(717, '971', '2023-11-08', '0.00', NULL, NULL\),
\(718, '974', '2023-11-08', '0.00', NULL, NULL\),
\(719, '986', '2023-11-08', '0.00', '1', NULL\),
\(720, '1032', '2023-11-08', '0.00', NULL, NULL\),
\(721, '1035', '2023-11-08', '8.00', NULL, NULL\),
\(722, '1038', '2023-11-08', '7.00', NULL, NULL\),
\(723, '1046', '2023-11-08', '0.00', NULL, NULL\),
\(724, '1047', '2023-11-08', '8.00', NULL, NULL\),
\(725, '1050', '2023-11-08', '0.00', NULL, NULL\),
\(726, '1056', '2023-11-08', '0.00', NULL, NULL\),
\(727, '1072', '2023-11-08', '4.50', NULL, NULL\),
\(728, '1081', '2023-11-08', '0.00', NULL, NULL\),
\(729, '1083', '2023-11-08', '6.90', NULL, NULL\),
\(730, '1118', '2023-11-08', '0.00', NULL, NULL\),
\(731, '1122', '2023-11-08', '0.00', NULL, NULL\),
\(732, '1129', '2023-11-08', '8.00', NULL, NULL\),
\(733, '1137', '2023-11-08', '0.00', NULL, NULL\),
\(734, '1153', '2023-11-08', '8.00', NULL, NULL\),
\(735, '1163', '2023-11-08', '0.00', NULL, NULL\),
\(736, '1189', '2023-11-08', '0.00', NULL, NULL\),
\(737, '1202', '2023-11-08', '7.00', NULL, NULL\),
\(738, '1216', '2023-11-08', '5.90', NULL, NULL\),
\(739, '1228', '2023-11-08', '0.00', NULL, NULL\),
\(740, '1236', '2023-11-08', '0.00', NULL, NULL\),
\(741, '1241', '2023-11-08', '0.00', NULL, NULL\),
\(742, '1244', '2023-11-08', '0.00', NULL, NULL\),
\(743, '1245', '2023-11-08', '8.00', NULL, NULL\),
\(744, '1291', '2023-11-08', '8.00', NULL, NULL\),
\(745, '1310', '2023-11-08', '8.00', NULL, NULL\),
\(746, '1315', '2023-11-08', '0.00', NULL, NULL\),
\(747, '1317', '2023-11-08', '0.00', NULL, NULL\),
\(748, '1320', '2023-11-08', '8.00', NULL, NULL\),
\(749, '1325', '2023-11-08', '0.00', NULL, NULL\),
\(750, '1340', '2023-11-08', '8.00', NULL, NULL\),
\(751, '1346', '2023-11-08', '9.00', NULL, NULL\),
\(752, '1347', '2023-11-08', '0.00', NULL, NULL\),
\(753, '1352', '2023-11-08', '8.00', NULL, NULL\),
\(754, '1388', '2023-11-08', '7.00', NULL, NULL\),
\(755, '1395', '2023-11-08', '0.00', NULL, NULL\),
\(756, '1397', '2023-11-08', '7.50', NULL, NULL\),
\(757, '1404', '2023-11-08', '0.00', '1', NULL\),
\(758, '1406', '2023-11-08', '7.80', NULL, NULL\),
\(759, '1407', '2023-11-08', '8.00', NULL, NULL\),
\(760, '1412', '2023-11-08', '8.00', NULL, NULL\),
\(761, '1413', '2023-11-08', '8.00', NULL, NULL\),
\(762, '1414', '2023-11-08', '5.60', NULL, NULL\),
\(763, '1415', '2023-11-08', '6.60', NULL, NULL\),
\(764, '1417', '2023-11-08', '0.00', NULL, NULL\),
\(765, '1418', '2023-11-08', '0.00', '4', NULL\),
\(766, '1420', '2023-11-08', '8.00', '1', NULL\),
\(767, '1421', '2023-11-08', '0.00', '4', NULL\),
\(768, '1422', '2023-11-08', '8.00', '4', NULL\),
\(769, '1423', '2023-11-08', '8.00', '8', NULL\),
\(770, '1424', '2023-11-08', '8.00', NULL, NULL\),
\(771, '1425', '2023-11-08', '8.00', '1', NULL\),
\(772, '1426', '2023-11-08', '0.00', NULL, NULL\),
\(773, '1428', '2023-11-08', '0.00', NULL, NULL\),
\(774, '1430', '2023-11-08', '0.00', NULL, NULL\),
\(775, '1431', '2023-11-08', '0.00', NULL, NULL\),
\(776, '1432', '2023-11-08', '8.00', NULL, NULL\),
\(777, '1441', '2023-11-08', '8.00', NULL, NULL\),
\(778, '1442', '2023-11-08', '7.50', NULL, NULL\),
\(779, '1446', '2023-11-08', '5.90', NULL, NULL\),
\(780, '1456', '2023-11-08', '7.00', NULL, NULL\),
\(781, '1460', '2023-11-08', '5.10', NULL, NULL\),
\(782, '1461', '2023-11-08', '8.00', NULL, NULL\),
\(783, '1466', '2023-11-08', '0.00', NULL, NULL\),
\(784, '1469', '2023-11-08', '6.80', NULL, NULL\),
\(785, '1471', '2023-11-08', '0.00', NULL, NULL\),
\(786, '1474', '2023-11-08', '9.40', NULL, NULL\),
\(787, '1478', '2023-11-08', '0.00', NULL, NULL\),
\(788, '1481', '2023-11-08', '8.00', NULL, NULL\),
\(789, '1482', '2023-11-08', '0.00', NULL, NULL\),
\(790, '1484', '2023-11-08', '8.00', NULL, NULL\),
\(791, '1485', '2023-11-08', '7.50', NULL, NULL\),
\(792, '1487', '2023-11-08', '4.00', NULL, NULL\),
\(793, '1488', '2023-11-08', '8.00', NULL, NULL\),
\(794, '1490', '2023-11-08', '0.00', NULL, NULL\),
\(795, '1491', '2023-11-08', '0.00', NULL, NULL\),
\(796, '1492', '2023-11-08', '8.00', NULL, NULL\),
\(797, '1495', '2023-11-08', '8.00', NULL, NULL\),
\(798, '1496', '2023-11-08', '6.20', NULL, NULL\),
\(799, '1497', '2023-11-08', '6.00', NULL, NULL\),
\(800, '1498', '2023-11-08', '0.00', NULL, NULL\),
\(801, '107', '2023-11-09', '0.00', NULL, NULL\),
\(802, '238', '2023-11-09', '8.00', '1', NULL\),
\(803, '240', '2023-11-09', '0.00', NULL, NULL\),
\(804, '298', '2023-11-09', '5.30', NULL, NULL\),
\(805, '493', '2023-11-09', '0.00', NULL, NULL\),
\(806, '532', '2023-11-09', '0.00', NULL, NULL\),
\(807, '574', '2023-11-09', '8.00', NULL, NULL\),
\(808, '591', '2023-11-09', '0.00', NULL, NULL\),
\(809, '666', '2023-11-09', '0.00', NULL, NULL\),
\(810, '835', '2023-11-09', '0.00', NULL, NULL\),
\(811, '852', '2023-11-09', '6.00', NULL, NULL\),
\(812, '853', '2023-11-09', '5.80', NULL, NULL\),
\(813, '857', '2023-11-09', '0.00', NULL, NULL\),
\(814, '932', '2023-11-09', '0.00', NULL, NULL\),
\(815, '962', '2023-11-09', '8.00', NULL, NULL\),
\(816, '970', '2023-11-09', '0.00', NULL, NULL\),
\(817, '971', '2023-11-09', '0.00', NULL, NULL\),
\(818, '974', '2023-11-09', '0.00', NULL, NULL\),
\(819, '986', '2023-11-09', '0.00', '1', NULL\),
\(820, '1032', '2023-11-09', '0.00', NULL, NULL\),
\(821, '1035', '2023-11-09', '8.00', NULL, NULL\),
\(822, '1038', '2023-11-09', '8.00', NULL, NULL\),
\(823, '1046', '2023-11-09', '0.00', NULL, NULL\),
\(824, '1047', '2023-11-09', '8.00', NULL, NULL\),
\(825, '1050', '2023-11-09', '8.00', NULL, NULL\),
\(826, '1056', '2023-11-09', '0.00', NULL, NULL\),
\(827, '1072', '2023-11-09', '0.00', NULL, NULL\),
\(828, '1081', '2023-11-09', '0.00', NULL, NULL\),
\(829, '1083', '2023-11-09', '5.40', NULL, NULL\),
\(830, '1118', '2023-11-09', '0.00', NULL, NULL\),
\(831, '1122', '2023-11-09', '0.00', NULL, NULL\),
\(832, '1129', '2023-11-09', '8.00', NULL, NULL\),
\(833, '1137', '2023-11-09', '0.00', NULL, NULL\),
\(834, '1153', '2023-11-09', '0.00', NULL, NULL\),
\(835, '1163', '2023-11-09', '0.00', NULL, NULL\),
\(836, '1189', '2023-11-09', '0.00', NULL, NULL\),
\(837, '1202', '2023-11-09', '7.40', NULL, NULL\),
\(838, '1216', '2023-11-09', '6.80', NULL, NULL\),
\(839, '1228', '2023-11-09', '7.50', NULL, NULL\),
\(840, '1236', '2023-11-09', '0.00', NULL, NULL\),
\(841, '1241', '2023-11-09', '6.30', NULL, NULL\),
\(842, '1244', '2023-11-09', '0.00', NULL, NULL\),
\(843, '1245', '2023-11-09', '8.00', NULL, NULL\),
\(844, '1291', '2023-11-09', '8.00', NULL, NULL\),
\(845, '1310', '2023-11-09', '8.00', NULL, NULL\),
\(846, '1315', '2023-11-09', '0.00', NULL, NULL\),
\(847, '1317', '2023-11-09', '0.00', NULL, NULL\),
\(848, '1320', '2023-11-09', '11.50', NULL, NULL\),
\(849, '1325', '2023-11-09', '8.00', NULL, NULL\),
\(850, '1340', '2023-11-09', '8.00', NULL, NULL\),
\(851, '1346', '2023-11-09', '6.40', NULL, NULL\),
\(852, '1347', '2023-11-09', '0.00', NULL, NULL\),
\(853, '1352', '2023-11-09', '8.00', NULL, NULL\),
\(854, '1388', '2023-11-09', '0.00', NULL, NULL\),
\(855, '1395', '2023-11-09', '0.00', NULL, NULL\),
\(856, '1397', '2023-11-09', '8.00', NULL, NULL\),
\(857, '1404', '2023-11-09', '6.60', '1', NULL\),
\(858, '1406', '2023-11-09', '5.80', NULL, NULL\),
\(859, '1407', '2023-11-09', '6.40', NULL, NULL\),
\(860, '1412', '2023-11-09', '8.00', NULL, NULL\),
\(861, '1413', '2023-11-09', '8.00', NULL, NULL\),
\(862, '1414', '2023-11-09', '3.70', NULL, NULL\),
\(863, '1415', '2023-11-09', '0.00', NULL, NULL\),
\(864, '1417', '2023-11-09', '7.00', NULL, NULL\),
\(865, '1418', '2023-11-09', '0.00', '4', NULL\),
\(866, '1420', '2023-11-09', '7.50', '1', NULL\),
\(867, '1421', '2023-11-09', '9.00', '4', NULL\),
\(868, '1422', '2023-11-09', '7.00', '4', NULL\),
\(869, '1423', '2023-11-09', '8.00', '8', NULL\),
\(870, '1424', '2023-11-09', '8.00', NULL, NULL\),
\(871, '1425', '2023-11-09', '8.00', '1', NULL\),
\(872, '1426', '2023-11-09', '0.00', NULL, NULL\),
\(873, '1428', '2023-11-09', '0.00', NULL, NULL\),
\(874, '1430', '2023-11-09', '0.00', NULL, NULL\),
\(875, '1431', '2023-11-09', '7.50', NULL, NULL\),
\(876, '1432', '2023-11-09', '8.00', NULL, NULL\),
\(877, '1441', '2023-11-09', '8.00', NULL, NULL\),
\(878, '1442', '2023-11-09', '0.00', NULL, NULL\),
\(879, '1446', '2023-11-09', '6.60', NULL, NULL\),
\(880, '1456', '2023-11-09', '8.00', NULL, NULL\),
\(881, '1460', '2023-11-09', '8.00', NULL, NULL\),
\(882, '1461', '2023-11-09', '5.80', NULL, NULL\),
\(883, '1466', '2023-11-09', '0.00', NULL, NULL\),
\(884, '1469', '2023-11-09', '0.00', NULL, NULL\),
\(885, '1471', '2023-11-09', '8.00', NULL, NULL\),
\(886, '1474', '2023-11-09', '9.50', NULL, NULL\),
\(887, '1478', '2023-11-09', '0.00', NULL, NULL\),
\(888, '1481', '2023-11-09', '8.00', NULL, NULL\),
\(889, '1482', '2023-11-09', '0.00', NULL, NULL\),
\(890, '1484', '2023-11-09', '8.00', NULL, NULL\),
\(891, '1485', '2023-11-09', '7.50', NULL, NULL\),
\(892, '1487', '2023-11-09', '5.50', NULL, NULL\),
\(893, '1488', '2023-11-09', '0.00', NULL, NULL\),
\(894, '1490', '2023-11-09', '0.00', NULL, NULL\),
\(895, '1491', '2023-11-09', '0.00', NULL, NULL\),
\(896, '1492', '2023-11-09', '8.00', NULL, NULL\),
\(897, '1495', '2023-11-09', '6.20', NULL, NULL\),
\(898, '1496', '2023-11-09', '3.60', NULL, NULL\),
\(899, '1497', '2023-11-09', '6.00', NULL, NULL\),
\(900, '1498', '2023-11-09', '8.00', NULL, NULL\),
\(901, '107', '2023-11-10', '0.00', NULL, NULL\),
\(902, '238', '2023-11-10', '8.00', '1', NULL\),
\(903, '240', '2023-11-10', '0.00', NULL, NULL\),
\(904, '298', '2023-11-10', '0.00', NULL, NULL\),
\(905, '493', '2023-11-10', '0.00', NULL, NULL\),
\(906, '532', '2023-11-10', '0.00', NULL, NULL\),
\(907, '574', '2023-11-10', '0.00', NULL, NULL\),
\(908, '591', '2023-11-10', '0.00', NULL, NULL\),
\(909, '666', '2023-11-10', '0.00', NULL, NULL\),
\(910, '835', '2023-11-10', '0.00', NULL, NULL\),
\(911, '852', '2023-11-10', '0.00', NULL, NULL\),
\(912, '853', '2023-11-10', '3.00', NULL, NULL\),
\(913, '857', '2023-11-10', '0.00', NULL, NULL\),
\(914, '932', '2023-11-10', '0.00', NULL, NULL\),
\(915, '962', '2023-11-10', '2.00', NULL, NULL\),
\(916, '970', '2023-11-10', '0.00', NULL, NULL\),
\(917, '971', '2023-11-10', '0.00', NULL, NULL\),
\(918, '974', '2023-11-10', '0.00', NULL, NULL\),
\(919, '986', '2023-11-10', '0.00', '1', NULL\),
\(920, '1032', '2023-11-10', '0.00', NULL, NULL\),
\(921, '1035', '2023-11-10', '0.00', NULL, NULL\),
\(922, '1038', '2023-11-10', '0.00', NULL, NULL\),
\(923, '1046', '2023-11-10', '0.00', NULL, NULL\),
\(924, '1047', '2023-11-10', '8.00', NULL, NULL\),
\(925, '1050', '2023-11-10', '0.00', NULL, NULL\),
\(926, '1056', '2023-11-10', '6.40', NULL, NULL\),
\(927, '1072', '2023-11-10', '4.50', NULL, NULL\),
\(928, '1081', '2023-11-10', '0.00', NULL, NULL\),
\(929, '1083', '2023-11-10', '0.00', NULL, NULL\),
\(930, '1118', '2023-11-10', '0.00', NULL, NULL\),
\(931, '1122', '2023-11-10', '0.00', NULL, NULL\),
\(932, '1129', '2023-11-10', '0.00', NULL, NULL\),
\(933, '1137', '2023-11-10', '0.00', NULL, NULL\),
\(934, '1153', '2023-11-10', '0.00', NULL, NULL\),
\(935, '1163', '2023-11-10', '0.00', NULL, NULL\),
\(936, '1189', '2023-11-10', '0.00', NULL, NULL\),
\(937, '1202', '2023-11-10', '3.00', NULL, NULL\),
\(938, '1216', '2023-11-10', '0.00', NULL, NULL\),
\(939, '1228', '2023-11-10', '8.00', NULL, NULL\),
\(940, '1236', '2023-11-10', '0.00', NULL, NULL\),
\(941, '1241', '2023-11-10', '0.00', NULL, NULL\),
\(942, '1244', '2023-11-10', '0.00', NULL, NULL\),
\(943, '1245', '2023-11-10', '6.00', NULL, NULL\),
\(944, '1291', '2023-11-10', '8.00', NULL, NULL\),
\(945, '1310', '2023-11-10', '0.00', NULL, NULL\),
\(946, '1315', '2023-11-10', '0.00', NULL, NULL\),
\(947, '1317', '2023-11-10', '0.00', NULL, NULL\),
\(948, '1320', '2023-11-10', '8.00', NULL, NULL\),
\(949, '1325', '2023-11-10', '0.00', NULL, NULL\),
\(950, '1340', '2023-11-10', '8.00', NULL, NULL\),
\(951, '1346', '2023-11-10', '5.50', NULL, NULL\),
\(952, '1347', '2023-11-10', '0.00', NULL, NULL\),
\(953, '1352', '2023-11-10', '8.00', NULL, NULL\),
\(954, '1388', '2023-11-10', '6.50', NULL, NULL\),
\(955, '1395', '2023-11-10', '0.00', NULL, NULL\),
\(956, '1397', '2023-11-10', '0.00', NULL, NULL\),
\(957, '1404', '2023-11-10', '0.00', '1', NULL\),
\(958, '1406', '2023-11-10', '0.00', NULL, NULL\),
\(959, '1407', '2023-11-10', '4.60', NULL, NULL\),
\(960, '1412', '2023-11-10', '8.00', NULL, NULL\),
\(961, '1413', '2023-11-10', '0.00', NULL, NULL\),
\(962, '1414', '2023-11-10', '5.10', NULL, NULL\),
\(963, '1415', '2023-11-10', '0.00', NULL, NULL\),
\(964, '1417', '2023-11-10', '2.00', NULL, NULL\),
\(965, '1418', '2023-11-10', '0.00', '4', NULL\),
\(966, '1420', '2023-11-10', '0.00', '1', NULL\),
\(967, '1421', '2023-11-10', '0.00', '4', NULL\),
\(968, '1422', '2023-11-10', '0.00', '4', NULL\),
\(969, '1423', '2023-11-10', '0.00', '8', NULL\),
\(970, '1424', '2023-11-10', '0.00', NULL, NULL\),
\(971, '1425', '2023-11-10', '8.00', '1', NULL\),
\(972, '1426', '2023-11-10', '7.50', NULL, NULL\),
\(973, '1428', '2023-11-10', '0.00', NULL, NULL\),
\(974, '1430', '2023-11-10', '0.00', NULL, NULL\),
\(975, '1431', '2023-11-10', '0.00', NULL, NULL\),
\(976, '1432', '2023-11-10', '0.00', NULL, NULL\),
\(977, '1441', '2023-11-10', '4.20', NULL, NULL\),
\(978, '1442', '2023-11-10', '8.00', NULL, NULL\),
\(979, '1446', '2023-11-10', '0.00', NULL, NULL\),
\(980, '1456', '2023-11-10', '0.00', NULL, NULL\),
\(981, '1460', '2023-11-10', '5.90', NULL, NULL\),
\(982, '1461', '2023-11-10', '7.00', NULL, NULL\),
\(983, '1466', '2023-11-10', '0.00', NULL, NULL\),
\(984, '1469', '2023-11-10', '0.00', NULL, NULL\),
\(985, '1471', '2023-11-10', '7.00', NULL, NULL\),
\(986, '1474', '2023-11-10', '0.00', NULL, NULL\),
\(987, '1478', '2023-11-10', '0.00', NULL, NULL\),
\(988, '1481', '2023-11-10', '8.00', NULL, NULL\),
\(989, '1482', '2023-11-10', '0.00', NULL, NULL\),
\(990, '1484', '2023-11-10', '8.00', NULL, NULL\),
\(991, '1485', '2023-11-10', '0.00', NULL, NULL\),
\(992, '1487', '2023-11-10', '5.20', NULL, NULL\),
\(993, '1488', '2023-11-10', '8.00', NULL, NULL\),
\(994, '1490', '2023-11-10', '0.00', NULL, NULL\),
\(995, '1491', '2023-11-10', '0.00', NULL, NULL\),
\(996, '1492', '2023-11-10', '0.00', NULL, NULL\),
\(997, '1495', '2023-11-10', '0.00', NULL, NULL\),
\(998, '1496', '2023-11-10', '0.00', NULL, NULL\),
\(999, '1497', '2023-11-10', '0.00', NULL, NULL\),
\(1000, '1498', '2023-11-10', '8.00', NULL, NULL\),
\(1001, '107', '2023-11-11', '0.00', NULL, NULL\),
\(1002, '238', '2023-11-11', '0.00', NULL, NULL\),
\(1003, '240', '2023-11-11', '0.00', NULL, NULL\),
\(1004, '298', '2023-11-11', '0.00', NULL, NULL\),
\(1005, '493', '2023-11-11', '0.00', NULL, NULL\),
\(1006, '532', '2023-11-11', '0.00', NULL, NULL\),
\(1007, '574', '2023-11-11', '0.00', NULL, NULL\),
\(1008, '591', '2023-11-11', '0.00', NULL, NULL\),
\(1009, '666', '2023-11-11', '0.00', NULL, NULL\),
\(1010, '835', '2023-11-11', '0.00', NULL, NULL\),
\(1011, '852', '2023-11-11', '0.00', NULL, NULL\),
\(1012, '853', '2023-11-11', '0.00', NULL, NULL\),
\(1013, '857', '2023-11-11', '0.00', NULL, NULL\),
\(1014, '932', '2023-11-11', '0.00', NULL, NULL\),
\(1015, '962', '2023-11-11', '0.00', NULL, NULL\),
\(1016, '970', '2023-11-11', '0.00', NULL, NULL\),
\(1017, '971', '2023-11-11', '0.00', NULL, NULL\),
\(1018, '974', '2023-11-11', '0.00', NULL, NULL\),
\(1019, '986', '2023-11-11', '0.00', '1', NULL\),
\(1020, '1032', '2023-11-11', '0.00', NULL, NULL\),
\(1021, '1035', '2023-11-11', '0.00', NULL, NULL\),
\(1022, '1038', '2023-11-11', '0.00', NULL, NULL\),
\(1023, '1046', '2023-11-11', '0.00', NULL, NULL\),
\(1024, '1047', '2023-11-11', '5.00', NULL, NULL\),
\(1025, '1050', '2023-11-11', '0.00', NULL, NULL\),
\(1026, '1056', '2023-11-11', '0.00', NULL, NULL\),
\(1027, '1072', '2023-11-11', '0.00', NULL, NULL\),
\(1028, '1081', '2023-11-11', '0.00', NULL, NULL\),
\(1029, '1083', '2023-11-11', '0.00', NULL, NULL\),
\(1030, '1118', '2023-11-11', '0.00', NULL, NULL\),
\(1031, '1122', '2023-11-11', '0.00', NULL, NULL\),
\(1032, '1129', '2023-11-11', '0.00', NULL, NULL\),
\(1033, '1137', '2023-11-11', '0.00', NULL, NULL\),
\(1034, '1153', '2023-11-11', '0.00', NULL, NULL\),
\(1035, '1163', '2023-11-11', '0.00', NULL, NULL\),
\(1036, '1189', '2023-11-11', '0.00', NULL, NULL\),
\(1037, '1202', '2023-11-11', '0.00', NULL, NULL\),
\(1038, '1216', '2023-11-11', '0.00', NULL, NULL\),
\(1039, '1228', '2023-11-11', '0.00', NULL, NULL\),
\(1040, '1236', '2023-11-11', '0.00', NULL, NULL\),
\(1041, '1241', '2023-11-11', '0.00', NULL, NULL\),
\(1042, '1244', '2023-11-11', '0.00', NULL, NULL\),
\(1043, '1245', '2023-11-11', '0.00', NULL, NULL\),
\(1044, '1291', '2023-11-11', '0.00', NULL, NULL\),
\(1045, '1310', '2023-11-11', '0.00', NULL, NULL\),
\(1046, '1315', '2023-11-11', '0.00', NULL, NULL\),
\(1047, '1317', '2023-11-11', '0.00', NULL, NULL\),
\(1048, '1320', '2023-11-11', '0.00', NULL, NULL\),
\(1049, '1325', '2023-11-11', '0.00', NULL, NULL\),
\(1050, '1340', '2023-11-11', '0.00', NULL, NULL\),
\(1051, '1346', '2023-11-11', '0.00', NULL, NULL\),
\(1052, '1347', '2023-11-11', '0.00', NULL, NULL\),
\(1053, '1352', '2023-11-11', '5.00', NULL, NULL\),
\(1054, '1388', '2023-11-11', '0.00', NULL, NULL\),
\(1055, '1395', '2023-11-11', '0.00', NULL, NULL\),
\(1056, '1397', '2023-11-11', '0.00', NULL, NULL\),
\(1057, '1404', '2023-11-11', '0.00', '1', NULL\),
\(1058, '1406', '2023-11-11', '0.00', NULL, NULL\),
\(1059, '1407', '2023-11-11', '12.00', NULL, NULL\),
\(1060, '1412', '2023-11-11', '0.00', NULL, NULL\),
\(1061, '1413', '2023-11-11', '0.00', NULL, NULL\),
\(1062, '1414', '2023-11-11', '0.00', NULL, NULL\),
\(1063, '1415', '2023-11-11', '0.00', NULL, NULL\),
\(1064, '1417', '2023-11-11', '0.00', NULL, NULL\),
\(1065, '1418', '2023-11-11', '0.00', '4', NULL\),
\(1066, '1420', '2023-11-11', '0.00', '1', NULL\),
\(1067, '1421', '2023-11-11', '0.00', '4', NULL\),
\(1068, '1422', '2023-11-11', '0.00', '4', NULL\);
INSERT INTO \`registro\_horas\_trabajo\` \(\`id\_registro\`, \`legajo\`, \`fecha\`, \`horas\_trabajadas\`, \`centro\_costo\`, \`proceso\`\) VALUES
\(1069, '1423', '2023-11-11', '0.00', '8', NULL\),
\(1070, '1424', '2023-11-11', '12.00', NULL, NULL\),
\(1071, '1425', '2023-11-11', '0.00', NULL, NULL\),
\(1072, '1426', '2023-11-11', '12.00', NULL, NULL\),
\(1073, '1428', '2023-11-11', '0.00', NULL, NULL\),
\(1074, '1430', '2023-11-11', '0.00', NULL, NULL\),
\(1075, '1431', '2023-11-11', '0.00', NULL, NULL\),
\(1076, '1432', '2023-11-11', '0.00', NULL, NULL\),
\(1077, '1441', '2023-11-11', '0.00', NULL, NULL\),
\(1078, '1442', '2023-11-11', '0.00', NULL, NULL\),
\(1079, '1446', '2023-11-11', '0.00', NULL, NULL\),
\(1080, '1456', '2023-11-11', '0.00', NULL, NULL\),
\(1081, '1460', '2023-11-11', '0.00', NULL, NULL\),
\(1082, '1461', '2023-11-11', '0.00', NULL, NULL\),
\(1083, '1466', '2023-11-11', '0.00', NULL, NULL\),
\(1084, '1469', '2023-11-11', '0.00', NULL, NULL\),
\(1085, '1471', '2023-11-11', '0.00', NULL, NULL\),
\(1086, '1474', '2023-11-11', '0.00', NULL, NULL\),
\(1087, '1478', '2023-11-11', '0.00', NULL, NULL\),
\(1088, '1481', '2023-11-11', '0.00', NULL, NULL\),
\(1089, '1482', '2023-11-11', '0.00', NULL, NULL\),
\(1090, '1484', '2023-11-11', '0.00', NULL, NULL\),
\(1091, '1485', '2023-11-11', '0.00', NULL, NULL\),
\(1092, '1487', '2023-11-11', '0.00', NULL, NULL\),
\(1093, '1488', '2023-11-11', '0.00', NULL, NULL\),
\(1094, '1490', '2023-11-11', '12.00', NULL, NULL\),
\(1095, '1491', '2023-11-11', '0.00', NULL, NULL\),
\(1096, '1492', '2023-11-11', '0.00', NULL, NULL\),
\(1097, '1495', '2023-11-11', '0.00', NULL, NULL\),
\(1098, '1496', '2023-11-11', '0.00', NULL, NULL\),
\(1099, '1497', '2023-11-11', '0.00', NULL, NULL\),
\(1100, '1498', '2023-11-11', '0.00', NULL, NULL\),
\(1101, '107', '2023-11-12', '0.00', NULL, NULL\),
\(1102, '238', '2023-11-12', '12.00', '8', NULL\),
\(1103, '240', '2023-11-12', '0.00', NULL, NULL\),
\(1104, '298', '2023-11-12', '0.00', NULL, NULL\),
\(1105, '493', '2023-11-12', '0.00', NULL, NULL\),
\(1106, '532', '2023-11-12', '0.00', NULL, NULL\),
\(1107, '574', '2023-11-12', '0.00', NULL, NULL\),
\(1108, '591', '2023-11-12', '0.00', NULL, NULL\),
\(1109, '666', '2023-11-12', '0.00', NULL, NULL\),
\(1110, '835', '2023-11-12', '0.00', NULL, NULL\),
\(1111, '852', '2023-11-12', '0.00', NULL, NULL\),
\(1112, '853', '2023-11-12', '0.00', NULL, NULL\),
\(1113, '857', '2023-11-12', '0.00', NULL, NULL\),
\(1114, '932', '2023-11-12', '0.00', NULL, NULL\),
\(1115, '962', '2023-11-12', '11.30', NULL, NULL\),
\(1116, '970', '2023-11-12', '0.00', NULL, NULL\),
\(1117, '971', '2023-11-12', '0.00', NULL, NULL\),
\(1118, '974', '2023-11-12', '0.00', NULL, NULL\),
\(1119, '986', '2023-11-12', '0.00', '1', NULL\),
\(1120, '1032', '2023-11-12', '0.00', NULL, NULL\),
\(1121, '1035', '2023-11-12', '0.00', NULL, NULL\),
\(1122, '1038', '2023-11-12', '0.00', NULL, NULL\),
\(1123, '1046', '2023-11-12', '0.00', NULL, NULL\),
\(1124, '1047', '2023-11-12', '0.00', NULL, NULL\),
\(1125, '1050', '2023-11-12', '0.00', NULL, NULL\),
\(1126, '1056', '2023-11-12', '0.00', NULL, NULL\),
\(1127, '1072', '2023-11-12', '0.00', NULL, NULL\),
\(1128, '1081', '2023-11-12', '0.00', NULL, NULL\),
\(1129, '1083', '2023-11-12', '0.00', NULL, NULL\),
\(1130, '1118', '2023-11-12', '0.00', NULL, NULL\),
\(1131, '1122', '2023-11-12', '0.00', NULL, NULL\),
\(1132, '1129', '2023-11-12', '0.00', NULL, NULL\),
\(1133, '1137', '2023-11-12', '0.00', NULL, NULL\),
\(1134, '1153', '2023-11-12', '0.00', NULL, NULL\),
\(1135, '1163', '2023-11-12', '0.00', NULL, NULL\),
\(1136, '1189', '2023-11-12', '0.00', NULL, NULL\),
\(1137, '1202', '2023-11-12', '0.00', NULL, NULL\),
\(1138, '1216', '2023-11-12', '0.00', NULL, NULL\),
\(1139, '1228', '2023-11-12', '0.00', NULL, NULL\),
\(1140, '1236', '2023-11-12', '0.00', NULL, NULL\),
\(1141, '1241', '2023-11-12', '0.00', NULL, NULL\),
\(1142, '1244', '2023-11-12', '0.00', NULL, NULL\),
\(1143, '1245', '2023-11-12', '0.00', NULL, NULL\),
\(1144, '1291', '2023-11-12', '0.00', NULL, NULL\),
\(1145, '1310', '2023-11-12', '0.00', NULL, NULL\),
\(1146, '1315', '2023-11-12', '0.00', NULL, NULL\),
\(1147, '1317', '2023-11-12', '0.00', NULL, NULL\),
\(1148, '1320', '2023-11-12', '0.00', NULL, NULL\),
\(1149, '1325', '2023-11-12', '0.00', NULL, NULL\),
\(1150, '1340', '2023-11-12', '0.00', NULL, NULL\),
\(1151, '1346', '2023-11-12', '0.00', NULL, NULL\),
\(1152, '1347', '2023-11-12', '0.00', NULL, NULL\),
\(1153, '1352', '2023-11-12', '0.00', NULL, NULL\),
\(1154, '1388', '2023-11-12', '0.00', NULL, NULL\),
\(1155, '1395', '2023-11-12', '0.00', NULL, NULL\),
\(1156, '1397', '2023-11-12', '0.00', NULL, NULL\),
\(1157, '1404', '2023-11-12', '0.00', '1', NULL\),
\(1158, '1406', '2023-11-12', '0.00', NULL, NULL\),
\(1159, '1407', '2023-11-12', '0.00', NULL, NULL\),
\(1160, '1412', '2023-11-12', '0.00', NULL, NULL\),
\(1161, '1413', '2023-11-12', '0.00', NULL, NULL\),
\(1162, '1414', '2023-11-12', '0.00', NULL, NULL\),
\(1163, '1415', '2023-11-12', '0.00', NULL, NULL\),
\(1164, '1417', '2023-11-12', '0.00', NULL, NULL\),
\(1165, '1418', '2023-11-12', '0.00', '4', NULL\),
\(1166, '1420', '2023-11-12', '0.00', '1', NULL\),
\(1167, '1421', '2023-11-12', '0.00', '4', NULL\),
\(1168, '1422', '2023-11-12', '0.00', '4', NULL\),
\(1169, '1423', '2023-11-12', '0.00', '8', NULL\),
\(1170, '1424', '2023-11-12', '0.00', NULL, NULL\),
\(1171, '1425', '2023-11-12', '0.00', NULL, NULL\),
\(1172, '1426', '2023-11-12', '0.00', NULL, NULL\),
\(1173, '1428', '2023-11-12', '0.00', NULL, NULL\),
\(1174, '1430', '2023-11-12', '0.00', NULL, NULL\),
\(1175, '1431', '2023-11-12', '0.00', NULL, NULL\),
\(1176, '1432', '2023-11-12', '0.00', NULL, NULL\),
\(1177, '1441', '2023-11-12', '0.00', NULL, NULL\),
\(1178, '1442', '2023-11-12', '0.00', NULL, NULL\),
\(1179, '1446', '2023-11-12', '0.00', NULL, NULL\),
\(1180, '1456', '2023-11-12', '0.00', NULL, NULL\),
\(1181, '1460', '2023-11-12', '0.00', NULL, NULL\),
\(1182, '1461', '2023-11-12', '0.00', NULL, NULL\),
\(1183, '1466', '2023-11-12', '0.00', NULL, NULL\),
\(1184, '1469', '2023-11-12', '0.00', NULL, NULL\),
\(1185, '1471', '2023-11-12', '0.00', NULL, NULL\),
\(1186, '1474', '2023-11-12', '0.00', NULL, NULL\),
\(1187, '1478', '2023-11-12', '0.00', NULL, NULL\),
\(1188, '1481', '2023-11-12', '0.00', NULL, NULL\),
\(1189, '1482', '2023-11-12', '0.00', NULL, NULL\),
\(1190, '1484', '2023-11-12', '0.00', NULL, NULL\),
\(1191, '1485', '2023-11-12', '0.00', NULL, NULL\),
\(1192, '1487', '2023-11-12', '0.00', NULL, NULL\),
\(1193, '1488', '2023-11-12', '0.00', NULL, NULL\),
\(1194, '1490', '2023-11-12', '0.00', NULL, NULL\),
\(1195, '1491', '2023-11-12', '0.00', NULL, NULL\),
\(1196, '1492', '2023-11-12', '0.00', NULL, NULL\),
\(1197, '1495', '2023-11-12', '0.00', NULL, NULL\),
\(1198, '1496', '2023-11-12', '12.00', NULL, NULL\),
\(1199, '1497', '2023-11-12', '10.90', NULL, NULL\),
\(1200, '1498', '2023-11-12', '0.00', NULL, NULL\),
\(1201, '107', '2023-11-13', '0.00', NULL, NULL\),
\(1202, '238', '2023-11-13', '0.00', NULL, NULL\),
\(1203, '240', '2023-11-13', '4.30', NULL, NULL\),
\(1204, '298', '2023-11-13', '6.50', NULL, NULL\),
\(1205, '493', '2023-11-13', '0.00', NULL, NULL\),
\(1206, '532', '2023-11-13', '0.00', NULL, NULL\),
\(1207, '574', '2023-11-13', '8.00', NULL, NULL\),
\(1208, '591', '2023-11-13', '0.00', NULL, NULL\),
\(1209, '666', '2023-11-13', '0.00', NULL, NULL\),
\(1210, '835', '2023-11-13', '0.00', NULL, NULL\),
\(1211, '852', '2023-11-13', '6.00', NULL, NULL\),
\(1212, '853', '2023-11-13', '4.40', NULL, NULL\),
\(1213, '857', '2023-11-13', '0.00', NULL, NULL\),
\(1214, '932', '2023-11-13', '0.00', NULL, NULL\),
\(1215, '962', '2023-11-13', '0.00', NULL, NULL\),
\(1216, '970', '2023-11-13', '6.00', NULL, NULL\),
\(1217, '971', '2023-11-13', '0.00', NULL, NULL\),
\(1218, '974', '2023-11-13', '0.00', NULL, NULL\),
\(1219, '986', '2023-11-13', '8.00', '1', NULL\),
\(1220, '1032', '2023-11-13', '0.00', NULL, NULL\),
\(1221, '1035', '2023-11-13', '8.00', NULL, NULL\),
\(1222, '1038', '2023-11-13', '8.00', NULL, NULL\),
\(1223, '1046', '2023-11-13', '0.00', NULL, NULL\),
\(1224, '1047', '2023-11-13', '8.00', NULL, NULL\),
\(1225, '1050', '2023-11-13', '0.00', NULL, NULL\),
\(1226, '1056', '2023-11-13', '7.00', NULL, NULL\),
\(1227, '1072', '2023-11-13', '4.50', NULL, NULL\),
\(1228, '1081', '2023-11-13', '0.00', NULL, NULL\),
\(1229, '1083', '2023-11-13', '6.80', NULL, NULL\),
\(1230, '1118', '2023-11-13', '0.00', NULL, NULL\),
\(1231, '1122', '2023-11-13', '0.00', NULL, NULL\),
\(1232, '1129', '2023-11-13', '8.00', NULL, NULL\),
\(1233, '1137', '2023-11-13', '0.00', NULL, NULL\),
\(1234, '1153', '2023-11-13', '0.00', NULL, NULL\),
\(1235, '1163', '2023-11-13', '0.00', NULL, NULL\),
\(1236, '1189', '2023-11-13', '9.00', NULL, NULL\),
\(1237, '1202', '2023-11-13', '7.60', NULL, NULL\),
\(1238, '1216', '2023-11-13', '7.00', NULL, NULL\),
\(1239, '1228', '2023-11-13', '8.00', NULL, NULL\),
\(1240, '1236', '2023-11-13', '0.00', NULL, NULL\),
\(1241, '1241', '2023-11-13', '0.00', NULL, NULL\),
\(1242, '1244', '2023-11-13', '0.00', NULL, NULL\),
\(1243, '1245', '2023-11-13', '8.00', NULL, NULL\),
\(1244, '1291', '2023-11-13', '8.00', NULL, NULL\),
\(1245, '1310', '2023-11-13', '8.00', NULL, NULL\),
\(1246, '1315', '2023-11-13', '0.00', NULL, NULL\),
\(1247, '1317', '2023-11-13', '8.00', NULL, NULL\),
\(1248, '1320', '2023-11-13', '0.00', NULL, NULL\),
\(1249, '1325', '2023-11-13', '8.00', NULL, NULL\),
\(1250, '1340', '2023-11-13', '8.00', NULL, NULL\),
\(1251, '1346', '2023-11-13', '6.60', NULL, NULL\),
\(1252, '1347', '2023-11-13', '0.00', NULL, NULL\),
\(1253, '1352', '2023-11-13', '0.00', NULL, NULL\),
\(1254, '1388', '2023-11-13', '6.00', NULL, NULL\),
\(1255, '1395', '2023-11-13', '0.00', NULL, NULL\),
\(1256, '1397', '2023-11-13', '0.00', NULL, NULL\),
\(1257, '1404', '2023-11-13', '3.30', '1', NULL\),
\(1258, '1406', '2023-11-13', '0.00', NULL, NULL\),
\(1259, '1407', '2023-11-13', '6.60', NULL, NULL\),
\(1260, '1412', '2023-11-13', '8.00', NULL, NULL\),
\(1261, '1413', '2023-11-13', '8.00', NULL, NULL\),
\(1262, '1414', '2023-11-13', '6.80', NULL, NULL\),
\(1263, '1415', '2023-11-13', '0.00', NULL, NULL\),
\(1264, '1417', '2023-11-13', '6.20', NULL, NULL\),
\(1265, '1418', '2023-11-13', '0.00', '4', NULL\),
\(1266, '1420', '2023-11-13', '0.00', '1', NULL\),
\(1267, '1421', '2023-11-13', '0.00', '4', NULL\),
\(1268, '1422', '2023-11-13', '7.40', '4', NULL\),
\(1269, '1423', '2023-11-13', '0.00', '8', NULL\),
\(1270, '1424', '2023-11-13', '8.00', NULL, NULL\),
\(1271, '1425', '2023-11-13', '0.00', NULL, NULL\),
\(1272, '1426', '2023-11-13', '0.00', NULL, NULL\),
\(1273, '1428', '2023-11-13', '7.00', NULL, NULL\),
\(1274, '1430', '2023-11-13', '8.00', NULL, NULL\),
\(1275, '1431', '2023-11-13', '0.00', NULL, NULL\),
\(1276, '1432', '2023-11-13', '8.00', NULL, NULL\),
\(1277, '1441', '2023-11-13', '6.50', NULL, NULL\),
\(1278, '1442', '2023-11-13', '8.00', NULL, NULL\),
\(1279, '1446', '2023-11-13', '6.00', NULL, NULL\),
\(1280, '1456', '2023-11-13', '8.00', NULL, NULL\),
\(1281, '1460', '2023-11-13', '0.00', NULL, NULL\),
\(1282, '1461', '2023-11-13', '6.00', NULL, NULL\),
\(1283, '1466', '2023-11-13', '0.00', NULL, NULL\),
\(1284, '1469', '2023-11-13', '0.00', NULL, NULL\),
\(1285, '1471', '2023-11-13', '0.00', NULL, NULL\),
\(1286, '1474', '2023-11-13', '0.00', NULL, NULL\),
\(1287, '1478', '2023-11-13', '0.00', NULL, NULL\),
\(1288, '1481', '2023-11-13', '0.00', NULL, NULL\),
\(1289, '1482', '2023-11-13', '0.00', NULL, NULL\),
\(1290, '1484', '2023-11-13', '8.00', NULL, NULL\),
\(1291, '1485', '2023-11-13', '7.00', NULL, NULL\),
\(1292, '1487', '2023-11-13', '6.00', NULL, NULL\),
\(1293, '1488', '2023-11-13', '8.00', NULL, NULL\),
\(1294, '1490', '2023-11-13', '0.00', NULL, NULL\),
\(1295, '1491', '2023-11-13', '0.00', NULL, NULL\),
\(1296, '1492', '2023-11-13', '8.00', NULL, NULL\),
\(1297, '1495', '2023-11-13', '4.00', NULL, NULL\),
\(1298, '1496', '2023-11-13', '6.70', NULL, NULL\),
\(1299, '1497', '2023-11-13', '0.00', NULL, NULL\),
\(1300, '1498', '2023-11-13', '8.00', NULL, NULL\),
\(1301, '107', '2023-11-14', '8.00', NULL, NULL\),
\(1302, '238', '2023-11-14', '8.00', NULL, NULL\),
\(1303, '240', '2023-11-14', '5.50', NULL, NULL\),
\(1304, '298', '2023-11-14', '7.50', NULL, NULL\),
\(1305, '493', '2023-11-14', '0.00', NULL, NULL\),
\(1306, '532', '2023-11-14', '0.00', NULL, NULL\),
\(1307, '574', '2023-11-14', '8.00', NULL, NULL\),
\(1308, '591', '2023-11-14', '0.00', NULL, NULL\),
\(1309, '666', '2023-11-14', '0.00', NULL, NULL\),
\(1310, '835', '2023-11-14', '0.00', NULL, NULL\),
\(1311, '852', '2023-11-14', '6.00', NULL, NULL\),
\(1312, '853', '2023-11-14', '6.90', NULL, NULL\),
\(1313, '857', '2023-11-14', '0.00', NULL, NULL\),
\(1314, '932', '2023-11-14', '0.00', NULL, NULL\),
\(1315, '962', '2023-11-14', '8.00', NULL, NULL\),
\(1316, '970', '2023-11-14', '9.00', NULL, NULL\),
\(1317, '971', '2023-11-14', '0.00', NULL, NULL\),
\(1318, '974', '2023-11-14', '0.00', NULL, NULL\),
\(1319, '986', '2023-11-14', '8.00', '1', NULL\),
\(1320, '1032', '2023-11-14', '0.00', NULL, NULL\),
\(1321, '1035', '2023-11-14', '8.00', NULL, NULL\),
\(1322, '1038', '2023-11-14', '8.00', NULL, NULL\),
\(1323, '1046', '2023-11-14', '8.00', '3', NULL\),
\(1324, '1047', '2023-11-14', '8.00', NULL, NULL\),
\(1325, '1050', '2023-11-14', '0.00', NULL, NULL\),
\(1326, '1056', '2023-11-14', '8.00', NULL, NULL\),
\(1327, '1072', '2023-11-14', '4.50', NULL, NULL\),
\(1328, '1081', '2023-11-14', '0.00', NULL, NULL\),
\(1329, '1083', '2023-11-14', '8.00', NULL, NULL\),
\(1330, '1118', '2023-11-14', '0.00', NULL, NULL\),
\(1331, '1122', '2023-11-14', '0.00', NULL, NULL\),
\(1332, '1129', '2023-11-14', '8.00', NULL, NULL\),
\(1333, '1137', '2023-11-14', '0.00', NULL, NULL\),
\(1334, '1153', '2023-11-14', '0.00', NULL, NULL\),
\(1335, '1163', '2023-11-14', '0.00', NULL, NULL\),
\(1336, '1189', '2023-11-14', '7.00', NULL, NULL\),
\(1337, '1202', '2023-11-14', '7.50', NULL, NULL\),
\(1338, '1216', '2023-11-14', '7.50', NULL, NULL\),
\(1339, '1228', '2023-11-14', '2.20', NULL, NULL\),
\(1340, '1236', '2023-11-14', '0.00', NULL, NULL\),
\(1341, '1241', '2023-11-14', '6.30', NULL, NULL\),
\(1342, '1244', '2023-11-14', '0.00', NULL, NULL\),
\(1343, '1245', '2023-11-14', '7.00', NULL, NULL\),
\(1344, '1291', '2023-11-14', '8.00', NULL, NULL\),
\(1345, '1310', '2023-11-14', '8.00', NULL, NULL\),
\(1346, '1315', '2023-11-14', '0.00', NULL, NULL\),
\(1347, '1317', '2023-11-14', '8.00', NULL, NULL\),
\(1348, '1320', '2023-11-14', '6.50', NULL, NULL\),
\(1349, '1325', '2023-11-14', '8.00', NULL, NULL\),
\(1350, '1340', '2023-11-14', '8.00', NULL, NULL\),
\(1351, '1346', '2023-11-14', '8.00', NULL, NULL\),
\(1352, '1347', '2023-11-14', '0.00', NULL, NULL\),
\(1353, '1352', '2023-11-14', '0.00', NULL, NULL\),
\(1354, '1388', '2023-11-14', '8.00', NULL, NULL\),
\(1355, '1395', '2023-11-14', '8.00', '1', NULL\),
\(1356, '1397', '2023-11-14', '0.00', NULL, NULL\),
\(1357, '1404', '2023-11-14', '0.00', '1', NULL\),
\(1358, '1406', '2023-11-14', '8.00', NULL, NULL\),
\(1359, '1407', '2023-11-14', '0.00', NULL, NULL\),
\(1360, '1412', '2023-11-14', '8.00', NULL, NULL\),
\(1361, '1413', '2023-11-14', '8.00', NULL, NULL\),
\(1362, '1414', '2023-11-14', '7.00', NULL, NULL\),
\(1363, '1415', '2023-11-14', '6.60', NULL, NULL\),
\(1364, '1417', '2023-11-14', '7.70', NULL, NULL\),
\(1365, '1418', '2023-11-14', '8.00', '4', NULL\),
\(1366, '1420', '2023-11-14', '5.80', '1', NULL\),
\(1367, '1421', '2023-11-14', '6.30', '4', NULL\),
\(1368, '1422', '2023-11-14', '7.30', '4', NULL\),
\(1369, '1423', '2023-11-14', '0.00', '8', NULL\),
\(1370, '1424', '2023-11-14', '8.00', NULL, NULL\),
\(1371, '1425', '2023-11-14', '0.00', NULL, NULL\),
\(1372, '1426', '2023-11-14', '0.00', NULL, NULL\),
\(1373, '1428', '2023-11-14', '7.50', NULL, NULL\),
\(1374, '1430', '2023-11-14', '0.00', NULL, NULL\),
\(1375, '1431', '2023-11-14', '7.30', NULL, NULL\),
\(1376, '1432', '2023-11-14', '0.00', NULL, NULL\),
\(1377, '1441', '2023-11-14', '10.00', NULL, NULL\),
\(1378, '1442', '2023-11-14', '8.00', NULL, NULL\),
\(1379, '1446', '2023-11-14', '8.00', NULL, NULL\),
\(1380, '1456', '2023-11-14', '0.00', NULL, NULL\),
\(1381, '1460', '2023-11-14', '0.00', NULL, NULL\),
\(1382, '1461', '2023-11-14', '8.80', NULL, NULL\),
\(1383, '1466', '2023-11-14', '0.00', NULL, NULL\),
\(1384, '1469', '2023-11-14', '8.40', NULL, NULL\),
\(1385, '1471', '2023-11-14', '7.50', NULL, NULL\),
\(1386, '1474', '2023-11-14', '8.50', NULL, NULL\),
\(1387, '1478', '2023-11-14', '8.00', NULL, NULL\),
\(1388, '1481', '2023-11-14', '7.50', NULL, NULL\),
\(1389, '1482', '2023-11-14', '0.00', NULL, NULL\),
\(1390, '1484', '2023-11-14', '0.00', NULL, NULL\),
\(1391, '1485', '2023-11-14', '0.00', NULL, NULL\),
\(1392, '1487', '2023-11-14', '7.60', NULL, NULL\),
\(1393, '1488', '2023-11-14', '6.00', NULL, NULL\),
\(1394, '1490', '2023-11-14', '0.00', NULL, NULL\),
\(1395, '1491', '2023-11-14', '0.00', NULL, NULL\),
\(1396, '1492', '2023-11-14', '9.00', NULL, NULL\),
\(1397, '1495', '2023-11-14', '0.00', NULL, NULL\),
\(1398, '1496', '2023-11-14', '5.80', NULL, NULL\),
\(1399, '1497', '2023-11-14', '8.00', NULL, NULL\),
\(1400, '1498', '2023-11-14', '8.00', NULL, NULL\),
\(1401, '107', '2023-11-15', '8.00', NULL, NULL\),
\(1402, '238', '2023-11-15', '8.00', NULL, NULL\),
\(1403, '240', '2023-11-15', '6.00', NULL, NULL\),
\(1404, '298', '2023-11-15', '8.00', NULL, NULL\),
\(1405, '493', '2023-11-15', '0.00', NULL, NULL\),
\(1406, '532', '2023-11-15', '0.00', NULL, NULL\),
\(1407, '574', '2023-11-15', '8.00', NULL, NULL\),
\(1408, '591', '2023-11-15', '0.00', NULL, NULL\),
\(1409, '666', '2023-11-15', '0.00', NULL, NULL\),
\(1410, '835', '2023-11-15', '0.00', NULL, NULL\),
\(1411, '852', '2023-11-15', '6.00', NULL, NULL\),
\(1412, '853', '2023-11-15', '0.00', NULL, NULL\),
\(1413, '857', '2023-11-15', '0.00', NULL, NULL\),
\(1414, '932', '2023-11-15', '0.00', NULL, NULL\),
\(1415, '962', '2023-11-15', '8.00', NULL, NULL\),
\(1416, '970', '2023-11-15', '8.90', NULL, NULL\),
\(1417, '971', '2023-11-15', '0.00', NULL, NULL\),
\(1418, '974', '2023-11-15', '0.00', NULL, NULL\),
\(1419, '986', '2023-11-15', '8.00', '1', NULL\),
\(1420, '1032', '2023-11-15', '0.00', NULL, NULL\),
\(1421, '1035', '2023-11-15', '8.50', NULL, NULL\),
\(1422, '1038', '2023-11-15', '8.00', NULL, NULL\),
\(1423, '1046', '2023-11-15', '8.00', '3', NULL\),
\(1424, '1047', '2023-11-15', '8.00', NULL, NULL\),
\(1425, '1050', '2023-11-15', '0.00', NULL, NULL\),
\(1426, '1056', '2023-11-15', '7.30', NULL, NULL\),
\(1427, '1072', '2023-11-15', '4.50', NULL, NULL\),
\(1428, '1081', '2023-11-15', '0.00', NULL, NULL\),
\(1429, '1083', '2023-11-15', '8.00', NULL, NULL\),
\(1430, '1118', '2023-11-15', '0.00', NULL, NULL\),
\(1431, '1122', '2023-11-15', '0.00', NULL, NULL\),
\(1432, '1129', '2023-11-15', '8.00', NULL, NULL\),
\(1433, '1137', '2023-11-15', '0.00', NULL, NULL\),
\(1434, '1153', '2023-11-15', '0.00', NULL, NULL\),
\(1435, '1163', '2023-11-15', '0.00', NULL, NULL\),
\(1436, '1189', '2023-11-15', '6.00', NULL, NULL\),
\(1437, '1202', '2023-11-15', '7.00', NULL, NULL\),
\(1438, '1216', '2023-11-15', '5.10', NULL, NULL\),
\(1439, '1228', '2023-11-15', '7.00', NULL, NULL\),
\(1440, '1236', '2023-11-15', '0.00', NULL, NULL\),
\(1441, '1241', '2023-11-15', '0.00', NULL, NULL\),
\(1442, '1244', '2023-11-15', '0.00', NULL, NULL\),
\(1443, '1245', '2023-11-15', '8.00', NULL, NULL\),
\(1444, '1291', '2023-11-15', '8.00', NULL, NULL\),
\(1445, '1310', '2023-11-15', '7.00', NULL, NULL\),
\(1446, '1315', '2023-11-15', '0.00', NULL, NULL\),
\(1447, '1317', '2023-11-15', '7.00', NULL, NULL\),
\(1448, '1320', '2023-11-15', '7.30', NULL, NULL\),
\(1449, '1325', '2023-11-15', '8.00', NULL, NULL\),
\(1450, '1340', '2023-11-15', '8.00', NULL, NULL\),
\(1451, '1346', '2023-11-15', '8.00', NULL, NULL\),
\(1452, '1347', '2023-11-15', '0.00', NULL, NULL\),
\(1453, '1352', '2023-11-15', '0.00', NULL, NULL\),
\(1454, '1388', '2023-11-15', '7.00', NULL, NULL\),
\(1455, '1395', '2023-11-15', '0.00', NULL, NULL\),
\(1456, '1397', '2023-11-15', '8.00', NULL, NULL\),
\(1457, '1404', '2023-11-15', '0.00', '1', NULL\),
\(1458, '1406', '2023-11-15', '8.00', NULL, NULL\),
\(1459, '1407', '2023-11-15', '5.80', NULL, NULL\),
\(1460, '1412', '2023-11-15', '8.00', NULL, NULL\),
\(1461, '1413', '2023-11-15', '8.00', NULL, NULL\),
\(1462, '1414', '2023-11-15', '0.00', NULL, NULL\),
\(1463, '1415', '2023-11-15', '6.60', NULL, NULL\),
\(1464, '1417', '2023-11-15', '6.00', NULL, NULL\),
\(1465, '1418', '2023-11-15', '8.00', '4', NULL\),
\(1466, '1420', '2023-11-15', '7.50', '1', NULL\),
\(1467, '1421', '2023-11-15', '9.90', '4', NULL\),
\(1468, '1422', '2023-11-15', '7.50', '4', NULL\),
\(1469, '1423', '2023-11-15', '8.00', '8', NULL\),
\(1470, '1424', '2023-11-15', '8.00', NULL, NULL\),
\(1471, '1425', '2023-11-15', '0.00', NULL, NULL\),
\(1472, '1426', '2023-11-15', '0.00', NULL, NULL\),
\(1473, '1428', '2023-11-15', '8.00', NULL, NULL\),
\(1474, '1430', '2023-11-15', '0.00', NULL, NULL\),
\(1475, '1431', '2023-11-15', '8.00', NULL, NULL\),
\(1476, '1432', '2023-11-15', '8.00', NULL, NULL\),
\(1477, '1441', '2023-11-15', '8.00', NULL, NULL\),
\(1478, '1442', '2023-11-15', '0.00', NULL, NULL\),
\(1479, '1446', '2023-11-15', '4.60', NULL, NULL\),
\(1480, '1456', '2023-11-15', '8.00', NULL, NULL\),
\(1481, '1460', '2023-11-15', '5.40', NULL, NULL\),
\(1482, '1461', '2023-11-15', '7.50', NULL, NULL\),
\(1483, '1466', '2023-11-15', '5.70', NULL, NULL\),
\(1484, '1469', '2023-11-15', '9.00', NULL, NULL\),
\(1485, '1471', '2023-11-15', '8.00', NULL, NULL\),
\(1486, '1474', '2023-11-15', '9.50', NULL, NULL\),
\(1487, '1478', '2023-11-15', '8.00', NULL, NULL\),
\(1488, '1481', '2023-11-15', '5.40', NULL, NULL\),
\(1489, '1482', '2023-11-15', '0.00', NULL, NULL\),
\(1490, '1484', '2023-11-15', '8.00', NULL, NULL\),
\(1491, '1485', '2023-11-15', '0.00', NULL, NULL\),
\(1492, '1487', '2023-11-15', '6.60', NULL, NULL\),
\(1493, '1488', '2023-11-15', '8.70', NULL, NULL\),
\(1494, '1490', '2023-11-15', '4.50', NULL, NULL\),
\(1495, '1491', '2023-11-15', '0.00', NULL, NULL\),
\(1496, '1492', '2023-11-15', '8.00', NULL, NULL\),
\(1497, '1495', '2023-11-15', '8.00', NULL, NULL\),
\(1498, '1496', '2023-11-15', '6.00', NULL, NULL\),
\(1499, '1497', '2023-11-15', '8.00', NULL, NULL\),
\(1500, '1498', '2023-11-15', '0.00', NULL, NULL\),
\(1501, '107', '2023-11-16', '8.00', NULL, NULL\),
\(1502, '238', '2023-11-16', '8.00', NULL, NULL\),
\(1503, '240', '2023-11-16', '7.00', NULL, NULL\),
\(1504, '298', '2023-11-16', '8.00', NULL, NULL\),
\(1505, '493', '2023-11-16', '0.00', NULL, NULL\),
\(1506, '532', '2023-11-16', '0.00', NULL, NULL\),
\(1507, '574', '2023-11-16', '8.00', NULL, NULL\),
\(1508, '591', '2023-11-16', '0.00', NULL, NULL\),
\(1509, '666', '2023-11-16', '0.00', NULL, NULL\),
\(1510, '835', '2023-11-16', '0.00', NULL, NULL\),
\(1511, '852', '2023-11-16', '8.00', NULL, NULL\),
\(1512, '853', '2023-11-16', '5.50', NULL, NULL\),
\(1513, '857', '2023-11-16', '0.00', NULL, NULL\),
\(1514, '932', '2023-11-16', '8.00', NULL, NULL\),
\(1515, '962', '2023-11-16', '8.00', NULL, NULL\),
\(1516, '970', '2023-11-16', '8.00', NULL, NULL\),
\(1517, '971', '2023-11-16', '0.00', NULL, NULL\),
\(1518, '974', '2023-11-16', '0.00', NULL, NULL\),
\(1519, '986', '2023-11-16', '8.00', '1', NULL\),
\(1520, '1032', '2023-11-16', '0.00', NULL, NULL\),
\(1521, '1035', '2023-11-16', '8.00', NULL, NULL\),
\(1522, '1038', '2023-11-16', '8.00', NULL, NULL\),
\(1523, '1046', '2023-11-16', '8.00', '3', NULL\),
\(1524, '1047', '2023-11-16', '8.00', NULL, NULL\),
\(1525, '1050', '2023-11-16', '0.00', NULL, NULL\),
\(1526, '1056', '2023-11-16', '5.80', NULL, NULL\),
\(1527, '1072', '2023-11-16', '4.50', NULL, NULL\),
\(1528, '1081', '2023-11-16', '0.00', NULL, NULL\),
\(1529, '1083', '2023-11-16', '6.60', NULL, NULL\),
\(1530, '1118', '2023-11-16', '0.00', NULL, NULL\),
\(1531, '1122', '2023-11-16', '0.00', NULL, NULL\),
\(1532, '1129', '2023-11-16', '8.00', NULL, NULL\),
\(1533, '1137', '2023-11-16', '0.00', NULL, NULL\),
\(1534, '1153', '2023-11-16', '0.00', NULL, NULL\),
\(1535, '1163', '2023-11-16', '0.00', NULL, NULL\),
\(1536, '1189', '2023-11-16', '0.00', NULL, NULL\),
\(1537, '1202', '2023-11-16', '5.70', NULL, NULL\),
\(1538, '1216', '2023-11-16', '5.00', NULL, NULL\),
\(1539, '1228', '2023-11-16', '8.00', NULL, NULL\),
\(1540, '1236', '2023-11-16', '0.00', NULL, NULL\),
\(1541, '1241', '2023-11-16', '8.20', NULL, NULL\),
\(1542, '1244', '2023-11-16', '0.00', NULL, NULL\),
\(1543, '1245', '2023-11-16', '8.00', NULL, NULL\),
\(1544, '1291', '2023-11-16', '8.00', NULL, NULL\),
\(1545, '1310', '2023-11-16', '7.40', NULL, NULL\),
\(1546, '1315', '2023-11-16', '0.00', NULL, NULL\),
\(1547, '1317', '2023-11-16', '7.00', NULL, NULL\),
\(1548, '1320', '2023-11-16', '10.00', NULL, NULL\),
\(1549, '1325', '2023-11-16', '8.00', NULL, NULL\),
\(1550, '1340', '2023-11-16', '8.00', NULL, NULL\),
\(1551, '1346', '2023-11-16', '7.50', NULL, NULL\),
\(1552, '1347', '2023-11-16', '0.00', NULL, NULL\),
\(1553, '1352', '2023-11-16', '0.00', NULL, NULL\),
\(1554, '1388', '2023-11-16', '10.30', NULL, NULL\),
\(1555, '1395', '2023-11-16', '4.30', '2', NULL\),
\(1556, '1397', '2023-11-16', '7.50', NULL, NULL\),
\(1557, '1404', '2023-11-16', '0.00', '1', NULL\),
\(1558, '1406', '2023-11-16', '5.50', NULL, NULL\),
\(1559, '1407', '2023-11-16', '8.00', NULL, NULL\),
\(1560, '1412', '2023-11-16', '8.00', NULL, NULL\),
\(1561, '1413', '2023-11-16', '8.00', NULL, NULL\),
\(1562, '1414', '2023-11-16', '5.30', NULL, NULL\),
\(1563, '1415', '2023-11-16', '5.30', NULL, NULL\),
\(1564, '1417', '2023-11-16', '3.20', NULL, NULL\),
\(1565, '1418', '2023-11-16', '6.00', '4', NULL\),
\(1566, '1420', '2023-11-16', '10.30', '1', NULL\),
\(1567, '1421', '2023-11-16', '4.30', '4', NULL\),
\(1568, '1422', '2023-11-16', '9.30', '4', NULL\),
\(1569, '1423', '2023-11-16', '8.00', '8', NULL\),
\(1570, '1424', '2023-11-16', '8.00', NULL, NULL\),
\(1571, '1425', '2023-11-16', '8.00', '1', NULL\),
\(1572, '1426', '2023-11-16', '0.00', NULL, NULL\),
\(1573, '1428', '2023-11-16', '9.50', NULL, NULL\),
\(1574, '1430', '2023-11-16', '0.00', NULL, NULL\),
\(1575, '1431', '2023-11-16', '8.00', NULL, NULL\),
\(1576, '1432', '2023-11-16', '8.00', NULL, NULL\),
\(1577, '1441', '2023-11-16', '8.00', NULL, NULL\),
\(1578, '1442', '2023-11-16', '7.00', NULL, NULL\),
\(1579, '1446', '2023-11-16', '5.10', NULL, NULL\),
\(1580, '1456', '2023-11-16', '10.80', NULL, NULL\),
\(1581, '1460', '2023-11-16', '5.50', NULL, NULL\),
\(1582, '1461', '2023-11-16', '7.80', NULL, NULL\),
\(1583, '1466', '2023-11-16', '7.10', NULL, NULL\),
\(1584, '1469', '2023-11-16', '8.00', NULL, NULL\),
\(1585, '1471', '2023-11-16', '6.00', NULL, NULL\),
\(1586, '1474', '2023-11-16', '8.50', NULL, NULL\),
\(1587, '1478', '2023-11-16', '8.50', NULL, NULL\),
\(1588, '1481', '2023-11-16', '7.30', NULL, NULL\),
\(1589, '1482', '2023-11-16', '10.30', NULL, NULL\),
\(1590, '1484', '2023-11-16', '8.00', NULL, NULL\),
\(1591, '1485', '2023-11-16', '0.00', NULL, NULL\),
\(1592, '1487', '2023-11-16', '6.00', NULL, NULL\),
\(1593, '1488', '2023-11-16', '9.50', NULL, NULL\),
\(1594, '1490', '2023-11-16', '4.00', NULL, NULL\),
\(1595, '1491', '2023-11-16', '0.00', NULL, NULL\),
\(1596, '1492', '2023-11-16', '9.00', NULL, NULL\),
\(1597, '1495', '2023-11-16', '5.20', NULL, NULL\),
\(1598, '1496', '2023-11-16', '0.00', NULL, NULL\),
\(1599, '1497', '2023-11-16', '5.00', NULL, NULL\),
\(1600, '1498', '2023-11-16', '8.00', NULL, NULL\),
\(1601, '107', '2023-11-17', '8.00', NULL, NULL\),
\(1602, '238', '2023-11-17', '8.00', NULL, NULL\),
\(1603, '240', '2023-11-17', '0.00', NULL, NULL\),
\(1604, '298', '2023-11-17', '5.00', NULL, NULL\),
\(1605, '493', '2023-11-17', '0.00', NULL, NULL\),
\(1606, '532', '2023-11-17', '0.00', NULL, NULL\),
\(1607, '574', '2023-11-17', '0.00', NULL, NULL\),
\(1608, '591', '2023-11-17', '0.00', NULL, NULL\),
\(1609, '666', '2023-11-17', '0.00', NULL, NULL\),
\(1610, '835', '2023-11-17', '0.00', NULL, NULL\),
\(1611, '852', '2023-11-17', '0.00', NULL, NULL\),
\(1612, '853', '2023-11-17', '5.50', NULL, NULL\),
\(1613, '857', '2023-11-17', '0.00', NULL, NULL\),
\(1614, '932', '2023-11-17', '0.00', NULL, NULL\),
\(1615, '962', '2023-11-17', '8.00', NULL, NULL\),
\(1616, '970', '2023-11-17', '0.00', NULL, NULL\),
\(1617, '971', '2023-11-17', '0.00', NULL, NULL\),
\(1618, '974', '2023-11-17', '0.00', NULL, NULL\),
\(1619, '986', '2023-11-17', '8.00', '1', NULL\),
\(1620, '1032', '2023-11-17', '0.00', NULL, NULL\),
\(1621, '1035', '2023-11-17', '5.20', NULL, NULL\),
\(1622, '1038', '2023-11-17', '0.00', NULL, NULL\),
\(1623, '1046', '2023-11-17', '0.00', NULL, NULL\),
\(1624, '1047', '2023-11-17', '8.00', NULL, NULL\),
\(1625, '1050', '2023-11-17', '0.00', NULL, NULL\),
\(1626, '1056', '2023-11-17', '6.70', NULL, NULL\),
\(1627, '1072', '2023-11-17', '0.00', NULL, NULL\),
\(1628, '1081', '2023-11-17', '0.00', NULL, NULL\),
\(1629, '1083', '2023-11-17', '7.30', NULL, NULL\),
\(1630, '1118', '2023-11-17', '0.00', NULL, NULL\),
\(1631, '1122', '2023-11-17', '0.00', NULL, NULL\),
\(1632, '1129', '2023-11-17', '0.00', NULL, NULL\),
\(1633, '1137', '2023-11-17', '0.00', NULL, NULL\),
\(1634, '1153', '2023-11-17', '0.00', NULL, NULL\),
\(1635, '1163', '2023-11-17', '0.00', NULL, NULL\),
\(1636, '1189', '2023-11-17', '0.00', NULL, NULL\),
\(1637, '1202', '2023-11-17', '7.00', NULL, NULL\),
\(1638, '1216', '2023-11-17', '7.00', NULL, NULL\),
\(1639, '1228', '2023-11-17', '0.00', NULL, NULL\),
\(1640, '1236', '2023-11-17', '0.00', NULL, NULL\),
\(1641, '1241', '2023-11-17', '0.00', NULL, NULL\),
\(1642, '1244', '2023-11-17', '0.00', NULL, NULL\),
\(1643, '1245', '2023-11-17', '8.00', NULL, NULL\),
\(1644, '1291', '2023-11-17', '0.00', NULL, NULL\),
\(1645, '1310', '2023-11-17', '3.60', NULL, NULL\),
\(1646, '1315', '2023-11-17', '0.00', NULL, NULL\),
\(1647, '1317', '2023-11-17', '7.00', NULL, NULL\),
\(1648, '1320', '2023-11-17', '11.00', NULL, NULL\),
\(1649, '1325', '2023-11-17', '7.00', NULL, NULL\),
\(1650, '1340', '2023-11-17', '0.00', NULL, NULL\),
\(1651, '1346', '2023-11-17', '5.30', NULL, NULL\),
\(1652, '1347', '2023-11-17', '0.00', NULL, NULL\),
\(1653, '1352', '2023-11-17', '0.00', NULL, NULL\),
\(1654, '1388', '2023-11-17', '6.00', NULL, NULL\),
\(1655, '1395', '2023-11-17', '0.00', NULL, NULL\),
\(1656, '1397', '2023-11-17', '0.00', NULL, NULL\),
\(1657, '1404', '2023-11-17', '0.00', '1', NULL\),
\(1658, '1406', '2023-11-17', '0.00', NULL, NULL\),
\(1659, '1407', '2023-11-17', '5.00', NULL, NULL\),
\(1660, '1412', '2023-11-17', '0.00', NULL, NULL\),
\(1661, '1413', '2023-11-17', '8.00', NULL, NULL\),
\(1662, '1414', '2023-11-17', '6.40', NULL, NULL\),
\(1663, '1415', '2023-11-17', '0.00', NULL, NULL\),
\(1664, '1417', '2023-11-17', '5.30', NULL, NULL\),
\(1665, '1418', '2023-11-17', '0.00', '4', NULL\),
\(1666, '1420', '2023-11-17', '3.00', '1', NULL\),
\(1667, '1421', '2023-11-17', '7.80', '4', NULL\),
\(1668, '1422', '2023-11-17', '6.80', '4', NULL\),
\(1669, '1423', '2023-11-17', '8.00', '8', NULL\),
\(1670, '1424', '2023-11-17', '0.00', NULL, NULL\),
\(1671, '1425', '2023-11-17', '0.00', NULL, NULL\),
\(1672, '1426', '2023-11-17', '0.00', NULL, NULL\),
\(1673, '1428', '2023-11-17', '8.00', NULL, NULL\),
\(1674, '1430', '2023-11-17', '0.00', NULL, NULL\),
\(1675, '1431', '2023-11-17', '0.00', NULL, NULL\),
\(1676, '1432', '2023-11-17', '0.00', NULL, NULL\),
\(1677, '1441', '2023-11-17', '6.50', NULL, NULL\),
\(1678, '1442', '2023-11-17', '4.00', NULL, NULL\),
\(1679, '1446', '2023-11-17', '5.70', NULL, NULL\),
\(1680, '1456', '2023-11-17', '4.00', NULL, NULL\),
\(1681, '1460', '2023-11-17', '12.00', NULL, NULL\),
\(1682, '1461', '2023-11-17', '2.00', NULL, NULL\),
\(1683, '1466', '2023-11-17', '0.00', NULL, NULL\),
\(1684, '1469', '2023-11-17', '0.00', NULL, NULL\),
\(1685, '1471', '2023-11-17', '0.00', NULL, NULL\),
\(1686, '1474', '2023-11-17', '0.00', NULL, NULL\),
\(1687, '1478', '2023-11-17', '0.00', NULL, NULL\),
\(1688, '1481', '2023-11-17', '10.80', NULL, NULL\),
\(1689, '1482', '2023-11-17', '0.00', NULL, NULL\),
\(1690, '1484', '2023-11-17', '8.00', NULL, NULL\),
\(1691, '1485', '2023-11-17', '0.00', NULL, NULL\),
\(1692, '1487', '2023-11-17', '5.80', NULL, NULL\),
\(1693, '1488', '2023-11-17', '0.00', NULL, NULL\),
\(1694, '1490', '2023-11-17', '9.70', NULL, NULL\),
\(1695, '1491', '2023-11-17', '4.00', NULL, NULL\),
\(1696, '1492', '2023-11-17', '0.00', NULL, NULL\),
\(1697, '1495', '2023-11-17', '6.00', NULL, NULL\),
\(1698, '1496', '2023-11-17', '0.00', NULL, NULL\),
\(1699, '1497', '2023-11-17', '0.00', NULL, NULL\),
\(1700, '1498', '2023-11-17', '8.00', NULL, NULL\),
\(1701, '107', '2023-11-18', '5.00', NULL, NULL\),
\(1702, '238', '2023-11-18', '0.00', NULL, NULL\),
\(1703, '240', '2023-11-18', '9.00', NULL, NULL\),
\(1704, '298', '2023-11-18', '12.00', NULL, NULL\),
\(1705, '493', '2023-11-18', '0.00', NULL, NULL\),
\(1706, '532', '2023-11-18', '0.00', NULL, NULL\),
\(1707, '574', '2023-11-18', '0.00', NULL, NULL\),
\(1708, '591', '2023-11-18', '0.00', NULL, NULL\),
\(1709, '666', '2023-11-18', '0.00', NULL, NULL\),
\(1710, '835', '2023-11-18', '0.00', NULL, NULL\),
\(1711, '852', '2023-11-18', '0.00', NULL, NULL\),
\(1712, '853', '2023-11-18', '11.00', NULL, NULL\),
\(1713, '857', '2023-11-18', '0.00', NULL, NULL\),
\(1714, '932', '2023-11-18', '0.00', NULL, NULL\),
\(1715, '962', '2023-11-18', '0.00', NULL, NULL\),
\(1716, '970', '2023-11-18', '0.00', NULL, NULL\),
\(1717, '971', '2023-11-18', '0.00', NULL, NULL\),
\(1718, '974', '2023-11-18', '0.00', NULL, NULL\),
\(1719, '986', '2023-11-18', '12.00', '8', NULL\),
\(1720, '1032', '2023-11-18', '0.00', NULL, NULL\),
\(1721, '1035', '2023-11-18', '0.00', NULL, NULL\),
\(1722, '1038', '2023-11-18', '0.00', NULL, NULL\),
\(1723, '1046', '2023-11-18', '5.00', '3', NULL\),
\(1724, '1047', '2023-11-18', '0.00', NULL, NULL\),
\(1725, '1050', '2023-11-18', '0.00', NULL, NULL\),
\(1726, '1056', '2023-11-18', '0.00', NULL, NULL\),
\(1727, '1072', '2023-11-18', '0.00', NULL, NULL\),
\(1728, '1081', '2023-11-18', '0.00', NULL, NULL\),
\(1729, '1083', '2023-11-18', '0.00', NULL, NULL\),
\(1730, '1118', '2023-11-18', '0.00', NULL, NULL\),
\(1731, '1122', '2023-11-18', '0.00', NULL, NULL\),
\(1732, '1129', '2023-11-18', '0.00', NULL, NULL\),
\(1733, '1137', '2023-11-18', '0.00', NULL, NULL\),
\(1734, '1153', '2023-11-18', '0.00', NULL, NULL\),
\(1735, '1163', '2023-11-18', '0.00', NULL, NULL\),
\(1736, '1189', '2023-11-18', '0.00', NULL, NULL\),
\(1737, '1202', '2023-11-18', '0.00', NULL, NULL\),
\(1738, '1216', '2023-11-18', '0.00', NULL, NULL\),
\(1739, '1228', '2023-11-18', '0.00', NULL, NULL\),
\(1740, '1236', '2023-11-18', '0.00', NULL, NULL\),
\(1741, '1241', '2023-11-18', '0.00', NULL, NULL\),
\(1742, '1244', '2023-11-18', '0.00', NULL, NULL\),
\(1743, '1245', '2023-11-18', '2.40', NULL, NULL\),
\(1744, '1291', '2023-11-18', '0.00', NULL, NULL\),
\(1745, '1310', '2023-11-18', '0.00', NULL, NULL\),
\(1746, '1315', '2023-11-18', '0.00', NULL, NULL\),
\(1747, '1317', '2023-11-18', '0.00', NULL, NULL\),
\(1748, '1320', '2023-11-18', '10.00', NULL, NULL\),
\(1749, '1325', '2023-11-18', '0.00', NULL, NULL\),
\(1750, '1340', '2023-11-18', '0.00', NULL, NULL\),
\(1751, '1346', '2023-11-18', '0.00', NULL, NULL\),
\(1752, '1347', '2023-11-18', '0.00', NULL, NULL\),
\(1753, '1352', '2023-11-18', '0.00', NULL, NULL\),
\(1754, '1388', '2023-11-18', '0.00', NULL, NULL\),
\(1755, '1395', '2023-11-18', '0.00', NULL, NULL\),
\(1756, '1397', '2023-11-18', '0.00', NULL, NULL\),
\(1757, '1404', '2023-11-18', '0.00', '1', NULL\),
\(1758, '1406', '2023-11-18', '0.00', NULL, NULL\),
\(1759, '1407', '2023-11-18', '0.00', NULL, NULL\),
\(1760, '1412', '2023-11-18', '0.00', NULL, NULL\),
\(1761, '1413', '2023-11-18', '0.00', NULL, NULL\),
\(1762, '1414', '2023-11-18', '0.00', NULL, NULL\),
\(1763, '1415', '2023-11-18', '8.00', NULL, NULL\),
\(1764, '1417', '2023-11-18', '0.00', NULL, NULL\),
\(1765, '1418', '2023-11-18', '0.00', '4', NULL\),
\(1766, '1420', '2023-11-18', '7.90', '1', NULL\),
\(1767, '1421', '2023-11-18', '0.00', '4', NULL\),
\(1768, '1422', '2023-11-18', '0.00', '4', NULL\),
\(1769, '1423', '2023-11-18', '0.00', '8', NULL\),
\(1770, '1424', '2023-11-18', '0.00', NULL, NULL\),
\(1771, '1425', '2023-11-18', '0.00', NULL, NULL\),
\(1772, '1426', '2023-11-18', '12.00', NULL, NULL\),
\(1773, '1428', '2023-11-18', '0.00', NULL, NULL\),
\(1774, '1430', '2023-11-18', '0.00', NULL, NULL\),
\(1775, '1431', '2023-11-18', '0.00', NULL, NULL\),
\(1776, '1432', '2023-11-18', '0.00', NULL, NULL\),
\(1777, '1441', '2023-11-18', '0.00', NULL, NULL\),
\(1778, '1442', '2023-11-18', '0.00', NULL, NULL\),
\(1779, '1446', '2023-11-18', '0.00', NULL, NULL\),
\(1780, '1456', '2023-11-18', '0.00', NULL, NULL\),
\(1781, '1460', '2023-11-18', '10.00', NULL, NULL\),
\(1782, '1461', '2023-11-18', '0.00', NULL, NULL\),
\(1783, '1466', '2023-11-18', '0.00', NULL, NULL\),
\(1784, '1469', '2023-11-18', '0.00', NULL, NULL\),
\(1785, '1471', '2023-11-18', '0.00', NULL, NULL\),
\(1786, '1474', '2023-11-18', '0.00', NULL, NULL\),
\(1787, '1478', '2023-11-18', '0.00', NULL, NULL\),
\(1788, '1481', '2023-11-18', '12.00', NULL, NULL\),
\(1789, '1482', '2023-11-18', '0.00', NULL, NULL\),
\(1790, '1484', '2023-11-18', '0.00', NULL, NULL\),
\(1791, '1485', '2023-11-18', '0.00', NULL, NULL\),
\(1792, '1487', '2023-11-18', '0.00', NULL, NULL\),
\(1793, '1488', '2023-11-18', '0.00', NULL, NULL\),
\(1794, '1490', '2023-11-18', '0.00', NULL, NULL\),
\(1795, '1491', '2023-11-18', '0.00', NULL, NULL\),
\(1796, '1492', '2023-11-18', '0.00', NULL, NULL\),
\(1797, '1495', '2023-11-18', '0.00', NULL, NULL\),
\(1798, '1496', '2023-11-18', '0.00', NULL, NULL\),
\(1799, '1497', '2023-11-18', '0.00', NULL, NULL\),
\(1800, '1498', '2023-11-18', '0.00', NULL, NULL\),
\(1801, '107', '2023-11-19', '0.00', NULL, NULL\),
\(1802, '238', '2023-11-19', '0.00', NULL, NULL\),
\(1803, '240', '2023-11-19', '0.00', NULL, NULL\),
\(1804, '298', '2023-11-19', '0.00', NULL, NULL\),
\(1805, '493', '2023-11-19', '0.00', NULL, NULL\),
\(1806, '532', '2023-11-19', '0.00', NULL, NULL\),
\(1807, '574', '2023-11-19', '0.00', NULL, NULL\),
\(1808, '591', '2023-11-19', '0.00', NULL, NULL\),
\(1809, '666', '2023-11-19', '0.00', NULL, NULL\),
\(1810, '835', '2023-11-19', '0.00', NULL, NULL\),
\(1811, '852', '2023-11-19', '0.00', NULL, NULL\),
\(1812, '853', '2023-11-19', '0.00', NULL, NULL\),
\(1813, '857', '2023-11-19', '0.00', NULL, NULL\),
\(1814, '932', '2023-11-19', '0.00', NULL, NULL\),
\(1815, '962', '2023-11-19', '0.00', NULL, NULL\),
\(1816, '970', '2023-11-19', '0.00', NULL, NULL\),
\(1817, '971', '2023-11-19', '0.00', NULL, NULL\),
\(1818, '974', '2023-11-19', '0.00', NULL, NULL\),
\(1819, '986', '2023-11-19', '0.00', '1', NULL\),
\(1820, '1032', '2023-11-19', '0.00', NULL, NULL\),
\(1821, '1035', '2023-11-19', '0.00', NULL, NULL\),
\(1822, '1038', '2023-11-19', '0.00', NULL, NULL\),
\(1823, '1046', '2023-11-19', '0.00', NULL, NULL\),
\(1824, '1047', '2023-11-19', '0.00', NULL, NULL\),
\(1825, '1050', '2023-11-19', '0.00', NULL, NULL\),
\(1826, '1056', '2023-11-19', '0.00', NULL, NULL\),
\(1827, '1072', '2023-11-19', '0.00', NULL, NULL\),
\(1828, '1081', '2023-11-19', '0.00', NULL, NULL\),
\(1829, '1083', '2023-11-19', '0.00', NULL, NULL\),
\(1830, '1118', '2023-11-19', '0.00', NULL, NULL\),
\(1831, '1122', '2023-11-19', '0.00', NULL, NULL\),
\(1832, '1129', '2023-11-19', '0.00', NULL, NULL\),
\(1833, '1137', '2023-11-19', '12.00', NULL, NULL\),
\(1834, '1153', '2023-11-19', '0.00', NULL, NULL\),
\(1835, '1163', '2023-11-19', '0.00', NULL, NULL\),
\(1836, '1189', '2023-11-19', '0.00', NULL, NULL\),
\(1837, '1202', '2023-11-19', '0.00', NULL, NULL\),
\(1838, '1216', '2023-11-19', '0.00', NULL, NULL\),
\(1839, '1228', '2023-11-19', '0.00', NULL, NULL\),
\(1840, '1236', '2023-11-19', '0.00', NULL, NULL\),
\(1841, '1241', '2023-11-19', '0.00', NULL, NULL\),
\(1842, '1244', '2023-11-19', '0.00', NULL, NULL\),
\(1843, '1245', '2023-11-19', '0.00', NULL, NULL\),
\(1844, '1291', '2023-11-19', '0.00', NULL, NULL\),
\(1845, '1310', '2023-11-19', '0.00', NULL, NULL\),
\(1846, '1315', '2023-11-19', '0.00', NULL, NULL\),
\(1847, '1317', '2023-11-19', '0.00', NULL, NULL\),
\(1848, '1320', '2023-11-19', '0.00', NULL, NULL\),
\(1849, '1325', '2023-11-19', '0.00', NULL, NULL\),
\(1850, '1340', '2023-11-19', '0.00', NULL, NULL\),
\(1851, '1346', '2023-11-19', '0.00', NULL, NULL\),
\(1852, '1347', '2023-11-19', '0.00', NULL, NULL\),
\(1853, '1352', '2023-11-19', '0.00', NULL, NULL\),
\(1854, '1388', '2023-11-19', '0.00', NULL, NULL\),
\(1855, '1395', '2023-11-19', '0.00', NULL, NULL\),
\(1856, '1397', '2023-11-19', '0.00', NULL, NULL\),
\(1857, '1404', '2023-11-19', '0.00', '1', NULL\),
\(1858, '1406', '2023-11-19', '0.00', NULL, NULL\),
\(1859, '1407', '2023-11-19', '0.00', NULL, NULL\),
\(1860, '1412', '2023-11-19', '0.00', NULL, NULL\),
\(1861, '1413', '2023-11-19', '0.00', NULL, NULL\),
\(1862, '1414', '2023-11-19', '0.00', NULL, NULL\),
\(1863, '1415', '2023-11-19', '0.00', NULL, NULL\),
\(1864, '1417', '2023-11-19', '0.00', NULL, NULL\),
\(1865, '1418', '2023-11-19', '0.00', '4', NULL\),
\(1866, '1420', '2023-11-19', '0.00', '1', NULL\),
\(1867, '1421', '2023-11-19', '12.00', '4', NULL\),
\(1868, '1422', '2023-11-19', '0.00', '4', NULL\),
\(1869, '1423', '2023-11-19', '0.00', '8', NULL\),
\(1870, '1424', '2023-11-19', '0.00', NULL, NULL\),
\(1871, '1425', '2023-11-19', '0.00', NULL, NULL\),
\(1872, '1426', '2023-11-19', '0.00', NULL, NULL\),
\(1873, '1428', '2023-11-19', '0.00', NULL, NULL\),
\(1874, '1430', '2023-11-19', '0.00', NULL, NULL\),
\(1875, '1431', '2023-11-19', '0.00', NULL, NULL\),
\(1876, '1432', '2023-11-19', '0.00', NULL, NULL\),
\(1877, '1441', '2023-11-19', '0.00', NULL, NULL\),
\(1878, '1442', '2023-11-19', '0.00', NULL, NULL\),
\(1879, '1446', '2023-11-19', '0.00', NULL, NULL\),
\(1880, '1456', '2023-11-19', '0.00', NULL, NULL\),
\(1881, '1460', '2023-11-19', '0.00', NULL, NULL\),
\(1882, '1461', '2023-11-19', '0.00', NULL, NULL\),
\(1883, '1466', '2023-11-19', '0.00', NULL, NULL\),
\(1884, '1469', '2023-11-19', '0.00', NULL, NULL\),
\(1885, '1471', '2023-11-19', '0.00', NULL, NULL\),
\(1886, '1474', '2023-11-19', '0.00', NULL, NULL\),
\(1887, '1478', '2023-11-19', '0.00', NULL, NULL\),
\(1888, '1481', '2023-11-19', '0.00', NULL, NULL\),
\(1889, '1482', '2023-11-19', '0.00', NULL, NULL\),
\(1890, '1484', '2023-11-19', '0.00', NULL, NULL\),
\(1891, '1485', '2023-11-19', '7.50', NULL, NULL\),
\(1892, '1487', '2023-11-19', '12.00', NULL, NULL\),
\(1893, '1488', '2023-11-19', '0.00', NULL, NULL\),
\(1894, '1490', '2023-11-19', '0.00', NULL, NULL\),
\(1895, '1491', '2023-11-19', '0.00', NULL, NULL\),
\(1896, '1492', '2023-11-19', '0.00', NULL, NULL\),
\(1897, '1495', '2023-11-19', '0.00', NULL, NULL\),
\(1898, '1496', '2023-11-19', '0.00', NULL, NULL\),
\(1899, '1497', '2023-11-19', '0.00', NULL, NULL\),
\(1900, '1498', '2023-11-19', '0.00', NULL, NULL\),
\(1901, '107', '2023-11-20', '5.00', NULL, NULL\),
\(1902, '238', '2023-11-20', '0.00', NULL, NULL\),
\(1903, '240', '2023-11-20', '0.00', NULL, NULL\),
\(1904, '298', '2023-11-20', '0.00', NULL, NULL\),
\(1905, '493', '2023-11-20', '0.00', NULL, NULL\),
\(1906, '532', '2023-11-20', '0.00', NULL, NULL\),
\(1907, '574', '2023-11-20', '0.00', NULL, NULL\),
\(1908, '591', '2023-11-20', '0.00', NULL, NULL\),
\(1909, '666', '2023-11-20', '0.00', NULL, NULL\),
\(1910, '835', '2023-11-20', '0.00', NULL, NULL\),
\(1911, '852', '2023-11-20', '0.00', NULL, NULL\),
\(1912, '853', '2023-11-20', '0.00', NULL, NULL\),
\(1913, '857', '2023-11-20', '0.00', NULL, NULL\),
\(1914, '932', '2023-11-20', '0.00', NULL, NULL\),
\(1915, '962', '2023-11-20', '0.00', NULL, NULL\),
\(1916, '970', '2023-11-20', '0.00', NULL, NULL\),
\(1917, '971', '2023-11-20', '0.00', NULL, NULL\),
\(1918, '974', '2023-11-20', '8.00', '8', NULL\),
\(1919, '986', '2023-11-20', '0.00', '1', NULL\),
\(1920, '1032', '2023-11-20', '0.00', NULL, NULL\),
\(1921, '1035', '2023-11-20', '0.00', NULL, NULL\),
\(1922, '1038', '2023-11-20', '0.00', NULL, NULL\),
\(1923, '1046', '2023-11-20', '5.00', '3', NULL\),
\(1924, '1047', '2023-11-20', '5.00', NULL, NULL\),
\(1925, '1050', '2023-11-20', '0.00', NULL, NULL\),
\(1926, '1056', '2023-11-20', '0.00', NULL, NULL\),
\(1927, '1072', '2023-11-20', '0.00', NULL, NULL\),
\(1928, '1081', '2023-11-20', '0.00', NULL, NULL\),
\(1929, '1083', '2023-11-20', '0.00', NULL, NULL\),
\(1930, '1118', '2023-11-20', '0.00', NULL, NULL\),
\(1931, '1122', '2023-11-20', '0.00', NULL, NULL\),
\(1932, '1129', '2023-11-20', '0.00', NULL, NULL\),
\(1933, '1137', '2023-11-20', '0.00', NULL, NULL\),
\(1934, '1153', '2023-11-20', '0.00', NULL, NULL\),
\(1935, '1163', '2023-11-20', '0.00', NULL, NULL\),
\(1936, '1189', '2023-11-20', '0.00', NULL, NULL\),
\(1937, '1202', '2023-11-20', '0.00', NULL, NULL\),
\(1938, '1216', '2023-11-20', '0.00', NULL, NULL\),
\(1939, '1228', '2023-11-20', '0.00', NULL, NULL\),
\(1940, '1236', '2023-11-20', '0.00', NULL, NULL\),
\(1941, '1241', '2023-11-20', '0.00', NULL, NULL\),
\(1942, '1244', '2023-11-20', '0.00', NULL, NULL\),
\(1943, '1245', '2023-11-20', '8.00', NULL, NULL\),
\(1944, '1291', '2023-11-20', '0.00', NULL, NULL\),
\(1945, '1310', '2023-11-20', '0.00', NULL, NULL\),
\(1946, '1315', '2023-11-20', '0.00', NULL, NULL\),
\(1947, '1317', '2023-11-20', '0.00', NULL, NULL\),
\(1948, '1320', '2023-11-20', '0.00', NULL, NULL\),
\(1949, '1325', '2023-11-20', '0.00', NULL, NULL\),
\(1950, '1340', '2023-11-20', '8.00', NULL, NULL\),
\(1951, '1346', '2023-11-20', '0.00', NULL, NULL\),
\(1952, '1347', '2023-11-20', '0.00', NULL, NULL\),
\(1953, '1352', '2023-11-20', '0.00', NULL, NULL\),
\(1954, '1388', '2023-11-20', '0.00', NULL, NULL\),
\(1955, '1395', '2023-11-20', '0.00', NULL, NULL\),
\(1956, '1397', '2023-11-20', '0.00', NULL, NULL\),
\(1957, '1404', '2023-11-20', '0.00', '1', NULL\),
\(1958, '1406', '2023-11-20', '0.00', NULL, NULL\),
\(1959, '1407', '2023-11-20', '0.00', NULL, NULL\),
\(1960, '1412', '2023-11-20', '0.00', NULL, NULL\),
\(1961, '1413', '2023-11-20', '0.00', NULL, NULL\),
\(1962, '1414', '2023-11-20', '0.00', NULL, NULL\),
\(1963, '1415', '2023-11-20', '0.00', NULL, NULL\),
\(1964, '1417', '2023-11-20', '0.00', NULL, NULL\),
\(1965, '1418', '2023-11-20', '0.00', '4', NULL\),
\(1966, '1420', '2023-11-20', '0.00', '1', NULL\),
\(1967, '1421', '2023-11-20', '0.00', '4', NULL\),
\(1968, '1422', '2023-11-20', '0.00', '4', NULL\),
\(1969, '1423', '2023-11-20', '8.00', '8', NULL\),
\(1970, '1424', '2023-11-20', '0.00', NULL, NULL\),
\(1971, '1425', '2023-11-20', '0.00', NULL, NULL\),
\(1972, '1426', '2023-11-20', '8.00', NULL, NULL\),
\(1973, '1428', '2023-11-20', '0.00', NULL, NULL\),
\(1974, '1430', '2023-11-20', '0.00', NULL, NULL\),
\(1975, '1431', '2023-11-20', '0.00', NULL, NULL\),
\(1976, '1432', '2023-11-20', '0.00', NULL, NULL\),
\(1977, '1441', '2023-11-20', '0.00', NULL, NULL\),
\(1978, '1442', '2023-11-20', '0.00', NULL, NULL\),
\(1979, '1446', '2023-11-20', '0.00', NULL, NULL\),
\(1980, '1456', '2023-11-20', '0.00', NULL, NULL\),
\(1981, '1460', '2023-11-20', '0.00', NULL, NULL\),
\(1982, '1461', '2023-11-20', '0.00', NULL, NULL\),
\(1983, '1466', '2023-11-20', '0.00', NULL, NULL\),
\(1984, '1469', '2023-11-20', '0.00', NULL, NULL\),
\(1985, '1471', '2023-11-20', '0.00', NULL, NULL\),
\(1986, '1474', '2023-11-20', '0.00', NULL, NULL\),
\(1987, '1478', '2023-11-20', '0.00', NULL, NULL\),
\(1988, '1481', '2023-11-20', '0.00', NULL, NULL\),
\(1989, '1482', '2023-11-20', '0.00', NULL, NULL\),
\(1990, '1484', '2023-11-20', '0.00', NULL, NULL\),
\(1991, '1485', '2023-11-20', '0.00', NULL, NULL\),
\(1992, '1487', '2023-11-20', '0.00', NULL, NULL\),
\(1993, '1488', '2023-11-20', '0.00', NULL, NULL\),
\(1994, '1490', '2023-11-20', '0.00', NULL, NULL\),
\(1995, '1491', '2023-11-20', '0.00', NULL, NULL\),
\(1996, '1492', '2023-11-20', '0.00', NULL, NULL\),
\(1997, '1495', '2023-11-20', '0.00', NULL, NULL\),
\(1998, '1496', '2023-11-20', '0.00', NULL, NULL\),
\(1999, '1497', '2023-11-20', '0.00', NULL, NULL\),
\(2000, '1498', '2023-11-20', '0.00', NULL, NULL\);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla \`centro\`
--
ALTER TABLE \`centro\`
  ADD PRIMARY KEY \(\`id\_centro\`\);

--
-- Indices de la tabla \`informacion\_asociados\`
--
ALTER TABLE \`informacion\_asociados\`
  ADD PRIMARY KEY \(\`id\_asociado\`\);

--
-- Indices de la tabla \`proceso\`
--
ALTER TABLE \`proceso\`
  ADD PRIMARY KEY \(\`id\_proceso\`\),
  ADD KEY \`id\_centro\` \(\`id\_centro\`\);

--
-- Indices de la tabla \`registro\_horas\_trabajo\`
--
ALTER TABLE \`registro\_horas\_trabajo\`
  ADD PRIMARY KEY \(\`id\_registro\`\);

--
-- AUTO\_INCREMENT de las tablas volcadas
--

--
-- AUTO\_INCREMENT de la tabla \`centro\`
--
ALTER TABLE \`centro\`
  MODIFY \`id\_centro\` int\(11\) NOT NULL AUTO\_INCREMENT, AUTO\_INCREMENT=9;

--
-- AUTO\_INCREMENT de la tabla \`informacion\_asociados\`
--
ALTER TABLE \`informacion\_asociados\`
  MODIFY \`id\_asociado\` int\(11\) NOT NULL AUTO\_INCREMENT, AUTO\_INCREMENT=101;

--
-- AUTO\_INCREMENT de la tabla \`proceso\`
--
ALTER TABLE \`proceso\`
  MODIFY \`id\_proceso\` int\(11\) NOT NULL AUTO\_INCREMENT, AUTO\_INCREMENT=14;

--
-- AUTO\_INCREMENT de la tabla \`registro\_horas\_trabajo\`
--
ALTER TABLE \`registro\_horas\_trabajo\`
  MODIFY \`id\_registro\` int\(11\) NOT NULL AUTO\_INCREMENT, AUTO\_INCREMENT=2001;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla \`proceso\`
--
ALTER TABLE \`proceso\`
  ADD CONSTRAINT \`proceso\_ibfk\_1\` FOREIGN KEY \(\`id\_centro\`\) REFERENCES \`centro\` \(\`id\_centro\`\);
COMMIT;

/\*\!40101 SET CHARACTER\_SET\_CLIENT=@OLD\_CHARACTER\_SET\_CLIENT \*/;
/\*\!40101 SET CHARACTER\_SET\_RESULTS=@OLD\_CHARACTER\_SET\_RESULTS \*/;
/\*\!40101 SET COLLATION\_CONNECTION=@OLD\_COLLATION\_CONNECTION \*/;

```

### C:\AppServ\www\Horas\importar_datos_horas_trabajo.py
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

### C:\AppServ\www\Horas\index.php
```plaintext
<?php 
include 'templates/header.php'; 
require\_once 'includes/db.php';


// Preparar la consulta SQL
$sql = "SELECT \* FROM informacion\_asociados ";

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
                <td> <a href='mostrar\_horas.php?legajo=".$fila\["legajo"\]."'>  ".$fila\["legajo"\]."  </a>   </td>
                <td>".$fila\["nombre"\]."</td> 
                <td>".$fila\["apellido"\]."</td> 
            </tr>";
    }
    echo "</table>";
} else {
    echo "No se encontraron resultados.";
}
echo "</body></html>";

// Cerrar la conexión
$conexion->close\(\);

include 'templates/footer.php'; ?>

```

### C:\AppServ\www\Horas\insertar_centro.php
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

### C:\AppServ\www\Horas\legajo.php
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

### C:\AppServ\www\Horas\mostrar_horas.php
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

### C:\AppServ\www\Horas\novus_usb_v0.py
```plaintext
#novus\_usb\_v0.py
import pymysql
import minimalmodbus
import serial.tools.list\_ports
import time
import os
from datetime import datetime

seg = 1
def detect\_serial\_ports\(device\_description\):
    available\_ports = list\(serial.tools.list\_ports.comports\(\)\)
    for port, desc, hwid in available\_ports:
        if device\_description in desc:
            return port
    return None

device\_description = "DigiRail Connect"  
com\_port = detect\_serial\_ports\(device\_description\)

if com\_port:
    print\(f"Puerto {device\_description} detectado: {com\_port}\n"\)
else:
    device\_description = "USB-SERIAL CH340"  
    com\_port = detect\_serial\_ports\(device\_description\)
    if com\_port:
        print\(f"Puerto detectado: {com\_port}\n"\)
    else:
        print\("No se detectaron puertos COM para tu dispositivo."\)
        input\("Presiona una tecla para salir"\)
        exit\(\)


# Dirección del dispositivo Modbus \(ajusta la dirección del dispositivo según tu configuración\)
device\_address = 1

# Entradas digitales
D1 = 70
D2 = 71
# Contador
HR\_COUNTER1\_LO = 22
HR\_COUNTER1\_HI = 23
HR\_COUNTER2\_LO = 24
HR\_COUNTER2\_HI = 25

# Configuración de la base de datos MySQL
db\_config = {
    'host': 'localhost',
    'user': 'root',
    'password': '12345678',
    'db': 'novus'  # Base de datos y subíndice
}

# Función para verificar la conexión a la base de datos
def check\_db\_connection\(\):
    try:
        connection = pymysql.connect\(\*\*db\_config\)
        return connection
    except Exception as e:
        print\(f"Error de conexión a la base de datos: {e}"\)
        return None

# Función para leer una entrada digital
def read\_digital\_input\(instrument, address\):
    if instrument:
        try:
            result = instrument.read\_bit\(address, functioncode=2\)
            return result
        except Exception as e:
            print\(f"Error al leer entrada digital en registro {address}: {e}"\)
    return None

# Función para leer registros de alta resolución
def read\_high\_resolution\_register\(instrument, address\_lo, address\_hi\):
    if instrument:
        try:
            value\_lo = instrument.read\_register\(address\_lo, functioncode=3\)
            value\_hi = instrument.read\_register\(address\_hi, functioncode=3\)
            return value\_lo, value\_hi
        except Exception as e:
            print\(f"Error al leer registro de alta resolución en registros {address\_lo} y {address\_hi}: {e}"\)
    return None, None

# Función para actualizar registros en la base de datos
def update\_database\(connection, address, value, descripcion\):
    if connection:
        try:
            with connection.cursor\(\) as cursor:
                sql = f"UPDATE registros\_modbus SET valor = {value} WHERE direccion\_modbus = {address}"
                cursor.execute\(sql\)
                connection.commit\(\)
                print\(f"Registro actualizado: dirección {address}, {descripcion} valor {value}"\)
        except Exception as e:
            print\(f"Error al actualizar el registro en la base de datos: {e}"\)


def insert\_database\(connection, fecha\_ahora, HR\_COUNTER1\):
    if connection:
        try:
            with connection.cursor\(\) as cursor:
                sql = f"INSERT INTO \`maq\_bolsas\`\( \`unixtime\`, \`HR\_COUNTER1\`\) VALUES \({fecha\_ahora}, {HR\_COUNTER1}\)"
                cursor.execute\(sql\)
                connection.commit\(\)
                print\(f"Registro Insertado: unixtime = {fecha\_ahora}, HR\_COUNTER1= {HR\_COUNTER1} , timestamp = {datetime.fromtimestamp\(fecha\_ahora\)}"\)
        except Exception as e:
            print\(f"Error al insertar el registro en la base de datos: {e}"\)

while True:
    os.system\('cls' if os.name == 'nt' else 'clear'\)

    # Realiza tus operaciones de lectura y actualización aquí.
    connection = check\_db\_connection\(\)
    try:
        instrument = minimalmodbus.Instrument\(com\_port, device\_address\)
    except Exception as e:
        print\("Error al configurar el puerto serie:", str\(e\)\)
        time.sleep\(10\)
        continue

    if connection:
        D1\_state = read\_digital\_input\(instrument, D1\)
        HR\_COUNTER1\_lo, HR\_COUNTER1\_hi = read\_high\_resolution\_register\(instrument, HR\_COUNTER1\_LO, HR\_COUNTER1\_HI\)
        if HR\_COUNTER1\_lo is not None and HR\_COUNTER1\_hi is not None:
            HR\_COUNTER1 = HR\_COUNTER1\_lo + HR\_COUNTER1\_hi \* 65536
        print\(f"Puerto {device\_description} detectado: {com\_port}\n"\)


        if D1\_state is not None:
            update\_database\(connection, D1, D1\_state, descripcion="HR\_INPUT1\_STATE"\)

        if HR\_COUNTER1\_lo is not None and HR\_COUNTER1\_hi is not None:
            update\_database\(connection, HR\_COUNTER1\_LO, HR\_COUNTER1\_lo, descripcion="HR\_COUNTER1\_LO "\)
            update\_database\(connection, HR\_COUNTER1\_HI, HR\_COUNTER1\_hi, descripcion="HR\_COUNTER1\_HI "\)
            
        fecha\_ahora = int\(time.time\(\)\)
        print\(f"la hora es: {datetime.fromtimestamp\(fecha\_ahora\)}"\)
        fecha\_sig = \(\(int\(time.time\(\)\) // 300 + 1\) \* 300\)
        fecha\_sig\_formateada = datetime.fromtimestamp\(fecha\_sig\)
        print\(f"Próxima actualización a las {fecha\_sig\_formateada}"\)
        seg = fecha\_sig - fecha\_ahora
        seg\_truncado = round\(seg, 1\)
        print\(f"Tiempo para la siguiente actualización: {seg\_truncado} segundos"\)
        fecha\_ahora = round\(fecha\_ahora/300,1\)\*300


    if seg < 2:
        try:
            insert\_database\(connection, fecha\_ahora, HR\_COUNTER1\)
            time.sleep\(15\)
        except Exception as e:  
            print\("Error al insertar en la base de datos:", e\)

    time.sleep\(1\)

```

### C:\AppServ\www\Horas\procesar.php
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

### C:\AppServ\www\Horas\README.md
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

### C:\AppServ\www\Horas\AMIS\01-ProjectAnalysis.md
```plaintext
## Análisis del Proyecto de Software "Horas"

### Contexto del Proyecto
El proyecto "Horas" parece ser una aplicación web diseñada para el seguimiento y gestión de horas de trabajo, asociadas a diferentes centros de costos y procesos dentro de una organización. Utiliza tecnologías como PHP, MySQL, HTML, CSS, JavaScript, y Python.

### Estructura de Carpetas y Archivos
El proyecto consta de varias carpetas y archivos principales:
- \*\*PHP Scripts:\*\* \`actualizar\_centro.php\`, \`centro\_costo.php\`, \`database.php\`, \`index.php\`, \`insertar\_centro.php\`, \`legajo.php\`, \`mostrar\_horas.php\`, \`procesar.php\`.
- \*\*Python Scripts:\*\* \`importar\_datos\_horas\_trabajo.py\`, \`novus\_usb\_v0.py\`.
- \*\*SQL:\*\* \`horas.sql\`.
- \*\*CSS:\*\* \`header.css\`.
- \*\*Configuración y Plantillas:\*\* Dentro de las carpetas \`CSV\`, \`includes\`, \`templates\`.

### Análisis y Recomendaciones

#### Desarrollo de Software
1. \*\*Estructura de Código y Prácticas de Codificación:\*\*
   - Evitar duplicación de código. Por ejemplo, la función \`obtenerNombreCentroCosto\(\)\` se repite en varios archivos PHP. Considerar crear un archivo de funciones comunes.
   - Refactorizar la conexión a la base de datos en un solo archivo para evitar repetición.

2. \*\*Seguridad y Ciberseguridad:\*\*
   - Evitar exponer detalles de la base de datos \(\`database.php\`\). Usar variables de entorno para almacenar información sensible.
   - Prevenir inyecciones SQL utilizando consultas preparadas y validando la entrada del usuario.

3. \*\*Pruebas Automáticas y Análisis de Rendimiento:\*\*
   - Implementar pruebas unitarias y de integración para validar la funcionalidad de los scripts PHP y Python.
   - Utilizar herramientas de análisis de código para identificar posibles mejoras de rendimiento y seguridad.

#### Diseño UX/UI
1. \*\*Accesibilidad y Estética:\*\*
   - Mejorar la coherencia en el diseño de las interfaces de usuario, utilizando un framework de CSS como Bootstrap para un diseño responsivo.
   - Asegurar la accesibilidad web, verificando el contraste de colores y la navegabilidad con teclado.

2. \*\*Funcionalidad y Experiencia del Usuario:\*\*
   - Implementar una navegación más intuitiva y un diseño amigable para el usuario final.
   - Incluir mensajes de confirmación o error claros para las acciones del usuario.

#### Tecnologías Utilizadas
- \*\*Python:\*\* Utilizar un linter como Pylint para mejorar la calidad del código.
- \*\*PHP:\*\* Actualizar a la última versión estable para mejorar la seguridad y el rendimiento.
- \*\*JavaScript y CSS:\*\* Minimizar y optimizar los archivos para mejorar los tiempos de carga.

#### Automatización y Machine Learning
- \*\*Pruebas Automáticas:\*\* Implementar un framework de pruebas como PHPUnit para PHP.
- \*\*Machine Learning:\*\* Considerar la implementación de algoritmos de ML para la predicción y análisis de datos de horas de trabajo.

### Documentación y Conocimiento Compartido

- \*\*Mantenimiento de Documentación Actualizada:\*\* Es esencial mantener una documentación completa y actualizada de todo el sistema. Esto incluye no solo el código, sino también la arquitectura del sistema, el diseño de la base de datos, y los procesos de negocio que soporta.
- \*\*Comentarios en el Código:\*\* Asegurarse de que todos los scripts y módulos estén adecuadamente comentados para facilitar la comprensión y el mantenimiento por parte de otros desarrolladores.
- \*\*Guías de Usuario y Administrador:\*\* Desarrollar guías detalladas para los usuarios finales y administradores del sistema, explicando cómo realizar tareas comunes, configuraciones y solución de problemas.
- \*\*Registro de Cambios:\*\* Llevar un registro detallado de cambios y actualizaciones en un archivo \`CHANGELOG.md\` para rastrear la evolución del proyecto y facilitar la comprensión de las modificaciones realizadas.

### Conclusión y Siguientes Pasos

El proyecto "Horas" muestra una estructura básica funcional, pero requiere mejoras en áreas como seguridad, eficiencia en el código, diseño UX/UI, y documentación. Las recomendaciones proporcionadas buscan fortalecer estos aspectos para lograr un sistema más robusto, seguro y amigable para el usuario.

\*\*Primer Paso:\*\*
- Realizar una auditoría de seguridad para identificar y corregir posibles vulnerabilidades, especialmente en lo que respecta a la gestión de la base de datos y la validación de entradas del usuario.

\*\*Pasos a seguir:\*\*
- Refactorizar el código para mejorar la mantenibilidad y eficiencia.
- Implementar pruebas automáticas para garantizar la calidad y fiabilidad del software.
- Mejorar la experiencia del usuario con un enfoque en la accesibilidad y la estética.
- Actualizar y mantener la documentación del proyecto.

Este análisis es un punto de partida. La mejora continua del proyecto requerirá revisión y ajustes regulares basados en retroalimentación y cambios en los requisitos del sistema.
```

### C:\AppServ\www\Horas\AMIS\02-ToDoList.md
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

### C:\AppServ\www\Horas\AMIS\03-MySQL.md
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

### C:\AppServ\www\Horas\CSS\header.css
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

### C:\AppServ\www\Horas\CSV\config.php
```plaintext
<?php
define\('DB\_SERVER', 'localhost'\);
define\('DB\_USERNAME', 'root'\);
define\('DB\_PASSWORD', '12346578'\);
define\('DB\_NAME', 'horas'\);
?>

```

### C:\AppServ\www\Horas\includes\config.php
```plaintext
<?php
$servername = "localhost";
$username = "root";
$password = "12345678";
$dbname = "horas";
?>

```

### C:\AppServ\www\Horas\includes\db.php
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

### C:\AppServ\www\Horas\templates\footer.php
```plaintext
<footer>
    <\!-- Contenido del pie de página -->
</footer>
</body>
</html>

```

### C:\AppServ\www\Horas\templates\header.php
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