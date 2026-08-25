<?php
// backend/models/Product.php

class Product {
    private $conn;
    private $table_name = "products";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getProductsByCategory($category = 'All') {
        if ($category === 'All' || empty($category)) {
            $query = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC";
            $stmt = $this->conn->prepare($query);
        } else {
            $query = "SELECT * FROM " . $this->table_name . " WHERE LOWER(category) = LOWER(:category) ORDER BY id DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':category', $category);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>