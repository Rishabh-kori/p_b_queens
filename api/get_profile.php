<?php

include "config.php";   // DB connection

header("Content-Type: application/json");

// Read JSON input
$data = json_decode(file_get_contents("php://input"), true);

// Validate user id
if (!isset($data['id'])) {

    echo json_encode([
        "status" => false,
        "message" => "User ID missing"
    ]);
    exit;
}

$id = intval($data['id']);

// Fetch FULL profile including phone & dob
$stmt = $conn->prepare("
    SELECT id, first_name, last_name, email, phone, dob, role
    FROM users 
    WHERE id=?
");

$stmt->execute([$id]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {

    echo json_encode([
        "status" => true,
        "user" => $user
    ]);

} else {

    echo json_encode([
        "status" => false,
        "message" => "User not found"
    ]);
}
?>