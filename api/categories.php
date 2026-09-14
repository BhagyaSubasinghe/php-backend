<?php

header("Content-Type: application/json");

require_once "../config/database.php";


try {

    $stmt = $pdo->query("

        SELECT
            id,
            name

        FROM categories

        ORDER BY id

    ");


    $categories =
        $stmt->fetchAll();


    echo json_encode([

        "success" => true,

        "categories" => $categories

    ]);


} catch (PDOException $e) {

    http_response_code(500);

    echo json_encode([

        "success" => false,

        "message" =>
            "Unable to retrieve categories"

    ]);

}