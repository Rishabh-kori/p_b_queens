<?php
session_start();
include "config.php";

header("Content-Type: application/json");

$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// ✅ Admin Security Check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo json_encode([
        "status" => false,
        "message" => "Unauthorized access"
    ]);
    exit;
}

// Get form values
$id = $_POST['id'] ?? null;
$badge = $_POST['badge'] ?? '';
$title1 = $_POST['title_line1'] ?? '';
$title2 = $_POST['title_line2'] ?? '';
$description = $_POST['description'] ?? '';
$sort_order = $_POST['sort_order'] ?? 0;
$starting_price = $_POST['starting_price'] ?? null;

if (empty($badge) || empty($title1) || empty($title2)) {
    echo json_encode([
        "status" => false,
        "message" => "Required fields missing"
    ]);
    exit;
}

$imagePath = null;

// ✅ IMAGE UPLOAD
if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {

    $uploadDir = "../images/hero/";

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileName = time() . "_" . basename($_FILES["image"]["name"]);
    $targetFile = $uploadDir . $fileName;

    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'webp'];

    if (!in_array($imageFileType, $allowed)) {
        echo json_encode([
            "status" => false,
            "message" => "Invalid image format"
        ]);
        exit;
    }

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
        $imagePath = "images/hero/" . $fileName;
    } else {
        echo json_encode([
            "status" => false,
            "message" => "Image upload failed"
        ]);
        exit;
    }
}

try {

    // ================= INSERT =================
    if (empty($id)) {

        if (!$imagePath) {
            echo json_encode([
                "status" => false,
                "message" => "Image required"
            ]);
            exit;
        }

        $stmt = $conn->prepare("
            INSERT INTO hero_slides 
            (badge, title_line1, title_line2, description, starting_price, image_url, sort_order)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
        $badge,
        $title1,
        $title2,
        $description,
        $starting_price,
        $imagePath,
        $sort_order
    ]);

        $newId = $conn->lastInsertId();

        if ($newId > 0) {
            echo json_encode([
                "status" => true,
                "last_id" => $newId
            ]);
        } else {
            echo json_encode([
                "status" => false,
                "message" => "Insert failed"
            ]);
        }

        exit;
    }

    // ================= UPDATE =================
    else {

        if ($imagePath) {
            $stmt = $conn->prepare("
                UPDATE hero_slides 
                SET badge=?, title_line1=?, title_line2=?, description=?, starting_price=?, image_url=?, sort_order=?
                WHERE id=?
            ");

            $stmt->execute([
            $badge,
            $title1,
            $title2,
            $description,
            $starting_price,
            $imagePath,
            $sort_order,
            $id
        ]);
        } else {
            $stmt = $conn->prepare("
                UPDATE hero_slides 
                SET badge=?, title_line1=?, title_line2=?, description=?, starting_price=?, sort_order=?
                WHERE id=?
            ");

            $stmt->execute([
                $badge,
                $title1,
                $title2,
                $description,
                $starting_price,
                $sort_order,
                $id
            ]);
        }

        echo json_encode([
            "status" => true
        ]);
        exit;
    }

} catch (PDOException $e) {

    echo json_encode([
        "status" => false,
        "error" => $e->getMessage()
    ]);
    exit;
}
?>