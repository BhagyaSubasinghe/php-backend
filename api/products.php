<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../controllers/ProductController.php";

$productController = new ProductController($conn);

// Check if an ID was requested
if (isset($_GET["id"])) {

    $id = $_GET["id"];

    $response = $productController->getProduct($id);

} else {

    $response = $productController->getProducts();

}

// Return JSON
echo json_encode($response, JSON_PRETTY_PRINT);

?>