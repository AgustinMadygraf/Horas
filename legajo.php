<?php
require_once 'includes/db.php'; // Asegúrate de que la ruta al archivo db.php sea correcta

// Obtener el legajo desde el parámetro GET
$legajo = isset($_GET['legajo']) ? $_GET['legajo'] : '';

// Verificar si el legajo no está vacío
if (!empty($legajo)) {
    // Preparar la consulta SQL
    $sql = "SELECT * FROM informacion_asociados WHERE legajo = ?";
    
    // Preparar la sentencia
    if ($stmt = $conexion->prepare($sql)) {
        // Vincular parámetros
        $stmt->bind_param("s", $legajo);
        
        // Ejecutar la sentencia
        $stmt->execute();

        // Obtener los resultados
        $resultado = $stmt->get_result();

        // Verificar si hay resultados
        if ($resultado->num_rows > 0) {
            // Iniciar la tabla
            echo "<table border='1'>";
            echo "<tr><th>Legajo</th><th>Nombre</th><th>Apellido</th></tr>"; // Encabezados de la tabla
        
            // Mostrar los resultados en filas de la tabla
            while ($fila = $resultado->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($fila['legajo']) . "</td>";
                echo "<td>" . htmlspecialchars($fila['nombre']) . "</td>";
                echo "<td>" . htmlspecialchars($fila['apellido']) . "</td>";
                echo "</tr>";
            }
        
            // Cerrar la tabla
            echo "</table><br>";
        } else {
            echo "No se encontraron resultados para el legajo: $legajo";
        }
        // Cerrar la sentencia
        $stmt->close();
    } else {
        echo "Error al preparar la consulta: " . $conexion->error;
    }
} else {
    echo "Por favor, proporcione un legajo.<br>";
}


