<?php
// backend/api/products.php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . '/../controllers/ProductController.php';

$category = isset($_GET['category']) ? $_GET['category'] : 'All';

$controller = new ProductController();
$response = $controller->fetchProducts($category);

echo json_encode($response);
?>