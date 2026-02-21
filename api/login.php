<?php
include "config.php";

header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if(!isset($data['email'],$data['password'])){
    echo json_encode([
        "status"=>false,
        "message"=>"Missing login data"
    ]);
    exit;
}

$email = trim($data['email']);
$password = $data['password'];

// Fetch only required fields (SECURE)
$stmt = $conn->prepare("
    SELECT id, first_name, last_name, email, password, role 
    FROM users 
    WHERE email=?
");

$stmt->execute([$email]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if($user && password_verify($password,$user['password'])){

    // Remove password before sending response
    unset($user['password']);

    echo json_encode([
        "status"=>true,
        "user"=>$user
    ]);

}else{

    echo json_encode([
        "status"=>false,
        "message"=>"Invalid email or password"
    ]);
}
?>