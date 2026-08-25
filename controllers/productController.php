<?php
// backend/controllers/ProductController.php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/Product.php';

class ProductController {
    public function fetchProducts($category) {
        $database = new Database();
        $db = $database->getConnection();

        $productModel = new Product($db);
        $products = $productModel->getProductsByCategory($category);

        return [
            "success" => true,
            "category" => $category,
            "data" => $products
        ];
    }
}
?>