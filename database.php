<?php
require_once 'path/to/env.php'; // Asegúrate de incluir tu archivo de variables de entorno

class Database {
    private $host;
    private $db_name;
    private $username;
    private $password;
    public $conn;

    public function __construct() {
        $this->host = getenv('DB_HOST');
        $this->db_name = getenv('DB_NAME');
        $this->username = getenv('DB_USER');
        $this->password = getenv('DB_PASS');
    }

    public function getConnection() {
        $this->conn = null;

        try {
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8";
            $this->conn = new PDO($dsn, $this->username, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false
            ]);
        } catch(PDOException $exception) {


            // Registra el error en un archivo de log y muestra un mensaje genérico al usuario
            error_log($exception->getMessage()); // Asegúrate de que el archivo de log no sea accesible públicamente
            die("Ocurrió un error al conectar con la base de datos.");
        }

        return $this->conn;
    }
}

