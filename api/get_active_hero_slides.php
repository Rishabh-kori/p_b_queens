<?php
include "config.php";

header("Content-Type: application/json");

try {

    // Make sure PDO throws exceptions
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("
        SELECT 
            id,
            badge,
            title_line1,
            title_line2,
            description,
            image_url,
            starting_price,
            sort_order
        FROM hero_slides
        WHERE is_active = 1
        ORDER BY sort_order ASC, id DESC
    ");

    $stmt->execute();

    $slides = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "status" => true,
        "count" => count($slides),
        "slides" => $slides
    ]);

} catch (PDOException $e) {

    echo json_encode([
        "status" => false,
        "message" => $e->getMessage()
    ]);
}
?>