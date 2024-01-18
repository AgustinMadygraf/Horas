<?php 
//index.php

include 'templates/header.php'; 
require_once 'includes/db.php';
require_once 'includes/DatabaseManager.php';

// Crear una instancia de DatabaseManager
$dbManager = new DatabaseManager($servername, $username, $password, $dbname);

// Utilizar el método de DatabaseManager para obtener la información
$resultado = $dbManager->obtenerInformacionAsociados();

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
?>
