<?php
// DatabaseManager.php

class DatabaseManager {
    private $conexion;

    public function __construct($servername, $username, $password, $dbname) {
        $this->conexion = new mysqli($servername, $username, $password, $dbname);

        if ($this->conexion->connect_error) {
            die("Conexión fallida: " . $this->conexion->connect_error);
        }
    }

    public function obtenerInformacionAsociados() {
        $sql = "SELECT * FROM informacion_asociados";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $stmt->close();

        return $resultado;
    }

    public function close() {
        $this->conexion->close();
    }
}
