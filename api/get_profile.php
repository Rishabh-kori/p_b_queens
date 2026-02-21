<?php

include "config.php";   // (use same DB file as login/signup)

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

// Fetch user profile (ONLY required fields)
$stmt = $conn->prepare("
    SELECT id, first_name, last_name, email, role 
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