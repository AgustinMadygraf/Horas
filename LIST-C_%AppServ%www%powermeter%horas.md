Objetivo: Analizar un proyecto para identificar áreas específicas para aplicar mejores prácticas de programación.

Pasos:

1 - Realizar un análisis del proyecto
2 - Identificar un área clave para mejora prioritaria
3 - Proveer una sugerencia específica para esa mejora, detallando cómo podría implementarse.
4 - Desarrollar un plan de acción detallado y paso a paso para implementar la mejora, incluyendo herramientas y métodos recomendados.
5 - Indicar qué archivo o componente específico necesita ser mejorado como punto de partida para la implementación de la mejora. Considera el impacto en el código existente y la estructura del proyecto.
6 - Una vez implementadas las mejoras, evaluar su impacto y considerar ajustes adicionales.

Consideraciones:

1 - Para la mejora de la programación, se deben tener en cuenta aspectos como la estructura de archivos y carpetas, el mensaje de logging, el código duplicado, la ciberseguridad, el nombre de las funciones y variables, y la experiencia de usuario e interfaz de usuario.
2 - Para la mejora del diseño UI/UX, se deben tener en cuenta aspectos como la accesibilidad, la estética, la funcionalidad, etc.
---

# Estructura de Carpetas y Archivos
```bash
horas/
    actualizar_centro.php
    centro_costo.php
    centro_costo.sql
    horas.sql
    importar_datos_horas_trabajo.py
    index.php
    informacion_asociados.sql
    insertar_centro.php
    legajo.php
    mostrar_horas.php
    procesar.php
    RegistroHorasTrabajo.sql
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
---


Contenido de archivos seleccionados:

--- Contenido de C:\AppServ\www\powermeter\horas\actualizar_centro.php ---
<?php
#mostrar_horas.php
include 'templates/header.php'; 
require_once 'includes/db.php';
require_once 'legajo.php';






// Preparar la consulta SQL
$sql = "SELECT * FROM registro_horas_trabajo WHERE legajo = ? AND horas_trabajadas > 1 AND centro_costo IS NOT NULL  ORDER BY fecha ASC";

// Preparar la sentencia
$stmt = $conexion->prepare($sql);

// Vincular parámetros
$stmt->bind_param("s", $legajo);

// Ejecutar la sentencia
$stmt->execute();

// Obtener los resultados
$resultado = $stmt->get_result();

// Cerrar la sentencia
$stmt->close();

// Comenzar el HTML
echo "<!DOCTYPE html><html><head><title>Registro de Horas</title></head><body>";
function obtenerNombreCentroCosto($codigo) {
    $nombresCentroCosto = [
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
    ];

    return isset($nombresCentroCosto[$codigo]) ? $nombresCentroCosto[$codigo] : 'Desconocido';
}


// Verificar si hay resultados y mostrarlos
if ($resultado->num_rows > 0) {
    echo "<table border='1'>
            <tr>
                <th>Legajo</th>
                <th>Fecha</th>
                <th>Horas</th>
                <th>Centro de costo anterior</th>
                <th>Centro de costo actual</th>
                <th></th>

            </tr>";
    while($fila = $resultado->fetch_assoc()) {
        echo "<tr>
                <td>".$fila["legajo"]."</td>
                <td>".$fila["fecha"]."</td>  
                <td>".$fila["horas_trabajadas"]."</td><td>";  
        echo obtenerNombreCentroCosto($fila["centro_costo"]);
        echo "</td> <form action='procesar.php' method='GET'>
                <td>
                    <select name='centro_costo'>
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
                    <input type='hidden' name='legajo' value='".$fila["legajo"]."'>
                    <input type='hidden' name='fecha' value='".$fila["fecha"]."'>
                <td><input type='submit' value='Actualizar'></td>
                </form>";
        echo "</td><td>".$fila["proceso"]."</td> </tr>";
    }
    echo "</table>";
} else {
    echo "No se encontraron resultados.";
}


// Finalizar el HTML
echo "</body></html>";

// Cerrar la conexión
$conexion->close();
?>



--- Contenido de C:\AppServ\www\powermeter\horas\centro_costo.php ---
<?php
include 'templates/header.php'; 
require_once 'includes/db.php';

// Preparar la consulta SQL
$sql = "SELECT COALESCE(centro_costo, 'Sin Asignar') AS centro_costo, SUM(horas_trabajadas) AS total_horas FROM registro_horas_trabajo GROUP BY COALESCE(centro_costo, 'Sin Asignar') ORDER BY `total_horas` DESC";
$resultado = $conexion->query($sql);

function obtenerNombreCentroCosto($codigo) {
    $nombresCentroCosto = [
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

    ];
    return isset($nombresCentroCosto[$codigo]) ? $nombresCentroCosto[$codigo] : 'Desconocido';
}

$totalHoras = 0;
$datosGrafico = [["Centro de Costo", "Horas"]];

if ($resultado) {
    while ($fila = $resultado->fetch_assoc()) {
        $totalHoras += $fila["total_horas"];
        $nombreCentro = obtenerNombreCentroCosto($fila["centro_costo"]);
        array_push($datosGrafico, [$nombreCentro, (float)$fila["total_horas"]]);
    }
}

$datosJson = json_encode($datosGrafico);

// Comenzar el HTML
echo "<!DOCTYPE html><html><head>
      <title>Total Horas por Centro de Costo</title>
      <script type='text/javascript' src='https://www.gstatic.com/charts/loader.js'></script>
      <script type='text/javascript'>
          google.charts.load('current', {'packages':['corechart']});
          google.charts.setOnLoadCallback(function() {
              drawChart($datosJson);
          });
          function drawChart(dataArray) {
              var data = google.visualization.arrayToDataTable(dataArray);
              var options = {
                  title: 'Total Horas por Centro de Costo'
              };
              var chart = new google.visualization.PieChart(document.getElementById('piechart'));
              chart.draw(data, options);
          }
      </script>
      </head><body>";

// Mostrar tabla de datos
echo "<table border='1'>
      <tr>
          <th>Centro de Costo</th>
          <th>Horas</th>
          <th>Porcentaje [%]</th>
      </tr>";

$resultado->data_seek(0); // Rebobinar para usar de nuevo el resultado

if ($resultado && $resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $porcentaje = $fila["total_horas"] / $totalHoras * 100;
        echo "<tr>
                <td>". obtenerNombreCentroCosto($fila["centro_costo"]). "</td>
                <td>". $fila["total_horas"]. "</td>
                <td>". number_format($porcentaje, 2). "%</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "No se encontraron resultados.";
}

echo "<div id='piechart' style='width: 900px; height: 500px;'></div>";
echo "</body></html>";
$conexion->close();
?>


--- Contenido de C:\AppServ\www\powermeter\horas\centro_costo.sql ---
CREATE TABLE centro_costo (
    id_costo INT AUTO_INCREMENT PRIMARY KEY,
    item VARCHAR(10),
    num_descripcion VARCHAR(4),
    descripcion VARCHAR(20)
);

INSERT INTO centro_costo (item, num_descripcion, descripcion) VALUES

('centro', '1', 'Maquina de bolsas'),
('centro', '2', 'Boletas y folleteria'),
('centro', '3', 'logistica'),
('centro', '4', 'Administracion'),
('centro', '5', 'Club'),
('centro', '6', 'Mantenimiento'),
('centro', '7', 'Comedor'),
('centro', '8', 'Guardia'),
('centro', '9', 'Sistemas'),
('centro', '10', 'Enfermeria'),
('sub_centro', '1.a', 'Confección de bolsas de papel'),
('sub_centro', '1.b', 'Impresión  de bolsas de papel'),
('sub_centro', '1.c', 'Confeción y pegado manual de manijas'),
('sub_centro', '1.d', 'Ventas y Marketing de bolsas'),
('sub_centro', '2.a', 'Impresión'),
('sub_centro', '2.b', 'Encuadernación'),
('sub_centro', '2.c', 'Preimpresión'),
('sub_centro', '2.d', 'Despacho');


--- Contenido de C:\AppServ\www\powermeter\horas\horas.sql ---
-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 02-01-2024 a las 19:07:46
-- Versión del servidor: 8.0.17
-- Versión de PHP: 7.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `horas`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `centro`
--

CREATE TABLE `centro` (
  `id_centro` int(11) NOT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `descripcion` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `centro`
--

INSERT INTO `centro` (`id_centro`, `nombre`, `descripcion`) VALUES
(1, '1', 'Maquina de bolsas'),
(2, '2', 'Boletas y folleteria'),
(3, '3', 'Logistica'),
(4, '4', 'Administracion'),
(5, '5', 'Club'),
(6, '6', 'Mantenimiento'),
(7, '7', 'Comedor'),
(8, '8', 'Guardia');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `informacion_asociados`
--

CREATE TABLE `informacion_asociados` (
  `id_asociado` int(11) NOT NULL,
  `legajo` varchar(4) DEFAULT NULL,
  `nombre` varchar(20) DEFAULT NULL,
  `apellido` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `informacion_asociados`
--

INSERT INTO `informacion_asociados` (`id_asociado`, `legajo`, `nombre`, `apellido`) VALUES
(1, '107', 'Antonio', 'Lopez'),
(2, '238', 'Oreste Mariano', 'Montenegro'),
(3, '240', 'Julian', 'Ortiz'),
(4, '298', 'Jose Andres', 'Ponce'),
(5, '493', 'Eduardo', 'Ayala'),
(6, '532', 'Hugo', 'Vera'),
(7, '574', 'Daniel', 'Arriondo'),
(8, '591', 'Julio', 'Hidalgo'),
(9, '666', 'Gustavo', 'Medrano'),
(10, '835', 'Cristian German', 'Pena'),
(11, '852', 'Ramon', 'Zalazar'),
(12, '853', 'Pablo', 'Paz'),
(13, '857', 'Oscar', 'Bentancourt'),
(14, '932', 'Martin', 'Killing'),
(15, '962', 'Hector Javier', 'Ballesteros'),
(16, '970', 'Miguel Angel', 'Gomez'),
(17, '971', 'Fernando Ariel', 'Utrera'),
(18, '974', 'Facundo Matias', 'Gomez'),
(19, '986', 'Ariel Gustavo', 'Fernandez'),
(20, '1032', 'Rolando Hector', 'Falcon'),
(21, '1035', 'Luis Fernando', 'Serrano'),
(23, '1038', 'Cristian Fabian', 'Ferreyra'),
(24, '1046', 'Silverio', 'Sanchez'),
(25, '1047', 'Angel Diego Jose', 'Galeano'),
(26, '1050', 'Gerardo Gaston', 'Leguizamon'),
(27, '1056', 'Matias Osvaldo', 'Hug'),
(28, '1072', 'Damian Emilio', 'Conti'),
(29, '1081', 'Cristian Gabriel', 'Cañete'),
(30, '1083', 'Jorge Gabriel', 'Medina'),
(31, '1118', 'Sebastian Anibal', 'Arrascaeta'),
(32, '1122', 'Mauro Maximiliano', 'Zuccarotto'),
(33, '1129', 'Alcira Amalia', 'Landeira'),
(34, '1137', 'Enrique Emiliano', 'Diaz'),
(35, '1153', 'Sandro Ariel', 'Salazar'),
(36, '1163', 'Gustavo David', 'Brito'),
(37, '1189', 'Martin Andres', 'Arari'),
(38, '1202', 'Rodolfo Walter', 'Osuna'),
(39, '1216', 'Ruben Martin', 'Dirroco'),
(40, '1228', 'Adrian Israel', 'Mancilla'),
(41, '1236', 'Oscar', 'Velazco'),
(42, '1241', 'Jonathan Ezequiel', 'Ledesma'),
(43, '1244', 'Marcelo Adrian Elise', 'Almada'),
(44, '1245', 'Jonatan', 'Gue'),
(45, '1291', 'Jose Antonio', 'Zarate'),
(46, '1310', 'Abel Alejandro', 'Silva'),
(47, '1315', 'Armando Federico', 'Pignataro'),
(48, '1317', 'Emanuel Omar', 'Garzia'),
(49, '1320', 'Carlos Eduardo', 'Cardozo'),
(50, '1325', 'Rodrigo Cristian', 'Rosales Arias'),
(51, '1340', 'David Leonardo', 'Valera Vasquez'),
(52, '1346', 'Julio Dario', 'Almaraz'),
(53, '1347', 'Carlos Alberto', 'Lescano'),
(54, '1352', 'Ricardo German', 'Miño'),
(55, '1388', 'German Diego', 'Gassibe'),
(56, '1395', 'Marcelo Omar', 'Ortega'),
(57, '1397', 'Martin Ezequiel', 'Chaile'),
(58, '1404', 'Agustin Leonardo', 'Bustos'),
(59, '1406', 'Jimena', 'Caruso'),
(60, '1407', 'Vanina', 'Mancuso'),
(61, '1412', 'Maria del Carmen', 'Vallejos'),
(62, '1413', 'Juana Rosa', 'Laime'),
(63, '1414', 'Leandro', 'Quinzano'),
(64, '1415', 'Monica Patricia', 'Butiler'),
(65, '1417', 'Ingrid Geraldina', 'Alarcon'),
(66, '1418', 'Lucrecia Viviana', 'Borge'),
(67, '1420', 'Maria Celeste', 'Paz'),
(68, '1421', 'Anahi Daiana', 'Almada'),
(69, '1422', 'Erica Victoria', 'Gramajo'),
(70, '1423', 'Rocio Noemi', 'Fernandez'),
(71, '1424', 'Monica', 'Salazar'),
(72, '1425', 'Maria de los Angeles', 'Plett'),
(73, '1426', 'Maria Alejandra Leon', 'Cortellcubi'),
(74, '1428', 'Emiliana Hilda', 'Andrade'),
(75, '1430', 'Eliana Edith', 'Villanueva'),
(76, '1431', 'Norma Beatriz', 'Barrientos'),
(77, '1432', 'Maria Vanina', 'Reboredo'),
(78, '1441', 'Gabriela del Carmen', 'Vera'),
(79, '1442', 'Juan Domingo', 'Peralta'),
(80, '1446', 'Nicolas', 'Almarante'),
(81, '1456', 'Mariana Soledad', 'Hogas'),
(82, '1460', 'Roberto Cesar', 'Amador'),
(83, '1461', 'Hugo Ricardo', 'Santillan'),
(84, '1466', 'Martin', 'Gonzalez Rojas'),
(85, '1469', 'Jonatan Eduardo', 'Guereñu'),
(86, '1471', 'Homero Miguel', 'Agüero'),
(87, '1474', 'Noelia Anahi', 'Oviedo'),
(88, '1478', 'Jorge Claudio Jesus', 'Gomez'),
(89, '1481', 'Roberto Antonio', 'Torres'),
(90, '1482', 'Gustavo Orlando', 'Frias'),
(91, '1484', 'Arnaldo Ramon', 'Sanchez'),
(92, '1485', 'Lucas Gaston', 'Vera'),
(93, '1487', 'Franco Gabriel', 'Urquiza'),
(94, '1488', 'Maria Belen', 'Medina'),
(95, '1490', 'Mercedes Liliana', 'Fretes'),
(96, '1491', 'Claudia Ester', 'D´elelessis'),
(97, '1492', 'Cintia Mariela', 'Chaves'),
(98, '1495', 'Silvina Valeria', 'Castro'),
(99, '1496', 'Agustin', 'Frers'),
(100, '1497', 'Eymy', 'Najarro');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proceso`
--

