<?php
session_start();
header("Content-Type: application/json");

// Prevent caching (important for back button security)
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

if (isset($_SESSION['user_id'])) {

    echo json_encode([
        "logged_in" => true,
        "user_id"   => $_SESSION['user_id'],
        "user_name" => $_SESSION['user_name'] ?? "",
        "email"     => $_SESSION['user_email'] ?? "",
        "role"      => $_SESSION['role'] ?? "user"   // ⭐ important for admin check
    ]);

} else {

    echo json_encode([
        "logged_in" => false
    ]);

}
?>