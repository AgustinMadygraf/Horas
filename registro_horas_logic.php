<?php
#registro_horas.php
define('BASE_PATH', __DIR__); // Ruta base del proyecto
define('TEMPLATES_PATH', BASE_PATH . '/templates'); // Ruta de los archivos de plantillas
define('INCLUDES_PATH', BASE_PATH . '/includes'); // Ruta de los archivos de inclusión

include TEMPLATES_PATH . '/header.php';
require_once INCLUDES_PATH . '/db.php';
require_once 'includes/legajo.php';

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

// Pasar a la vista para la presentación
include 'registro_horas_view.php';

// Cerrar la conexión
$conexion->close();
