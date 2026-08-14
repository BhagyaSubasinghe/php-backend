<?php

class Product
{
    private $conn;
    private $table = "products";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Get all products
    public function getAll()
    {
        $sql = "
            SELECT
                p.id,
                p.name,
                p.description,
                p.price,
                p.image,
                p.sizes,
                p.stock,
                c.name AS category
            FROM products p
            INNER JOIN categories c
                ON p.category_id = c.id
            ORDER BY p.id DESC
        ";

        $result = $this->conn->query($sql);

        $products = [];

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $products[] = $row;
            }
        }

        return $products;
    }

    // Get products by category
    public function getByCategory($category)
    {
        $sql = "
            SELECT
                p.id,
                p.name,
                p.description,
                p.price,
                p.image,
                p.sizes,
                p.stock,
                c.name AS category
            FROM products p
            INNER JOIN categories c
                ON p.category_id = c.id
            WHERE c.name = ?
            ORDER BY p.id DESC
        ";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return [];
        }

        $stmt->bind_param("s", $category);
        $stmt->execute();

        $result = $stmt->get_result();

        $products = [];

        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }

        $stmt->close();

        return $products;
    }

    // Get one product by ID
    public function getById($id)
    {
        $sql = "
            SELECT
                p.id,
                p.name,
                p.description,
                p.price,
                p.image,
                p.sizes,
                p.stock,
                c.name AS category
            FROM products p
            INNER JOIN categories c
                ON p.category_id = c.id
            WHERE p.id = ?
        ";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return null;
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            $stmt->close();
            return null;
        }

        $product = $result->fetch_assoc();

        $stmt->close();

        return $product;
    }
}