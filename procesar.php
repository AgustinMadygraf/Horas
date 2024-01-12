<?php
//procesar.php
require_once 'includes/db.php';

// Verificar si los parámetros GET están establecidos
if (isset($_GET['legajo']) && isset($_GET['centro_costo']) && isset($_GET['fecha'])) {
    $legajo = $_GET['legajo'];
    $centro_costo = $_GET['centro_costo'];
    $fecha = $_GET['fecha'];

    // Validar los datos aquí (si es necesario)

    // Preparar la consulta SQL para actualizar
    $sql = "UPDATE registro_horas_trabajo SET centro_costo = ? WHERE legajo = ? AND fecha = ?";

    // Preparar la sentencia
    if ($stmt = $conexion->prepare($sql)) {
        // Vincular parámetros
        $stmt->bind_param("sss", $centro_costo, $legajo, $fecha);

        // Ejecutar la sentencia
        if ($stmt->execute()) {
            // Enviar un mensaje de confirmación a insertar_centro.php
            header("Location: insertar_centro.php?legajo=$legajo&actualizado=1");
            exit;
        } else {
            echo "Error al actualizar el centro de costo: " . $conexion->error;
        }

        // Cerrar la sentencia
        $stmt->close();
    } else {
        echo "Error al preparar la consulta: " . $conexion->error;
    }
} else {
    echo "Legajo, centro de costo o fecha no proporcionados.";
}

// Cerrar la conexión
$conexion->close();
?>
