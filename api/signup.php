<?php
include "config.php";

header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if(!isset($data['first_name'],$data['last_name'],$data['email'],$data['password'])){
    echo json_encode([
        "status"=>false,
        "message"=>"Missing fields"
    ]);
    exit;
}

$first_name = trim($data['first_name']);
$last_name  = trim($data['last_name']);
$email      = trim($data['email']);
$password   = $data['password'];

if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
    echo json_encode([
        "status"=>false,
        "message"=>"Invalid email format"
    ]);
    exit;
}

// Check if email exists
$check = $conn->prepare("SELECT id FROM users WHERE email=?");
$check->execute([$email]);

if($check->rowCount()>0){
    echo json_encode([
        "status"=>false,
        "message"=>"Email already registered"
    ]);
    exit;
}

// Hash password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("
INSERT INTO users (first_name,last_name,email,password,role)
VALUES (?,?,?,?, 'user')
");

if($stmt->execute([$first_name,$last_name,$email,$hashedPassword])){

    echo json_encode([
        "status"=>true,
        "message"=>"Signup successful"
    ]);

}else{

    echo json_encode([
        "status"=>false,
        "message"=>"Signup failed"
    ]);
}
?>