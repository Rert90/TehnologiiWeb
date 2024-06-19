<?php
class Database {
    private $dsn = 'mysql:host=localhost;dbname=visb_db';
    private $username = 'root';
    private $password = ''; 
    public $conn;
    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO($this->dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
        return $this->conn;
    }
}
?>