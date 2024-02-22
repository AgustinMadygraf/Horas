<!--centro_costo.php-->
<?php
include 'templates/header.php'; 
require_once 'includes/db.php';
require_once 'includes/helpers.php';

function obtenerDatosCentroCosto($conexion, $fecha_inicio, $fecha_fin) {
    $sql = "SELECT COALESCE(centro_costo, 'Sin Asignar') AS centro_costo, SUM(horas_trabajadas) AS total_horas FROM registro_horas_trabajo GROUP BY COALESCE(centro_costo, 'Sin Asignar') ORDER BY total_horas DESC";
    $stmt = $conexion->prepare($sql);
    
    if (!$stmt->execute()) {
        throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
    }

    $resultado = $stmt->get_result();
    if (!$resultado) {
        throw new Exception("Error al obtener resultados: " . $conexion->error);
    }

    $datosGrafico = [["Centro de Costo", "Horas"]];
    $totalHoras = 0;

    while ($fila = $resultado->fetch_assoc()) {
        $totalHoras += $fila["total_horas"];
        $nombreCentro = obtenerNombreCentroCosto($fila["centro_costo"]);
        array_push($datosGrafico, [$nombreCentro, (float)$fila["total_horas"]]);
    }

    $stmt->close();
    return [$datosGrafico, $totalHoras, $resultado];
}

try {
    list($datosGrafico, $totalHoras, $resultado) = obtenerDatosCentroCosto($conexion, $fecha_inicio, $fecha_fin);
    $datosJson = json_encode($datosGrafico);
} catch (Exception $e) {
    error_log("Error en centro_costo_logic.php: " . $e->getMessage());
    // Manejo del error
}

$conexion->close();
include 'includes/centro_costo_chart.php';
include 'includes/centro_costo_table.php';




echo "<br><br><br> Datos desde $fecha_inicio hasta $fecha_fin <br>";

?>
