<?php
class Database {
    private $host = "localhost";
    private $port = "5432";
    private $db_name = "nadiyas_db";
    private $username = "postgres";
    private $password = "Nadeesha@98";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $dsn = "pgsql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name;
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
            exit();
        }
        return $this->conn;
    }
}
?>