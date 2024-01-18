<?php
//registro_horas.php
define('BASE_PATH', __DIR__); 
define('TEMPLATES_PATH', BASE_PATH . '/templates'); 
define('INCLUDES_PATH', BASE_PATH . '/includes'); 

include TEMPLATES_PATH . '/header.php';
require_once INCLUDES_PATH . '/db.php';
require_once INCLUDES_PATH . '/legajo.php';
require_once INCLUDES_PATH . '/centro_costo_helper.php';

$legajo = isset($_GET['legajo']) ? $_GET['legajo'] : '';

// Preparar la consulta SQL
$sql = "SELECT * FROM registro_horas_trabajo WHERE horas_trabajadas > 1";
$parametros = array();
$tipos = '';

// Agregar condición para el legajo si no está vacío
if (!empty($legajo)) {
    $sql .= " AND legajo = ?";
    $parametros[] = & $legajo;
    $tipos .= 's'; // Tipo 'string' para legajo
}

// Verificar si la conexión a la base de datos se estableció correctamente
if (!$conexion) {
    die("Error al conectar a la base de datos: " . mysqli_connect_error());
}

$stmt = $conexion->prepare($sql);
if (!$stmt) {
    die("Error al preparar la consulta: " . $conexion->error);
}

// Vincular parámetros si es necesario
if (!empty($legajo)) {
    call_user_func_array(array($stmt, 'bind_param'), array_merge(array($tipos), $parametros));
}

if (!$stmt->execute()) {
    die("Error al ejecutar la sentencia: " . $stmt->error);
}

$resultado = $stmt->get_result();
$stmt->close();

echo "<!DOCTYPE html><html><head><title>Registro de Horas</title></head><body>";

if ($resultado->num_rows > 0) {
    echo "<table border='1'><tr><th>Legajo</th><th>Fecha</th><th>Día</th><th>Horas</th><th>Centro de costo</th><th>Proceso</th></tr>";
    while($fila = $resultado->fetch_assoc()) {
        $dia = date('l', strtotime($fila["fecha"]));
        $diaEnEspañol = $diasEnEspañol[$dia] ?? 'Desconocido';
        echo "<tr><td>".$fila["legajo"]."</td><td>".$fila["fecha"]."</td><td>".$diaEnEspañol."</td><td>".$fila["horas_trabajadas"]."</td><td>".obtenerNombreCentroCosto($fila["centro_costo"])."</td><td>".$fila["proceso"]."</td></tr>";
    }
    echo "</table>";
} else {
    echo "No se encontraron resultados.";
}

echo "</body></html>";
$conexion->close();
?>
