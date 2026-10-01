<?php

header("Content-Type: application/json");

require_once "../config/database.php";


if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    http_response_code(405);

    echo json_encode([

        "success" => false,

        "message" =>
            "Only POST requests are allowed."

    ]);

    exit;

}


$data =
    json_decode(
        file_get_contents("php://input"),
        true
    );


if (!$data) {

    http_response_code(400);

    echo json_encode([

        "success" => false,

        "message" =>
            "Invalid order data."

    ]);

    exit;

}


try {

    $pdo->beginTransaction();


    $orderNumber =
        "NAD-" .
        date("YmdHis") .
        rand(100, 999);


    $customerName =
        trim($data["customer"]["name"] ?? "");


    $customerEmail =
        trim($data["customer"]["email"] ?? "");


    $customerPhone =
        trim($data["customer"]["phone"] ?? "");


    $customerAddress =
        trim($data["customer"]["address"] ?? "");


    $customerCity =
        trim($data["customer"]["city"] ?? "");


    $paymentMethod =
        $data["payment"] ??
        "Cash on Delivery";


    $items =
        $data["items"] ?? [];


    if (
        empty($customerName) ||
        empty($customerEmail) ||
        empty($customerPhone) ||
        empty($customerAddress) ||
        empty($customerCity) ||
        empty($items)
    ) {

        throw new Exception(
            "Required order information is missing."
        );

    }


    $subtotal = 0;


    foreach ($items as $item) {

        if (
            !isset(
                $item["productId"],
                $item["name"],
                $item["size"],
                $item["price"],
                $item["quantity"]
            ) ||
            !is_numeric($item["productId"]) ||
            !is_numeric($item["price"]) ||
            !is_numeric($item["quantity"]) ||
            (int) $item["quantity"] < 1 ||
            (float) $item["price"] < 0 ||
            trim($item["size"]) === ""
        ) {
            throw new Exception("Invalid order item.");
        }

        $subtotal +=
            (float) $item["price"] *
            (int) $item["quantity"];

    }


    $deliveryFee =
        $subtotal >= 10000
        ? 0
        : 350;


    $total =
        $subtotal +
        $deliveryFee;


    $orderSql = "

        INSERT INTO orders (

            order_number,
            customer_name,
            customer_email,
            customer_phone,
            customer_address,
            customer_city,
            payment_method,
            subtotal,
            delivery_fee,
            total

        )

        VALUES (

            :order_number,
            :customer_name,
            :customer_email,
            :customer_phone,
            :customer_address,
            :customer_city,
            :payment_method,
            :subtotal,
            :delivery_fee,
            :total

        )

        RETURNING id

    ";


    $orderStmt =
        $pdo->prepare($orderSql);


    $orderStmt->execute([

        "order_number" =>
            $orderNumber,

        "customer_name" =>
            $customerName,

        "customer_email" =>
            $customerEmail,

        "customer_phone" =>
            $customerPhone,

        "customer_address" =>
            $customerAddress,

        "customer_city" =>
            $customerCity,

        "payment_method" =>
            $paymentMethod,

        "subtotal" =>
            $subtotal,

        "delivery_fee" =>
            $deliveryFee,

        "total" =>
            $total

    ]);


    $orderId =
        $orderStmt->fetchColumn();


    $itemSql = "

        INSERT INTO order_items (

            order_id,
            product_id,
            product_name,
            size,
            quantity,
            price,
            total

        )

        VALUES (

            :order_id,
            :product_id,
            :product_name,
            :size,
            :quantity,
            :price,
            :total

        )

    ";


    $itemStmt =
        $pdo->prepare($itemSql);


    foreach ($items as $item) {

        $itemTotal =
            $item["price"] *
            $item["quantity"];


        $itemStmt->execute([

            "order_id" =>
                $orderId,

            "product_id" =>
                $item["productId"],

            "product_name" =>
                $item["name"],

            "size" =>
                $item["size"],

            "quantity" =>
                $item["quantity"],

            "price" =>
                $item["price"],

            "total" =>
                $itemTotal

        ]);

    }


    $pdo->commit();


    echo json_encode([

        "success" => true,

        "message" =>
            "Order placed successfully.",

        "order_number" =>
            $orderNumber

    ]);


} catch (Exception $e) {

    if ($pdo->inTransaction()) {

        $pdo->rollBack();

    }


    http_response_code(500);


    echo json_encode([

        "success" => false,

        "message" =>
            $e->getMessage()

    ]);

}