CREATE TABLE `proceso` (
  `id_proceso` int(11) NOT NULL,
  `id_centro` int(11) DEFAULT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `descripcion` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `proceso`
--

INSERT INTO `proceso` (`id_proceso`, `id_centro`, `nombre`, `descripcion`) VALUES
(1, 1, 'a', 'Confección de bolsas de papel'),
(2, 1, 'b', 'Impresión de bolsas de papel'),
(3, 1, 'c', 'Confeción y pegado manual de manijas'),
(4, 1, 'd', 'Ventas y Marketing de bolsas'),
(5, 2, 'a', 'Impresión'),
(6, 2, 'b', 'Encuadernación'),
(7, 2, 'c', 'Preimpresión'),
(8, 2, 'd', 'Despacho'),
(9, 6, 'a', 'Maquina de bolsas'),
(10, 6, 'b', 'Logistica'),
(11, 6, 'c', 'Energía'),
(12, 6, 'd', 'Servicios generales'),
(13, 6, 'e', 'Efluentes');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro_horas_trabajo`
--

CREATE TABLE `registro_horas_trabajo` (
  `id_registro` int(11) NOT NULL,
  `legajo` varchar(4) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `año` year(4) GENERATED ALWAYS AS (year(`fecha`)) STORED,
  `mes` int(11) GENERATED ALWAYS AS (month(`fecha`)) STORED,
  `horas_trabajadas` decimal(5,2) DEFAULT NULL,
  `centro_costo` varchar(3) DEFAULT NULL,
  `proceso` varchar(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `registro_horas_trabajo`
--

INSERT INTO `registro_horas_trabajo` (`id_registro`, `legajo`, `fecha`, `horas_trabajadas`, `centro_costo`, `proceso`) VALUES
(1, '107', '2023-11-01', '0.00', NULL, NULL),

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `centro`
--
ALTER TABLE `centro`
  ADD PRIMARY KEY (`id_centro`);

--
-- Indices de la tabla `informacion_asociados`
--
ALTER TABLE `informacion_asociados`
  ADD PRIMARY KEY (`id_asociado`);

--
-- Indices de la tabla `proceso`
--
ALTER TABLE `proceso`
  ADD PRIMARY KEY (`id_proceso`),
  ADD KEY `id_centro` (`id_centro`);

--
-- Indices de la tabla `registro_horas_trabajo`
--
ALTER TABLE `registro_horas_trabajo`
  ADD PRIMARY KEY (`id_registro`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `centro`
--
ALTER TABLE `centro`
  MODIFY `id_centro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `informacion_asociados`
--
ALTER TABLE `informacion_asociados`
  MODIFY `id_asociado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT de la tabla `proceso`
--
ALTER TABLE `proceso`
  MODIFY `id_proceso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `registro_horas_trabajo`
--
ALTER TABLE `registro_horas_trabajo`
  MODIFY `id_registro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2001;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `proceso`
--
ALTER TABLE `proceso`
  ADD CONSTRAINT `proceso_ibfk_1` FOREIGN KEY (`id_centro`) REFERENCES `centro` (`id_centro`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


--- Contenido de C:\AppServ\www\powermeter\horas\importar_datos_horas_trabajo.py ---
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


--- Contenido de C:\AppServ\www\powermeter\horas\index.php ---
<?php 
include 'templates/header.php'; 
require_once 'includes/db.php';


// Preparar la consulta SQL
$sql = "SELECT * FROM informacion_asociados ";

// Preparar la sentencia
$stmt = $conexion->prepare($sql);

// Vincular parámetros
$stmt->bind_param("s", $legajo);

// Ejecutar la sentencia
$stmt->execute();

// Obtener los resultados
$resultado = $stmt->get_result();

// Cerrar la sentencia
$stmt->close();



// Verificar si hay resultados y mostrarlos
if ($resultado->num_rows > 0) {
    echo "<table border='1'>
            <tr>
                <th>Legajo</th>
                <th>Nombre</th>
                <th>Apellido</th>
            </tr>";
    while($fila = $resultado->fetch_assoc()) {
        echo "<tr>
                <td> <a href='mostrar_horas.php?legajo=".$fila["legajo"]."'>  ".$fila["legajo"]."  </a>   </td>
                <td>".$fila["nombre"]."</td> 
                <td>".$fila["apellido"]."</td> 
            </tr>";
    }
    echo "</table>";
} else {
    echo "No se encontraron resultados.";
}
echo "</body></html>";

// Cerrar la conexión
$conexion->close();

include 'templates/footer.php'; ?>


--- Contenido de C:\AppServ\www\powermeter\horas\informacion_asociados.sql ---
-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 28-12-2023 a las 15:55:46
-- Versión del servidor: 8.0.17
-- Versión de PHP: 7.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `horas`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `informacion_asociados`
--

CREATE TABLE `informacion_asociados` (
  `id_asociado` int(11) NOT NULL,
  `legajo` varchar(4) DEFAULT NULL,
  `nombre` varchar(20) DEFAULT NULL,
  `apellido` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `informacion_asociados`
--

INSERT INTO `informacion_asociados` (`id_asociado`, `legajo`, `nombre`, `apellido`) VALUES
(1, '107', 'Antonio', 'Lopez'),
(2, '238', 'Oreste Mariano', 'Montenegro'),
(3, '240', 'Julian', 'Ortiz'),
(4, '298', 'Jose Andres', 'Ponce'),
(5, '493', 'Eduardo', 'Ayala'),
(6, '532', 'Hugo', 'Vera'),
(7, '574', 'Daniel', 'Arriondo'),
(8, '591', 'Julio', 'Hidalgo'),
(9, '666', 'Gustavo', 'Medrano'),
(10, '835', 'Cristian German', 'Pena'),
(11, '852', 'Ramon', 'Zalazar'),
(12, '853', 'Pablo', 'Paz'),
(13, '857', 'Oscar', 'Bentancourt'),
(14, '932', 'Martin', 'Killing'),
(15, '962', 'Hector Javier', 'Ballesteros'),
(16, '970', 'Miguel Angel', 'Gomez'),
(17, '971', 'Fernando Ariel', 'Utrera'),
(18, '974', 'Facundo Matias', 'Gomez'),
(19, '986', 'Ariel Gustavo', 'Fernandez'),
(20, '1032', 'Rolando Hector', 'Falcon'),
(21, '1035', 'Luis Fernando', 'Serrano'),
(22, '1035', 'Luis Fernando', 'Serrano'),
(23, '1038', 'Cristian Fabian', 'Ferreyra'),
(24, '1046', 'Silverio', 'Sanchez'),
(25, '1047', 'Angel Diego Jose', 'Galeano'),
(26, '1050', 'Gerardo Gaston', 'Leguizamon'),
(27, '1056', 'Matias Osvaldo', 'Hug'),
(28, '1072', 'Damian Emilio', 'Conti'),
(29, '1081', 'Cristian Gabriel', 'Cañete'),
(30, '1083', 'Jorge Gabriel', 'Medina'),
(31, '1118', 'Sebastian Anibal', 'Arrascaeta'),
(32, '1122', 'Mauro Maximiliano', 'Zuccarotto'),
(33, '1129', 'Alcira Amalia', 'Landeira'),
(34, '1137', 'Enrique Emiliano', 'Diaz'),
(35, '1153', 'Sandro Ariel', 'Salazar'),
(36, '1163', 'Gustavo David', 'Brito'),
(37, '1189', 'Martin Andres', 'Arari'),
(38, '1202', 'Rodolfo Walter', 'Osuna'),
(39, '1216', 'Ruben Martin', 'Dirroco'),
(40, '1228', 'Adrian Israel', 'Mancilla'),
(41, '1236', 'Oscar', 'Velazco'),
(42, '1241', 'Jonathan Ezequiel', 'Ledesma'),
(43, '1244', 'Marcelo Adrian Elise', 'Almada'),
(44, '1245', 'Jonatan', 'Gue'),
(45, '1291', 'Jose Antonio', 'Zarate'),
(46, '1310', 'Abel Alejandro', 'Silva'),
(47, '1315', 'Armando Federico', 'Pignataro'),
(48, '1317', 'Emanuel Omar', 'Garzia'),
(49, '1320', 'Carlos Eduardo', 'Cardozo'),
(50, '1325', 'Rodrigo Cristian', 'Rosales Arias'),
(51, '1340', 'David Leonardo', 'Valera Vasquez'),
(52, '1346', 'Julio Dario', 'Almaraz'),
(53, '1347', 'Carlos Alberto', 'Lescano'),
(54, '1352', 'Ricardo German', 'Miño'),
(55, '1388', 'German Diego', 'Gassibe'),
(56, '1395', 'Marcelo Omar', 'Ortega'),
(57, '1397', 'Martin Ezequiel', 'Chaile'),
(58, '1404', 'Agustin Leonardo', 'Bustos'),
(59, '1406', 'Jimena', 'Caruso'),
(60, '1407', 'Vanina', 'Mancuso'),
(61, '1412', 'Maria del Carmen', 'Vallejos'),
(62, '1413', 'Juana Rosa', 'Laime'),
(63, '1414', 'Leandro', 'Quinzano'),
(64, '1415', 'Monica Patricia', 'Butiler'),
(65, '1417', 'Ingrid Geraldina', 'Alarcon'),
(66, '1418', 'Lucrecia Viviana', 'Borge'),
(67, '1420', 'Maria Celeste', 'Paz'),
(68, '1421', 'Anahi Daiana', 'Almada'),
(69, '1422', 'Erica Victoria', 'Gramajo'),
(70, '1423', 'Rocio Noemi', 'Fernandez'),
(71, '1424', 'Monica', 'Salazar'),
(72, '1425', 'Maria de los Angeles', 'Plett'),
(73, '1426', 'Maria Alejandra Leon', 'Cortellcubi'),
(74, '1428', 'Emiliana Hilda', 'Andrade'),
(75, '1430', 'Eliana Edith', 'Villanueva'),
(76, '1431', 'Norma Beatriz', 'Barrientos'),
(77, '1432', 'Maria Vanina', 'Reboredo'),
(78, '1441', 'Gabriela del Carmen', 'Vera'),
(79, '1442', 'Juan Domingo', 'Peralta'),
(80, '1446', 'Nicolas', 'Almarante'),
(81, '1456', 'Mariana Soledad', 'Hogas'),
(82, '1460', 'Roberto Cesar', 'Amador'),
(83, '1461', 'Hugo Ricardo', 'Santillan'),
(84, '1466', 'Martin', 'Gonzalez Rojas'),
(85, '1469', 'Jonatan Eduardo', 'Guereñu'),
(86, '1471', 'Homero Miguel', 'Agüero'),
(87, '1474', 'Noelia Anahi', 'Oviedo'),
(88, '1478', 'Jorge Claudio Jesus', 'Gomez'),
(89, '1481', 'Roberto Antonio', 'Torres'),
(90, '1482', 'Gustavo Orlando', 'Frias'),
(91, '1484', 'Arnaldo Ramon', 'Sanchez'),
(92, '1485', 'Lucas Gaston', 'Vera'),
(93, '1487', 'Franco Gabriel', 'Urquiza'),
(94, '1488', 'Maria Belen', 'Medina'),
(95, '1490', 'Mercedes Liliana', 'Fretes'),
(96, '1491', 'Claudia Ester', 'D´elelessis'),
(97, '1492', 'Cintia Mariela', 'Chaves'),
(98, '1495', 'Silvina Valeria', 'Castro'),
(99, '1496', 'Agustin', 'Frers'),
(100, '1497', 'Eymy', 'Najarro');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `informacion_asociados`
--
ALTER TABLE `informacion_asociados`
  ADD PRIMARY KEY (`id_asociado`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `informacion_asociados`
--
ALTER TABLE `informacion_asociados`
  MODIFY `id_asociado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


--- Contenido de C:\AppServ\www\powermeter\horas\insertar_centro.php ---
<?php
include 'templates/header.php'; 
require_once 'includes/db.php';
require_once 'legajo.php';




// Obtener el legajo desde el parámetro GET
$legajo = isset($_GET['legajo']) ? $_GET['legajo'] : '';

// Preparar la consulta SQL
$sql = "SELECT * FROM registro_horas_trabajo WHERE legajo = ? AND horas_trabajadas > 1 AND centro_costo IS NULL ORDER BY fecha ASC";

// Preparar la sentencia
$stmt = $conexion->prepare($sql);

// Vincular parámetros
$stmt->bind_param("s", $legajo);

// Ejecutar la sentencia
$stmt->execute();

// Obtener los resultados
$resultado = $stmt->get_result();

// Cerrar la sentencia
$stmt->close();

// Comenzar el HTML
echo "<!DOCTYPE html><html><head><title>Registro de Horas</title></head><body>";

if ($resultado->num_rows > 0) {
            // Convertir la fecha a día de la semana
            $dia = date('l', strtotime($fila["fecha"])); // 'l' devuelve el día completo en inglés, p.ej., "Monday"

            // Traducir el día al español
            $diasEnEspañol = [
                'Monday'    => 'Lunes',
                'Tuesday'   => 'Martes',
                'Wednesday' => 'Miércoles',
                'Thursday'  => 'Jueves',
                'Friday'    => 'Viernes',
                'Saturday'  => 'Sábado',
                'Sunday'    => 'Domingo',
            ];
            $diaEnEspañol = isset($diasEnEspañol[$dia]) ? $diasEnEspañol[$dia] : 'Desconocido';
    
    echo "<table border='1'>
            <tr>
                <th>Legajo</th>
                <th>Fecha</th>
                <th>Día</th>
                <th>Horas</th>
                <th>Centro de Costo</th>
                <th>Acción</th>
            </tr>";
    while ($fila = $resultado->fetch_assoc()) {
        echo "<tr>
                <td>".$fila["legajo"]."</td>
                <td>".$fila["fecha"]."</td>  
                <td>".$diaEnEspañol."</td>  
                <td>".$fila["horas_trabajadas"]."</td> 
                <form action='procesar.php' method='GET'>
                    <td>
                        <select name='centro_costo'>
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
                        <input type='hidden' name='legajo' value='".$fila["legajo"]."'>
                        <input type='hidden' name='fecha' value='".$fila["fecha"]."'>
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
$conexion->close();
?>



--- Contenido de C:\AppServ\www\powermeter\horas\legajo.php ---
<?php
require_once 'includes/db.php'; // Asegúrate de que la ruta al archivo db.php sea correcta

// Obtener el legajo desde el parámetro GET
$legajo = isset($_GET['legajo']) ? $_GET['legajo'] : '';

// Verificar si el legajo no está vacío
if (!empty($legajo)) {
    // Preparar la consulta SQL
    $sql = "SELECT * FROM informacion_asociados WHERE legajo = ?";
    
    // Preparar la sentencia
    if ($stmt = $conexion->prepare($sql)) {
        // Vincular parámetros
        $stmt->bind_param("s", $legajo);
        
        // Ejecutar la sentencia
        $stmt->execute();

        // Obtener los resultados
        $resultado = $stmt->get_result();

        // Verificar si hay resultados
        if ($resultado->num_rows > 0) {
            // Iniciar la tabla
            echo "<table border='1'>";
            echo "<tr><th>Legajo</th><th>Nombre</th><th>Apellido</th></tr>"; // Encabezados de la tabla
        
            // Mostrar los resultados en filas de la tabla
            while ($fila = $resultado->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($fila['legajo']) . "</td>";
                echo "<td>" . htmlspecialchars($fila['nombre']) . "</td>";
                echo "<td>" . htmlspecialchars($fila['apellido']) . "</td>";
                echo "</tr>";
            }
        
            // Cerrar la tabla
            echo "</table><br>";
        } else {
            echo "No se encontraron resultados para el legajo: $legajo";
        }
        // Cerrar la sentencia
        $stmt->close();
    } else {
        echo "Error al preparar la consulta: " . $conexion->error;
    }
} else {
    echo "Por favor, proporcione un legajo.<br>";
}




--- Contenido de C:\AppServ\www\powermeter\horas\mostrar_horas.php ---
<?php
#mostrar_horas.php
include 'templates/header.php'; 
require_once 'includes/db.php';
require_once 'legajo.php';


// Obtener el legajo desde el parámetro GET
$legajo = isset($_GET['legajo']) ? $_GET['legajo'] : '';

// Verificar si el legajo no está vacío
if (!empty($legajo)) { $sql = "SELECT * FROM registro_horas_trabajo WHERE legajo = ? AND horas_trabajadas > 1 ORDER BY fecha ASC";
}else {$sql = "SELECT * FROM registro_horas_trabajo WHERE  horas_trabajadas > 1 ORDER BY fecha ASC";
}



// Preparar la consulta SQL


// Preparar la sentencia
$stmt = $conexion->prepare($sql);

// Vincular parámetros
$stmt->bind_param("s", $legajo);

// Ejecutar la sentencia
$stmt->execute();

// Obtener los resultados
$resultado = $stmt->get_result();

// Cerrar la sentencia
$stmt->close();

// Comenzar el HTML
echo "<!DOCTYPE html><html><head><title>Registro de Horas</title></head><body>";
function obtenerNombreCentroCosto($codigo) {
    $nombresCentroCosto = [
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

    ];

    return isset($nombresCentroCosto[$codigo]) ? $nombresCentroCosto[$codigo] : 'Desconocido';
}


// Verificar si hay resultados y mostrarlos
if ($resultado->num_rows > 0) {
            // Convertir la fecha a día de la semana
            $dia = date('l', strtotime($fila["fecha"])); // 'l' devuelve el día completo en inglés, p.ej., "Monday"

            // Traducir el día al español
            $diasEnEspañol = [
                'Monday'    => 'Lunes',
                'Tuesday'   => 'Martes',
                'Wednesday' => 'Miércoles',
                'Thursday'  => 'Jueves',
                'Friday'    => 'Viernes',
                'Saturday'  => 'Sábado',
                'Sunday'    => 'Domingo',
            ];
            $diaEnEspañol = isset($diasEnEspañol[$dia]) ? $diasEnEspañol[$dia] : 'Desconocido';
    
    echo "<table border='1'>
            <tr>
                <th>Legajo</th>
                <th>Fecha</th>
                <th>Día</th>
                <th>Horas</th>
                <th>Centro de costo</th>
                <th>Proceso</th>
            </tr>";
    while($fila = $resultado->fetch_assoc()) {
        echo "<tr>
                <td>".$fila["legajo"]."</td>
                <td>".$fila["fecha"]."</td>  
                <td>".$diaEnEspañol."</td>  
                <td>".$fila["horas_trabajadas"]."</td><td>";
                echo obtenerNombreCentroCosto($fila["centro_costo"]);
                echo "</td><td>".$fila["proceso"]."</td> </tr>";
    }
    echo "</table>";
} else {
    echo "No se encontraron resultados.";
}


// Finalizar el HTML
echo "</body></html>";

// Cerrar la conexión
$conexion->close();
?>


--- Contenido de C:\AppServ\www\powermeter\horas\procesar.php ---
<?php
//procesar.php
require_once 'includes/db.php';

// Verificar si los parámetros GET están establecidos
if (isset($_GET['legajo']) && isset($_GET['centro_costo']) && isset($_GET['fecha'])) {
    $legajo = $_GET['legajo'];
    $centro_costo = $_GET['centro_costo'];
    $fecha = $_GET['fecha'];

    // Validar los datos aquí (si es necesario)

    // Preparar la consulta SQL para actualizar
    $sql = "UPDATE registro_horas_trabajo SET centro_costo = ? WHERE legajo = ? AND fecha = ?";

    // Preparar la sentencia
    if ($stmt = $conexion->prepare($sql)) {
        // Vincular parámetros
        $stmt->bind_param("sss", $centro_costo, $legajo, $fecha);

        // Ejecutar la sentencia
        if ($stmt->execute()) {
            // Enviar un mensaje de confirmación a insertar_centro.php
            header("Location: insertar_centro.php?legajo=$legajo&actualizado=1");
            exit;
        } else {
            echo "Error al actualizar el centro de costo: " . $conexion->error;
        }

        // Cerrar la sentencia
        $stmt->close();
    } else {
        echo "Error al preparar la consulta: " . $conexion->error;
    }
} else {
    echo "Legajo, centro de costo o fecha no proporcionados.";
}

// Cerrar la conexión
$conexion->close();
?>


--- Contenido de C:\AppServ\www\powermeter\horas\RegistroHorasTrabajo.sql ---
CREATE TABLE registro_horas_trabajo (
    id_registro INT AUTO_INCREMENT PRIMARY KEY,
    legajo VARCHAR(4),
    fecha DATE,
    horas_trabajadas DECIMAL(5, 2),
    centro_costo VARCHAR(3),
    sub_centro_costo VARCHAR(3)

);


--- Contenido de C:\AppServ\www\powermeter\horas\CSS\header.css ---
/*-----------------------CONTENT--------------*/

div.content {
  margin-left: 250px;
  padding: 1px 16px;

}

@media screen and (max-width: 750px) {

  div.content {margin-left: 0;
              padding: 220px 16px;}
            }


@media screen and (max-width: 690px) { div.content {padding: 1px 8px;}        }
@media screen and (max-width: 680px) { div.content {padding: 1px 6px;}        }
@media screen and (max-width: 670px) { div.content {padding: 1px 4px;}        }
@media screen and (max-width: 660px) { div.content {padding: 1px 2px;}        }
@media screen and (max-width: 650px) {div.content {padding: 1px 1px;}        }


/*---------------------------TOPNAV--------------------------*/

 .topnav ul {
           list-style-type: none;
           margin: 0;
           padding: 0;
           overflow: hidden;
           background-color: #333;
           position: fixed;
           top: 0;
           width: 100%;                     }

.topnav li {  float: left;
              font-size: 16px;
                   }

.topnav li a {
  display: block;
  color: white;
  text-align: center;
  padding: 12px 8px;
  text-decoration: none;
}

.topnav li a:hover:not(.active) {
  background-color: #111;
}

.topnav .active {
  background-color: #4CAF50;
}


@media screen and (max-width: 1275px) { .topnav li {font-size: 15px;         }
@media screen and (max-width: 1200px) { .topnav li {font-size: 14px;         }
@media screen and (max-width: 1150px) { .topnav li {font-size: 13px;         }
@media screen and (max-width: 1100px) { .topnav li {font-size: 12px;         }
@media screen and (max-width: 1075px) { .topnav li {font-size: 11px;         }
@media screen and (max-width: 1010px) { .topnav li {font-size: 10px;         }
@media screen and (max-width: 995px) { .topnav li {font-size: 9px;         }




.alert-danger{color:#721c24;background-color:#f8d7da;border-color:#f5c6cb}
.alert-danger hr{border-top-color:#f1b0b7}
.alert-danger .alert-link{color:#491217}


--- Contenido de C:\AppServ\www\powermeter\horas\CSV\config.php ---
<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '12346578');
define('DB_NAME', 'horas');
?>


--- Contenido de C:\AppServ\www\powermeter\horas\includes\config.php ---
<?php
$servername = "localhost";
$username = "root";
$password = "12345678";
$dbname = "horas";
?>


--- Contenido de C:\AppServ\www\powermeter\horas\includes\db.php ---
<?php
require_once 'config.php';

// Crear conexión
$conexion = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}
?>


--- Contenido de C:\AppServ\www\powermeter\horas\templates\footer.php ---
<footer>
    <!-- Contenido del pie de página -->
</footer>
</body>
</html>


--- Contenido de C:\AppServ\www\powermeter\horas\templates\header.php ---
<?php 
// Obtener el legajo desde el parámetro GET

$legajo = isset($_GET['legajo']) ? $_GET['legajo'] : '';



echo '<!DOCTYPE html><html><head>     <meta charset="UTF-8"> <link rel="stylesheet" type="text/css" href="CSS/index.css"> <link rel="stylesheet" type="text/css" href="CSS/header.css"> <meta name="viewport" content="width=device-width, initial-scale=1.0"> <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon"> <link rel="icon" href="/imagenes/favicon.ico" type="image/x-icon"> <title>Registro de Horas</title></head><body>';
echo "<header> <br><br><br>    <div class='topnav'> <ul>";
echo "<li><a href='index.php' >Inicio</a></li>";
echo "<li><a href='insertar_centro.php?legajo=".$legajo."'>Insertar</a></li>";
echo "<li><a href='mostrar_horas.php?legajo=".$legajo."'>Visualizar</a></li>";
echo "<li><a href='actualizar_centro.php?legajo=".$legajo."'>Actualizar</a></li>";
echo "<li><a href='centro_costo.php'>Centro de Costos</a></li>";
echo "<li><a href='/phpMyAdmin/' target='_blank'>Visit the AppServ Open Project</a></li>";
echo "</ul></div></header>"
?>


       


