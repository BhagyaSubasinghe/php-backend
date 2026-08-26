<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . '/../config/database.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!empty($data['name']) && !empty($data['email']) && !empty($data['message'])) {
    try {
        $stmt = $pdo->prepare("INSERT INTO messages (name, email, subject, message) VALUES (:name, :email, :subject, :message)");
        $stmt->execute([
            'name' => $data['name'],
            'email' => $data['email'],
            'subject' => $data['subject'] ?? '',
            'message' => $data['message']
        ]);

        echo json_encode(["success" => true, "message" => "Thank you! Your message has been sent successfully."]);
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Failed to save message: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Please fill in all required fields."]);
}
?>