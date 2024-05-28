<?php
class UserModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAdmin($username) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addAdmin($username, $password, $adminKey) {
        $stmt = $this->db->prepare("INSERT INTO users (username, password, admin_key) VALUES (:username, :password, :admin_key)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', password_hash($password, PASSWORD_BCRYPT));
        $stmt->bindParam(':admin_key', $adminKey);
        $stmt->execute();
    }

    public function checkAdminKey($adminKey) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE BINARY admin_key = BINARY :admin_key");
        $stmt->bindParam(':admin_key', $adminKey);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
