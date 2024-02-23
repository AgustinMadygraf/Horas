<!--includes/centro_costo_logic.php-->
<?php

setlocale(LC_TIME, 'es_ES', 'Spanish_Spain', 'Spanish');

$fecha_inicio = isset($_GET['fecha_inicio']) && !empty($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : '2023-11-01';
$fecha_fin = isset($_GET['fecha_fin']) && !empty($_GET['fecha_fin']) ? $_GET['fecha_fin'] : '2023-11-20';
$fecha_inicio_formato = date("d/m/Y", strtotime($fecha_inicio));
$fecha_fin_formato = date("d/m/Y", strtotime($fecha_fin));
$fechaInicioObj = new DateTime($fecha_inicio);
$fechaFinObj = new DateTime($fecha_fin);
$intervalo = $fechaInicioObj->diff($fechaFinObj);
$intervalo_dias = $intervalo->days;

function formatearFechaCompleta($fecha) {
    $formatter = new IntlDateFormatter('es_ES', IntlDateFormatter::FULL, IntlDateFormatter::NONE);
    $fechaDateTime = new DateTime($fecha);
    return $formatter->format($fechaDateTime);
}

$fecha_inicio_formato_completo = formatearFechaCompleta($fecha_inicio);
$fecha_fin_formato_completo = formatearFechaCompleta($fecha_fin);

require_once 'includes/db.php';
require_once 'includes/helpers.php';

function obtenerDatosCentroCosto($conexion, $fecha_inicio, $fecha_fin) {
    
    //$sql = "SELECT COALESCE(centro_costo, 'Sin Asignar') AS centro_costo, SUM(horas_trabajadas) AS total_horas FROM registro_horas_trabajo GROUP BY COALESCE(centro_costo, 'Sin Asignar') ORDER BY total_horas DESC";
    $sql = "SELECT COALESCE(centro_costo, 'Sin Asignar') AS centro_costo, SUM(horas_trabajadas) AS total_horas 
    FROM registro_horas_trabajo 
    WHERE fecha BETWEEN ? AND ? 
    GROUP BY COALESCE(centro_costo, 'Sin Asignar') 
    ORDER BY total_horas DESC";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ss", $fecha_inicio, $fecha_fin);
    
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
    return [$datosGrafico, $totalHoras, $resultado,$sql];
}
?>
