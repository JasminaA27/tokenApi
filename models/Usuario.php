<?php
require_once 'Database.php';

class Usuario {
    private $conn;
    private $table_name = "usuarios"; // Ajusta el nombre de la tabla según tu BD

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function login($username, $password) {
        $query = "SELECT id, nombre, username, password, estado FROM " . $this->table_name . " 
                  WHERE username = :username AND estado = 1 LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Verificar contraseña (asumiendo que está hasheada)
            if (password_verify($password, $user['password'])) {
                // Eliminar la contraseña del array antes de retornar
                unset($user['password']);
                return $user;
            }
        }
        
        return false;
    }

    // Método para crear usuario inicial si no existe
    public function createInitialUser() {
        // Verificar si ya existe el usuario admin
        $query = "SELECT id FROM " . $this->table_name . " WHERE username = 'admin'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        if ($stmt->rowCount() == 0) {
            // Crear usuario admin por defecto
            $query = "INSERT INTO " . $this->table_name . " 
                     (nombre, username, password, estado, created_at) 
                     VALUES (:nombre, :username, :password, 1, NOW())";
            
            $stmt = $this->conn->prepare($query);
            
            $nombre = "Administrador";
            $username = "admin";
            $password = password_hash("admin123", PASSWORD_DEFAULT);
            
            $stmt->bindParam(":nombre", $nombre);
            $stmt->bindParam(":username", $username);
            $stmt->bindParam(":password", $password);
            
            return $stmt->execute();
        }
        
        return true;
    }
}
?>