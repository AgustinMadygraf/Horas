<?php
//includes/db.php
$servername = "localhost";
$username = "root";
$password = "12345678";
$dbname = "horas";
// Crear conexión
$conexion = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}
?>
