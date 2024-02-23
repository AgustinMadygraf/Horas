<!--centro_costo.php-->
<br><br>
<?php
setlocale(LC_TIME, 'es_ES', 'Spanish_Spain', 'Spanish');

$fecha_inicio = isset($_GET['fecha_inicio']) && !empty($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : '2023-11-01';
$fecha_fin = isset($_GET['fecha_fin']) && !empty($_GET['fecha_fin']) ? $_GET['fecha_fin'] : '2023-11-20';
$fecha_inicio_formato = date("d/m/Y", strtotime($fecha_inicio));
$fecha_fin_formato = date("d/m/Y", strtotime($fecha_fin));
$intervalo_dias = $fecha_fin - $fecha_inicio; //ayuda acá
// Creación de objetos DateTime para las fechas de inicio y fin
$fechaInicioObj = new DateTime($fecha_inicio);
$fechaFinObj = new DateTime($fecha_fin);
function formatearFechaCompleta($fecha) {
    $formatter = new IntlDateFormatter('es_ES', IntlDateFormatter::FULL, IntlDateFormatter::NONE);
    $fechaDateTime = new DateTime($fecha);
    return $formatter->format($fechaDateTime);
}
// Calculando la diferencia entre las fechas
$intervalo = $fechaInicioObj->diff($fechaFinObj);
$fecha_inicio_formato_completo = formatearFechaCompleta($fecha_inicio);
$fecha_fin_formato_completo = formatearFechaCompleta($fecha_fin);
// Extrayendo el número total de días del intervalo
$intervalo_dias = $intervalo->days;
?>
<br>
<form method="GET" action="centro_costo.php">
    <table>    
        <tr>
            <td>Fecha inicio:</td>
            <td><input type="date" name="fecha_inicio" value="<?php echo htmlspecialchars($fecha_inicio); ?>"></td>
            <td><?php echo htmlspecialchars($fecha_inicio_formato); ?></td>
            <td><?php echo htmlspecialchars($fecha_inicio_formato_completo); ?></td>
        </tr>
        <tr>
            <td>Fecha fin:</td>
            <td><input type="date" name="fecha_fin" value="<?php echo htmlspecialchars($fecha_fin); ?>"></td>
            <td><?php echo htmlspecialchars($fecha_fin_formato); ?></td>
            <td><?php echo htmlspecialchars($fecha_fin_formato_completo); ?></td>
        </tr>
        <tr>
            <td colspan="4"><input type="submit" value="Filtrar"></td>
        </tr>
    </table>
</form>



<?php


echo "Cantidad de días seleccionados: " . $intervalo_dias . "<br><br>";

include 'templates/header.php'; 
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

$fecha_inicio_formato = date("d/m/Y", strtotime($fecha_inicio));
$fecha_fin_formato = date("d/m/Y", strtotime($fecha_fin));

//echo '<br><br><br> Datos desde "'.$fecha_inicio_formato.'" hasta "'.$fecha_fin_formato.'" <br>';
//echo "SQL = <br>" . htmlspecialchars($sql);

?>
