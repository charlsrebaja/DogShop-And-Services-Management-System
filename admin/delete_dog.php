<?php
include '../config/db.php';

if (isset($_GET['id'])) {
    $dog_id = $_GET['id'];

    // Fetch the dog's image for deletion
    $stmt = $pdo->prepare("SELECT image_url FROM dogs WHERE id = ?");
    $stmt->execute([$dog_id]);
    $dog = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($dog) {
        // Delete the image file from the server
        $image_path = "public/dogs/" . $dog['image_url'];
        if (file_exists($image_path)) {
            unlink($image_path);
        }

        // Delete the dog from the database
        $delete_stmt = $pdo->prepare("DELETE FROM dogs WHERE id = ?");
        $delete_stmt->execute([$dog_id]);
    }
}

header("Location: dashboard.php");
exit;
?>
