<?php
// DatabaseConnection.php

class DatabaseConnection {
    private static $instance = null;
    private $conexion;

    private function __construct() {
        // Aquí deberías incluir tu lógica actual de conexión a la base de datos
        $this->conexion = new mysqli('localhost', 'root', '12345678', 'horas');
        if ($this->conexion->connect_error) {
            throw new Exception("Error al conectar a la base de datos: " . $this->conexion->connect_error);
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new DatabaseConnection();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->conexion;
    }

    private function __clone() {}
    private function __wakeup() {}
}
?>
