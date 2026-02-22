<?php
require_once "config.php";

$data = json_decode(file_get_contents("php://input"), true);

if (
    empty($data['user_id']) ||
    empty($data['full_name']) ||
    empty($data['phone']) ||
    empty($data['address_line']) ||
    empty($data['city']) ||
    empty($data['state']) ||
    empty($data['pincode'])
) {
    echo json_encode([
        "status" => false,
        "message" => "All fields are required"
    ]);
    exit;
}

try {

$stmt = $conn->prepare("
    INSERT INTO user_addresses 
    (user_id, full_name, phone, alternate_phone, address_line, city, state, pincode, country, is_primary)
    VALUES 
    (:user_id, :full_name, :phone, :alternate_phone, :address_line, :city, :state, :pincode, :country, :is_primary)
");

$stmt->execute([
    ":user_id" => $data['user_id'],
    ":full_name" => $data['full_name'],
    ":phone" => $data['phone'],
    ":alternate_phone" => $data['alternate_phone'] ?? null,
    ":address_line" => $data['address_line'],
    ":city" => $data['city'],
    ":state" => $data['state'],
    ":pincode" => $data['pincode'],
    ":country" => "India",
    ":is_primary" => 0
]);

    echo json_encode([
        "status" => true,
        "message" => "Address added successfully"
    ]);

} catch (PDOException $e) {

    echo json_encode([
        "status" => false,
        "message" => "Failed to add address"
    ]);
}