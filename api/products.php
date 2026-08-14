<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../controllers/ProductController.php";

$productController = new ProductController($conn);

/*
|--------------------------------------------------------------------------
| Product API
|--------------------------------------------------------------------------
|
| Get all products:
| products.php
|
| Get product by ID:
| products.php?id=1
|
| Get products by category:
| products.php?category=Men
|
*/

// Get product by ID
if (isset($_GET["id"])) {

    $id = $_GET["id"];

    $response = $productController->getProduct($id);

// Get products by category
} elseif (isset($_GET["category"])) {

    $category = trim($_GET["category"]);

    if ($category === "") {

        $response = [
            "success" => false,
            "message" => "Category cannot be empty"
        ];

    } else {

        $response = $productController->getProductsByCategory($category);
    }

// Get all products
} else {

    $response = $productController->getProducts();
}

echo json_encode($response, JSON_PRETTY_PRINT);




?>