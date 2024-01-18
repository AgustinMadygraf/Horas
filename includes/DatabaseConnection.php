<?php
// DatabaseConnection.php

class DatabaseConnection {
    private static $instance = null;
    private $conexion;

    private function __construct() {
        // Aquí deberías incluir tu lógica actual de conexión a la base de datos
        $this->conexion = new mysqli('tu_host', 'tu_usuario', 'tu_contraseña', 'tu_base_de_datos');
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
