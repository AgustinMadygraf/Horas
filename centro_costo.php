<?php
include 'templates/header.php'; 
require_once 'includes/db.php';
require_once 'helpers.php';

try {
    // Preparar la consulta SQL utilizando consultas preparadas para mayor seguridad
    $sql = "SELECT COALESCE(centro_costo, 'Sin Asignar') AS centro_costo, SUM(horas_trabajadas) AS total_horas FROM registro_horas_trabajo GROUP BY COALESCE(centro_costo, 'Sin Asignar') ORDER BY total_horas DESC";
    
    // Preparar la sentencia
    $stmt = $conexion->prepare($sql);
    
    // Ejecutar la sentencia
    if (!$stmt->execute()) {
        throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
    }

    // Obtener los resultados
    $resultado = $stmt->get_result();

    if (!$resultado) {
        throw new Exception("Error al obtener resultados: " . $conexion->error);
    }

    $totalHoras = 0;
    $datosGrafico = [["Centro de Costo", "Horas"]];

    while ($fila = $resultado->fetch_assoc()) {
        $totalHoras += $fila["total_horas"];
        $nombreCentro = obtenerNombreCentroCosto($fila["centro_costo"]);
        array_push($datosGrafico, [$nombreCentro, (float)$fila["total_horas"]]);
    }

    $datosJson = json_encode($datosGrafico);

    // Cerrar la sentencia
    $stmt->close();
} catch (Exception $e) {
    // Manejar la excepción
    error_log("Error en centro_costo_logic.php: " . $e->getMessage());
    // Puedes optar por mostrar un mensaje de error al usuario o redirigirlo a una página de error.
}

// Cerrar la conexión a la base de datos
$conexion->close();

// Pasar datos al archivo de presentación
include 'centro_costo_view.php';
?>
