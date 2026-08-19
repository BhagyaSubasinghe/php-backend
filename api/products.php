/* ============================= */
/* PRODUCT DETAILS PAGE */
/* ============================= */

.product-page {
    max-width: 1200px;
    margin: 50px auto;
    padding: 30px;
}

.product-container {
    display: flex;
    gap: 50px;
    align-items: flex-start;
    background: white;
    padding: 30px;
    border-radius: 10px;
}

.product-image-container {
    width: 50%;
}

.product-image-container img {
    width: 100%;
    max-width: 500px;
    height: 550px;
    object-fit: cover;
    border-radius: 10px;
    display: block;
}

.product-details {
    width: 50%;
    padding: 20px;
}

.product-details h1 {
    font-size: 40px;
    margin-bottom: 20px;
}

.product-category {
    font-size: 18px;
    margin-bottom: 15px;
}

.product-price {
    font-size: 30px;
    font-weight: bold;
    margin-bottom: 20px;
}

.product-description {
    font-size: 18px;
    margin-bottom: 20px;
}

.product-stock {
    font-size: 18px;
    margin: 20px 0;
}

.size-label {
    font-size: 18px;
    font-weight: bold;
}

#sizeSelect {
    padding: 10px;
    font-size: 16px;
    margin: 10px 0 25px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.product-buttons {
    display: flex;
    gap: 15px;
}

.product-buttons button {
    padding: 12px 25px;
    border: none;
    border-radius: 5px;
    background-color: #28a745;
    color: white;
    font-size: 17px;
    cursor: pointer;
    margin: 0;
}

.product-buttons button:hover {
    background-color: #218838;
}

.buy-now-btn {
    background-color: #333 !important;
}

.buy-now-btn:hover {
    background-color: #111 !important;
}


/* ============================= */
/* CART PAGE */
/* ============================= */

.cart-page {
    max-width: 1100px;
    margin: 40px auto;
    padding: 20px;
}

.cart-page h1 {
    text-align: center;
    margin-bottom: 30px;
}

.cart-item {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 20px;
    margin-bottom: 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background: white;
}

.cart-item img {
    width: 120px;
    height: 150px;
    object-fit: cover;
    border-radius: 5px;
}

.cart-item-details {
    flex: 1;
}

.cart-item-details h3 {
    margin-bottom: 10px;
}

.cart-item-price {
    font-size: 18px;
    font-weight: bold;
}

.cart-controls {
    display: flex;
    align-items: center;
    gap: 10px;
}

.cart-controls button {
    padding: 5px 12px;
    margin: 0;
}

.cart-summary {
    margin-top: 30px;
    padding: 25px;
    border-radius: 8px;
    background: #f4f4f4;
    text-align: right;
}

.cart-summary h2 {
    margin-bottom: 15px;
}

.checkout-btn {
    background-color: #28a745;
    color: white;
    border: none;
    padding: 12px 25px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 17px;
}

.checkout-btn:hover {
    background-color: #218838;
}


/* ============================= */
/* MOBILE */
/* ============================= */

@media (max-width: 768px) {

    .product-container {
        flex-direction: column;
    }

    .product-image-container,
    .product-details {
        width: 100%;
    }

    .product-image-container img {
        height: 400px;
    }

    .product-details h1 {
        font-size: 30px;
    }

    .cart-item {
        flex-direction: column;
        text-align: center;
    }

    .product-buttons {
        flex-direction: column;
    }
}<?php

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