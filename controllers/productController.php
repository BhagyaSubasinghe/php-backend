<?php

require_once __DIR__ . "/../models/Product.php";

class ProductController
{
    private $product;

    public function __construct($db)
    {
        $this->product = new Product($db);
    }

    // Get all products
    public function getProducts()
    {
        $products = $this->product->getAll();

        return [
            "success" => true,
            "count" => count($products),
            "data" => $products
        ];
    }

    // Get product by ID
    public function getProduct($id)
    {
        if (!is_numeric($id)) {
            return [
                "success" => false,
                "message" => "Invalid product ID"
            ];
        }

        $product = $this->product->getById((int)$id);

        if (!$product) {
            return [
                "success" => false,
                "message" => "Product not found"
            ];
        }

        return [
            "success" => true,
            "data" => $product
        ];
    }
}