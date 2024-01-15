<?php
//procesar.php
require_once 'includes/db.php';

// Verificar si los parámetros GET están establecidos y son válidos
if (isset($_GET['legajo'], $_GET['centro_costo'], $_GET['fecha'])) {
    $legajo = $_GET['legajo'];
    $centro_costo = $_GET['centro_costo'];
    $fecha = $_GET['fecha'];

    // Validar los datos aquí
    if (!preg_match('/^\d{1,4}$/', $legajo)) {
        die("Legajo inválido.");
    }
    if (!preg_match('/^\d{1,3}$/', $centro_costo)) {
        die("Centro de costo inválido.");
    }
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
        die("Fecha inválida.");
    }

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


