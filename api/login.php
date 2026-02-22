<?php
$lifetime = 60 * 60 * 24 * 2; // 2 days in seconds

session_set_cookie_params([
    'lifetime' => $lifetime,
    'path' => '/',
    'httponly' => true,
    'secure' => false, // true if using HTTPS
    'samesite' => 'Lax'
]);

session_start();
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

    // Save user in session
$_SESSION['user_id'] = $user['id'];
$_SESSION['user_name'] = $user['first_name'];
$_SESSION['user_email'] = $user['email'];
$_SESSION['role'] = $user['role'];  

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