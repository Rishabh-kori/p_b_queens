<?php
require_once "config.php";

$data = json_decode(file_get_contents("php://input"), true);

if (empty($data['user_id'])) {
    echo json_encode(["status" => false]);
    exit;
}

$stmt = $conn->prepare("SELECT * FROM user_addresses WHERE user_id = :user_id ORDER BY created_at DESC");
$stmt->execute([":user_id" => $data['user_id']]);
$addresses = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    "status" => true,
    "addresses" => $addresses
]);