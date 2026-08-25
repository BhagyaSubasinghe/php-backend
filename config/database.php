<?php
// backend/config/db.php

class Database {
    private $host = "localhost";
    private $port = "5432"; // PostgreSQL Default Port
    private $db_name = "nadiyas_db";
    private $username = "postgres"; // Default PostgreSQL user
    private $password = "your_password"; // PostgreSQL මුරපදය මෙතැනට දෙන්න
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            // PostgreSQL සඳහා DSN (Data Source Name)
            $dsn = "pgsql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name;
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo json_encode(["status" => "error", "message" => "Connection error: " . $exception->getMessage()]);
            exit();
        }

        return $this->conn;
    }
}
?>