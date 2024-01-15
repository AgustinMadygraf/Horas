<?php
require_once 'includes/db.php';
require_once 'helpers.php';

function obtenerDatosCentroCosto() {
    global $conexion;

    // Preparar la consulta SQL utilizando consultas preparadas
    $sql = "SELECT COALESCE(centro_costo, 'Sin Asignar') AS centro_costo, SUM(horas_trabajadas) AS total_horas FROM registro_horas_trabajo GROUP BY COALESCE(centro_costo, 'Sin Asignar') ORDER BY `total_horas` DESC";

    // Preparar la sentencia
    $stmt = $conexion->prepare($sql);

    // Ejecutar la sentencia
    $stmt->execute();

    // Obtener los resultados
    $resultado = $stmt->get_result();

    // Cerrar la sentencia
    $stmt->close();

    $datosGrafico = [["Centro de Costo", "Horas"]];
    $totalHoras = 0;

    if ($resultado) {
        while ($fila = $resultado->fetch_assoc()) {
            $totalHoras += $fila["total_horas"];
            $nombreCentro = obtenerNombreCentroCosto($fila["centro_costo"]);
            array_push($datosGrafico, [$nombreCentro, (float)$fila["total_horas"]]);
        }
    }

    // Cerrar la conexión
    $conexion->close();

    return [
        'datosGrafico' => $datosGrafico,
        'totalHoras' => $totalHoras
    ];
}
