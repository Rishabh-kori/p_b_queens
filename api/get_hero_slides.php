<?php
session_start();
include "config.php";

header("Content-Type: application/json");

try {

    $isAdmin = isset($_SESSION['user_id']) && 
               isset($_SESSION['role']) && 
               $_SESSION['role'] === 'admin';

    if ($isAdmin) {

        // ADMIN: get all slides
        $stmt = $conn->prepare("
SELECT id, badge, title_line1, title_line2, description, 
       starting_price,
       image_url, sort_order, is_active, created_at
            FROM hero_slides
            ORDER BY sort_order ASC, id DESC
        ");

        $stmt->execute();

    } else {

        // PUBLIC: get only active slides
        $stmt = $conn->prepare("
            SELECT id, badge, title_line1, title_line2, description, 
                   image_url, sort_order
            FROM hero_slides
            WHERE is_active = 1
            ORDER BY sort_order ASC, id DESC
        ");

        $stmt->execute();
    }

    $slides = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "status" => true,
        "slides" => $slides
    ]);

} catch (PDOException $e) {

    echo json_encode([
        "status" => false,
        "message" => "Database error"
    ]);
}
?>