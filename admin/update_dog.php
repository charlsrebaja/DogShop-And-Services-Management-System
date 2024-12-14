<?php
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dogId = $_POST['id'];
    $name = $_POST['name'];
    $breed = $_POST['breed'];
    $adoption_fee = $_POST['adoption_fee'];
    $status = $_POST['status'];

    // Check if an image is uploaded
    $imageUrl = $_POST['current_image']; // Retain the current image by default
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        // Process the uploaded image
        $imageUrl = $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "../dogimg/$imageUrl");
    }

    // Update dog information in the database
    $stmt = $pdo->prepare("UPDATE dogs SET name = ?, breed = ?, adoption_fee = ?, image_url = ?, status = ? WHERE id = ?");
    $stmt->execute([$name, $breed, $adoption_fee, $imageUrl, $status, $dogId]);

    header('Location: dashboard.php#manage_dogs'); // Redirect back to the main page
}
?>
