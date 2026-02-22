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

// Get JSON input
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

    // Optional: delete image file
    $stmt = $conn->prepare("SELECT image_url FROM hero_slides WHERE id=?");
    $stmt->execute([$id]);
    $slide = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($slide && file_exists("../" . $slide['image_url'])) {
        unlink("../" . $slide['image_url']);
    }

    $stmt = $conn->prepare("DELETE FROM hero_slides WHERE id=?");
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