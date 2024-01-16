<?php 
//index.php

include 'templates/header.php'; 
require_once 'includes/db.php';
require_once 'DatabaseManager.php';

// Crear una instancia de DatabaseManager
$dbManager = new DatabaseManager($servername, $username, $password, $dbname);

// Preparar la consulta SQL
$sql = "SELECT * FROM informacion_asociados ";

// Preparar la sentencia
//$stmt = $dbManager->conexion->prepare($sql); //no anda
$stmt = $conexion->prepare($sql);

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
                <td><a href='mostrar_horas.php?legajo=" . $fila["legajo"] . "'>" . $fila["legajo"] . "</a></td>
                <td>" . $fila["nombre"] . "</td> 
                <td>" . $fila["apellido"] . "</td> 
            </tr>";
    }
    echo "</table>";
} else {
    echo "No se encontraron resultados.";
}


// Cerrar la conexión
$dbManager->close();

include 'templates/footer.php';
