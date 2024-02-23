<!--centro_costo.php-->
<?php include 'includes/centro_costo_logic.php'; ?>
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
echo "Cantidad de días seleccionados: " . $intervalo_dias . "<br>";
include 'templates/header.php'; 
require_once 'includes/db.php';
require_once 'includes/helpers.php';

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

?>
