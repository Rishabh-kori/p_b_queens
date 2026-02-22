<?php

include "config.php";

header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if(!isset($data['id'], $data['first_name'], $data['last_name'], $data['email'])){
    echo json_encode([
        "status"=>false,
        "message"=>"Missing required fields"
    ]);
    exit;
}

$id = $data['id'];
$first_name = trim($data['first_name']);
$last_name = trim($data['last_name']);
$email = trim($data['email']);
$phone = isset($data['phone']) ? trim($data['phone']) : null;
$dob = isset($data['dob']) ? $data['dob'] : null;

// Convert DD/MM/YYYY → YYYY-MM-DD for MySQL
if($dob){
    $dateParts = explode("/", $dob);
    if(count($dateParts) == 3){
        $dob = $dateParts[2]."-".$dateParts[1]."-".$dateParts[0];
    }
}


// ===== EMAIL VALIDATION =====
if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
    echo json_encode([
        "status"=>false,
        "message"=>"Invalid email format"
    ]);
    exit;
}


// ===== CHECK EMAIL DUPLICATE (except current user) =====
$check = $conn->prepare("SELECT id FROM users WHERE email=? AND id!=?");
$check->execute([$email,$id]);

if($check->rowCount() > 0){
    echo json_encode([
        "status"=>false,
        "message"=>"Email already used by another account"
    ]);
    exit;
}


// ===== UPDATE USER =====
$stmt = $conn->prepare("
UPDATE users 
SET first_name=?, last_name=?, email=?, phone=?, dob=?
WHERE id=?
");

if($stmt->execute([$first_name,$last_name,$email,$phone,$dob,$id])){

    echo json_encode([
        "status"=>true,
        "message"=>"Profile updated successfully"
    ]);

}else{

    echo json_encode([
        "status"=>false,
        "message"=>"Update failed"
    ]);

}

?>