<?php

header("Content-Type: application/json");

$host = "localhost";
$dbname = "pb_queens";
$username = "root";
$password = "";

try{
    $conn = new PDO("mysql:host=$host;dbname=$dbname",$username,$password);
    $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
    echo json_encode([
        "status"=>false,
        "message"=>"Database connection failed"
    ]);
    exit;
}

?>