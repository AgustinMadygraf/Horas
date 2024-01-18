<?php
//registro_horas.php
define('BASE_PATH', __DIR__); 
define('TEMPLATES_PATH', BASE_PATH . '/templates'); 
define('INCLUDES_PATH', BASE_PATH . '/includes'); 

include TEMPLATES_PATH . '/header.php';
require_once INCLUDES_PATH . '/db.php';
require_once INCLUDES_PATH . '/legajo.php';
require_once INCLUDES_PATH . '/centro_costo_helper.php';

// Validar y sanear la entrada del legajo
$legajo = isset($_GET['legajo']) ? $_GET['legajo'] : '';
$legajo = filter_var($legajo, FILTER_SANITIZE_STRING);

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

// Incluir el archivo de plantilla para la presentación
include TEMPLATES_PATH . '/registro_horas_template.php';

$conexion->close();
?>
