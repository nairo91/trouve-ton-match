<?php
class Database {
    private static $instance = null;
    private $conn;
    
    private function __construct() {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "gestion_football";
        
        $this->conn = new mysqli($servername, $username, $password, $dbname);
        
        if ($this->conn->connect_error) {
            die("Connexion échouée : " . $this->conn->connect_error);
        }
        
        $this->conn->set_charset("utf8mb4");
    }
    
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->conn;
    }
}
?>
