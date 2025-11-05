<?php
require_once 'Database.php';

class TokenModel {
    private $conn;
    private $table_name = "token_api";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAllTokens() {
        $query = "SELECT token FROM " . $this->table_name . " ORDER BY token";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateToken($old_token, $new_token) {
        $query = "UPDATE " . $this->table_name . " SET token = :new_token WHERE token = :old_token";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":new_token", $new_token);
        $stmt->bindParam(":old_token", $old_token);
        
        return $stmt->execute();
    }

    public function tokenExists($token) {
        $query = "SELECT token FROM " . $this->table_name . " WHERE token = :token";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":token", $token);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
}
?>