<?php
require_once "config.php";

$data = json_decode(file_get_contents("php://input"), true);

if (empty($data['address_id']) || empty($data['user_id'])) {
    echo json_encode(["status" => false, "message" => "Invalid request"]);
    exit;
}

try {

    // Make sure user owns this address
    $stmt = $conn->prepare("
        DELETE FROM user_addresses 
        WHERE id = :id AND user_id = :user_id
    ");

    $stmt->execute([
        ":id" => $data['address_id'],
        ":user_id" => $data['user_id']
    ]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(["status" => true]);
    } else {
        echo json_encode(["status" => false, "message" => "Address not found"]);
    }

} catch (PDOException $e) {
    echo json_encode(["status" => false, "message" => "Delete failed"]);
}