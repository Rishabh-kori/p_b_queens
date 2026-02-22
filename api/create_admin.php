<?php
include "config.php";

$password = password_hash("Admin@123", PASSWORD_DEFAULT);

$stmt = $conn->prepare("
    INSERT INTO users 
    (first_name, last_name, email, password, role) 
    VALUES (?, ?, ?, ?, ?)
");

$stmt->execute([
    "Super",
    "Admin",
    "admin@pbqueens.com",
    $password,
    "admin"
]);

echo "Admin created successfully";
?>