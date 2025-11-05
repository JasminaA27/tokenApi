<?php
require_once __DIR__ . '/../config/config.php';

class Database {
    private $host = BD_HOST;
    private $db_name = BD_NAME;
    private $username = BD_USER;
    private $password = BD_PASSWORD;
    private $charset = BD_CHARSET;
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=" . $this->charset,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Error de conexión: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>