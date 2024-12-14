<?php
include '../config/db.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $position = $_POST['position'];

    // Check if a new image was uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageTmpName = $_FILES['image']['tmp_name'];
        $imageName = basename($_FILES['image']['name']);
        $uploadDir = '../uploads/';
        $uploadPath = $uploadDir . $imageName;

        // Move the uploaded file to the uploads directory
        if (move_uploaded_file($imageTmpName, $uploadPath)) {
            // Update staff details including the new image
            $query = "UPDATE staff SET name = :name, position = :position, image_url = :image_url WHERE id = :id";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                ':name' => $name,
                ':position' => $position,
                ':image_url' => $imageName,
                ':id' => $id
            ]);
        } else {
            echo "Failed to upload the image.";
            exit;
        }
    } else {
        // Update staff details without changing the image
        $query = "UPDATE staff SET name = :name, position = :position WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':name' => $name,
            ':position' => $position,
            ':id' => $id
        ]);
    }

    // Redirect back to the manage staff page
    header('Location: dashboard.php#manage_staff');
    exit;
} else {
    // If not a POST request, redirect to manage staff
    header('Location: dashboard.php#manage_staff');
    exit;
}
