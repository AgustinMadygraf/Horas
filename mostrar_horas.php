<?php
// registro_horas.php

// Definiciones de rutas
define('BASE_PATH', __DIR__); 
define('TEMPLATES_PATH', BASE_PATH . '/templates'); 
define('INCLUDES_PATH', BASE_PATH . '/includes'); 

// Inclusión de archivos
include TEMPLATES_PATH . '/header.php';
require_once INCLUDES_PATH . '/db.php';
require_once INCLUDES_PATH . '/centro_costo_helper.php';
require_once INCLUDES_PATH . '/DatabaseConnection.php';
require_once INCLUDES_PATH . '/DateHelper.php';


// Función para preparar y configurar la consulta SQL
function prepararConsulta($conexion, $legajo) {
    // Modificamos la consulta SQL para incluir un JOIN con la tabla informacion_asociados
    // y seleccionar los campos nombre y apellido además de los campos ya existentes.
    $sql = "SELECT rht.*, ia.nombre, ia.apellido 
            FROM registro_horas_trabajo rht
            INNER JOIN informacion_asociados ia ON rht.legajo = ia.legajo 
            WHERE rht.horas_trabajadas > 1";
            
    $parametros = array();
    $tipos = '';

    if (!empty($legajo)) {
        $sql .= " AND rht.legajo = ?";
        $parametros[] = & $legajo;
        $tipos .= 's'; // Asumiendo que legajo es una cadena
    }

    $stmt = $conexion->prepare($sql);
    if (!$stmt) {
        throw new Exception("Error al preparar la consulta: " . $conexion->error);
    }

    if (!empty($legajo)) {
        call_user_func_array(array($stmt, 'bind_param'), array_merge(array($tipos), $parametros));
    }

    return $stmt;
}


// Manejo principal
try {
    $legajo = isset($_GET['legajo']) ? filter_var($_GET['legajo'], FILTER_SANITIZE_STRING) : '';

    // Obtener la instancia de la conexión a la base de datos
    $dbInstance = DatabaseConnection::getInstance();
    $conexion = $dbInstance->getConnection();

    $stmt = prepararConsulta($conexion, $legajo);

    if (!$stmt->execute()) {
        throw new Exception("Error al ejecutar la sentencia: " . $stmt->error);
    }

    $resultado = $stmt->get_result();
    $stmt->close();

    include TEMPLATES_PATH . '/registro_horas_template.php';
} catch (Exception $e) {
    error_log($e->getMessage());
    echo "Se ha producido un error. Por favor, intente de nuevo más tarde.";
    exit;
}
