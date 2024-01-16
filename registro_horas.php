<?php
#registro_horas.php
define('BASE_PATH', __DIR__); // Ruta base del proyecto
define('TEMPLATES_PATH', BASE_PATH . '/templates'); // Ruta de los archivos de plantillas
define('INCLUDES_PATH', BASE_PATH . '/includes'); // Ruta de los archivos de inclusión

include TEMPLATES_PATH . '/header.php';
require_once INCLUDES_PATH . '/db.php';
require_once 'legajo.php';


// Obtener el legajo desde el parámetro GET
$legajo = isset($_GET['legajo']) ? $_GET['legajo'] : '';

// Verificar si el legajo no está vacío y construir la consulta SQL
if (!empty($legajo)) {
    $sql = "SELECT * FROM registro_horas_trabajo WHERE legajo = ? AND horas_trabajadas > 1 ORDER BY fecha ASC";
} else {
    $sql = "SELECT * FROM registro_horas_trabajo WHERE  horas_trabajadas > 1 ORDER BY fecha ASC";
}

// Verificar si la conexión a la base de datos se estableció correctamente y manejar errores
if ($conexion) {
    $stmt = $conexion->prepare($sql);
    if (!$stmt) {
        die("Error al preparar la consulta: " . $conexion->error);
    }
} else {
    die("Error al conectar a la base de datos");
}


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
