<?php
session_start();
include "config.php";

header("Content-Type: application/json");

$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Admin check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo json_encode([
        "status" => false,
        "message" => "Unauthorized"
    ]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$id = $data['id'] ?? null;

if (!$id) {
    echo json_encode([
        "status" => false,
        "message" => "Invalid ID"
    ]);
    exit;
}

try {

    // Toggle logic
    $stmt = $conn->prepare("
        UPDATE hero_slides
        SET is_active = IF(is_active = 1, 0, 1)
        WHERE id = ?
    ");

    $stmt->execute([$id]);

    echo json_encode([
        "status" => true
    ]);

} catch (PDOException $e) {

    echo json_encode([
        "status" => false,
        "error" => $e->getMessage()
    ]);
}
?>