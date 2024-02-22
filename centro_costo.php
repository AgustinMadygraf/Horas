<!--centro_costo.php-->
<br><br><form method="GET" action="centro_costo.php">
    Fecha inicio: <input type="date" name="fecha_inicio" value="2023-11-01">
    Fecha fin: <input type="date" name="fecha_fin" value="2023-11-20">
    <input type="submit" value="Filtrar">
</form>

<?php
include 'templates/header.php'; 
require_once 'includes/db.php';
require_once 'includes/helpers.php';

$fecha_inicio = isset($_GET['fecha_inicio']) && !empty($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : '2023-11-01';
$fecha_fin = isset($_GET['fecha_fin']) && !empty($_GET['fecha_fin']) ? $_GET['fecha_fin'] : '2023-11-20';

function obtenerDatosCentroCosto($conexion, $fecha_inicio, $fecha_fin) {
    $sql = "SELECT COALESCE(centro_costo, 'Sin Asignar') AS centro_costo, SUM(horas_trabajadas) AS total_horas FROM registro_horas_trabajo GROUP BY COALESCE(centro_costo, 'Sin Asignar') ORDER BY total_horas DESC";
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

try {
    list($datosGrafico, $totalHoras, $resultado, $sql) = obtenerDatosCentroCosto($conexion, $fecha_inicio, $fecha_fin);
    $datosJson = json_encode($datosGrafico);
} catch (Exception $e) {
    error_log("Error en centro_costo_logic.php: " . $e->getMessage());
    // Manejo del error
}


$conexion->close();
include 'includes/centro_costo_chart.php';
include 'includes/centro_costo_table.php';




echo '<br><br><br> Datos desde "'.$fecha_inicio.'" hasta "'.$fecha_fin.'" <br>';
echo "SQL = <br>" . htmlspecialchars($sql);

?>
