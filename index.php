<?php
// backend/index.php

header('Content-Type: application/json');

echo json_encode([
    "status" => "online",
    "message" => "Nadiyas Clothing API is running running smoothly."
]);
?>