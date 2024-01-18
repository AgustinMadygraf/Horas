<?php
include 'templates/header.php'; 
require_once 'includes/db.php';
require_once 'includes/legajo.php';




// Obtener el legajo desde el parámetro GET
$legajo = isset($_GET['legajo']) ? $_GET['legajo'] : '';

// Preparar la consulta SQL
$sql = "SELECT * FROM registro_horas_trabajo WHERE legajo = ? AND horas_trabajadas > 1 AND centro_costo IS NULL ORDER BY fecha ASC";

// Preparar la sentencia
$stmt = $conexion->prepare($sql);

// Vincular parámetros
$stmt->bind_param("s", $legajo);

// Ejecutar la sentencia
$stmt->execute();

// Obtener los resultados
$resultado = $stmt->get_result();

// Cerrar la sentencia
$stmt->close();

// Comenzar el HTML
echo "<!DOCTYPE html><html><head><title>Registro de Horas</title></head><body>";

if ($resultado->num_rows > 0) {
            // Convertir la fecha a día de la semana
            $dia = date('l', strtotime($fila["fecha"])); // 'l' devuelve el día completo en inglés, p.ej., "Monday"

            // Traducir el día al español
            $diasEnEspañol = [
                'Monday'    => 'Lunes',
                'Tuesday'   => 'Martes',
                'Wednesday' => 'Miércoles',
                'Thursday'  => 'Jueves',
                'Friday'    => 'Viernes',
                'Saturday'  => 'Sábado',
                'Sunday'    => 'Domingo',
            ];
            $diaEnEspañol = isset($diasEnEspañol[$dia]) ? $diasEnEspañol[$dia] : 'Desconocido';
    
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
        echo "<tr>
                <td>".$fila["legajo"]."</td>
                <td>".$fila["fecha"]."</td>  
                <td>".$diaEnEspañol."</td>  
                <td>".$fila["horas_trabajadas"]."</td> 
                <form action='procesar.php' method='GET'>
                    <td>
                        <select name='centro_costo'>
                            <option value=''></option>    
                            <option value='1'>Maquina de bolsas</option>
                            <option value='2'>Boletas y folletería</option>
                            <option value='3'>Logistica</option>
                            <option value='4'>Administración</option>
                            <option value='5'>Club</option>
                            <option value='6'>Mantenimiento</option>
                            <option value='7'>Comedor</option>
                            <option value='8'>Guardia</option>
                            <option value='9'>Sistemas</option>
                            <option value='10'>Enfermería</option>
                        </select> </td>  
                        <input type='hidden' name='legajo' value='".$fila["legajo"]."'>
                        <input type='hidden' name='fecha' value='".$fila["fecha"]."'>
                    <td><input type='submit' value='Guardar'></td>
                    </form>
                </td>
            </tr>";
    }
    echo "</table>";
} else {
    echo "No se encontraron resultados para agregar centro de costos.";
}

// Finalizar el HTML
echo "</body></html>";

// Cerrar la conexión
$conexion->close();
?>

