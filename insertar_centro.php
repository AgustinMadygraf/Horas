<?php
include 'templates/header.php'; 
require_once 'includes/db.php';
require_once 'includes/legajo.php';

// Obtener el legajo desde el parámetro GET
$legajo = isset($_GET['legajo']) ? $_GET['legajo'] : '';

// Modificar la consulta SQL basada en si se proporcionó un legajo
$sql = $legajo !== '' ? 
    "SELECT * FROM registro_horas_trabajo WHERE legajo = ? AND horas_trabajadas > 1 AND centro_costo IS NULL ORDER BY fecha ASC" :
    "SELECT * FROM registro_horas_trabajo WHERE horas_trabajadas > 1 AND centro_costo IS NULL ORDER BY fecha ASC";

// Preparar la sentencia
$stmt = $conexion->prepare($sql);

// Vincular parámetros solo si se proporcionó un legajo
if ($legajo !== '') {
    $stmt->bind_param("s", $legajo);
}

// Ejecutar la sentencia
$stmt->execute();

// Obtener los resultados
$resultado = $stmt->get_result();

// Cerrar la sentencia
$stmt->close();

// Comenzar el HTML
echo "<!DOCTYPE html><html><head><title>Registro de Horas</title></head><body>";

if ($resultado->num_rows > 0) {
    echo "<table border='1'>
            <tr>
                <th>Legajo</th>
                <th>Fecha</th>
                <th>Día</th>
                <th>Horas</th>
                <th>Centro de Costo</th>
                <th>Acción</th>
            </tr>";
    while ($fila = $resultado->fetch_assoc()) {
        // Convertir la fecha a día de la semana y traducirlo al español
        $diaEnEspañol = date('l', strtotime($fila["fecha"]));
        $diasEnEspañol = ['Monday' => 'Lunes', 'Tuesday' => 'Martes', 'Wednesday' => 'Miércoles', 'Thursday' => 'Jueves', 'Friday' => 'Viernes', 'Saturday' => 'Sábado', 'Sunday' => 'Domingo'];
        $diaEnEspañol = $diasEnEspañol[$diaEnEspañol] ?? 'Desconocido';

        echo "<tr>
                <td>".$fila["legajo"]."</td>
                <td>".$fila["fecha"]."</td>  
                <td>".$diaEnEspañol."</td>  
                <td>".$fila["horas_trabajadas"]."</td> 
                <form action='procesar.php' method='GET'>
                    <td>
                        <select name='centro_costo'>
                            <option value=''></option>
                            <!-- Opciones del centro de costo -->
                        </select>
                    </td>  
                    <input type='hidden' name='legajo' value='".$fila["legajo"]."'>
                    <input type='hidden' name='fecha' value='".$fila["fecha"]."'>
                    <td><input type='submit' value='Guardar'></td>
                </form>
            </tr>";
    }
    echo "</table>";
} else {
    echo "No se encontraron resultados para agregar centro de costos.";
}

echo "</body></html>";
$conexion->close();
?>
