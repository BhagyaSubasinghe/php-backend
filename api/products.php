<?php

header("Content-Type: application/json");

require_once "../config/database.php";


try {

    $category = $_GET["category"] ?? null;


    if ($category) {

        $sql = "

            SELECT
                p.id,
                p.name,
                c.name AS category,
                p.description,
                p.price,
                p.image,
                p.stock

            FROM products p

            INNER JOIN categories c
                ON p.category_id = c.id

            WHERE LOWER(c.name) = LOWER(:category)

            ORDER BY p.id DESC

        ";


        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            "category" => $category
        ]);

    } else {

        $sql = "

            SELECT
                p.id,
                p.name,
                c.name AS category,
                p.description,
                p.price,
                p.image,
                p.stock

            FROM products p

            INNER JOIN categories c
                ON p.category_id = c.id

            ORDER BY p.id DESC

        ";


        $stmt = $pdo->query($sql);

    }


    $products = $stmt->fetchAll();


    foreach ($products as &$product) {

        $sizeStmt = $pdo->prepare("

            SELECT
                size

            FROM product_sizes

            WHERE product_id = :product_id

            AND stock > 0

            ORDER BY id

        ");


        $sizeStmt->execute([
            "product_id" => $product["id"]
        ]);


        $sizes = $sizeStmt->fetchAll(
            PDO::FETCH_COLUMN
        );


        $product["sizes"] = $sizes;

        $product["price"] =
            (float) $product["price"];

        if ($product["image"] === "images/men/m3.jpg") {
            $product["image"] = "images/men/mens3.jpg";
        }

    }


    echo json_encode([
        "success" => true,
        "products" => $products
    ]);


} catch (PDOException $e) {

    http_response_code(500);

    echo json_encode([

        "success" => false,

        "message" =>
            "Unable to retrieve products"

    ]);

}