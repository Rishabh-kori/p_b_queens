<?php
require_once "config.php";

$data = json_decode(file_get_contents("php://input"), true);

if (
    empty($data['address_id']) ||
    empty($data['full_name']) ||
    empty($data['phone']) ||
    empty($data['address_line']) ||
    empty($data['city']) ||
    empty($data['state']) ||
    empty($data['pincode'])
) {
    echo json_encode(["status" => false, "message" => "All fields required"]);
    exit;
}

try {

$stmt = $conn->prepare("
    UPDATE user_addresses SET
    full_name = :full_name,
    phone = :phone,
    alternate_phone = :alternate_phone,
    address_line = :address_line,
    city = :city,
    state = :state,
    pincode = :pincode
    WHERE id = :id
");

$stmt->execute([
    ":full_name" => $data['full_name'],
    ":phone" => $data['phone'],
    ":alternate_phone" => $data['alternate_phone'] ?? null,
    ":address_line" => $data['address_line'],
    ":city" => $data['city'],
    ":state" => $data['state'],
    ":pincode" => $data['pincode'],
    ":id" => $data['address_id']
]);

    echo json_encode(["status" => true]);

} catch (PDOException $e) {
    echo json_encode(["status" => false]);
}