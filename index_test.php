<?
// index.php

require_once 'DatabaseManager.php';
require_once 'includes/config.php'; // Asegúrate de que este archivo contenga las credenciales de la base de datos
include 'templates/header.php';

// Crear una instancia de DatabaseManager
$dbManager = new DatabaseManager($servername, $username, $password, $dbname);

// Obtener la información de asociados utilizando la función de la clase
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
