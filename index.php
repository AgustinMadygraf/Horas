<?php 
include 'templates/header.php'; 
require_once 'includes/db.php';


// Preparar la consulta SQL
$sql = "SELECT * FROM informacion_asociados ";

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



// Verificar si hay resultados y mostrarlos
if ($resultado->num_rows > 0) {
    echo "<table border='1'>
            <tr>
                <th>Legajo</th>
                <th>Nombre</th>
                <th>Apellido</th>
            </tr>";
    while($fila = $resultado->fetch_assoc()) {
        echo "<tr>
                <td> <a href='mostrar_horas.php?legajo=".$fila["legajo"]."'>  ".$fila["legajo"]."  </a>   </td>
                <td>".$fila["nombre"]."</td> 
                <td>".$fila["apellido"]."</td> 
            </tr>";
    }
    echo "</table>";
} else {
    echo "No se encontraron resultados.";
}
echo "</body></html>";

// Cerrar la conexión
$conexion->close();

include 'templates/footer.php'; ?>
