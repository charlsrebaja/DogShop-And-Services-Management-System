<?php
session_start();
include '../config/db.php'; // Include database connection

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data
    $service_name = $_POST['service_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image = $_FILES['image'];

    // Handle image upload
    $target_dir = "../uploads/"; // Directory to store uploaded images
    $image_name = basename($image['name']);
    $target_file = $target_dir . $image_name;

    // Check if file is an image
    $check = getimagesize($image['tmp_name']);
    if ($check === false) {
        echo "<script>alert('File is not an image');</script>";
        exit;
    }

    // Move the uploaded image to the target directory
    if (move_uploaded_file($image['tmp_name'], $target_file)) {
        // Insert service into the database
        $sql = "INSERT INTO services (service_name, description, price, image_url) 
                VALUES (:service_name, :description, :price, :image_url)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':service_name' => $service_name,
            ':description' => $description,
            ':price' => $price,
            ':image_url' => $image_name // Store just the image file name
        ]);

        echo "<script>alert('Service added successfully'); window.location.href = 'dashboard.php#manage_services';</script>";
    } else {
        echo "<script>alert('Error uploading image');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Service</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/mainstyle.css">
</head>
<body>


<div class="container mt-5">
    <h2>Add New Service</h2>
    <form  method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="service_name" class="form-label">Service Name</label>
            <input type="text" class="form-control" id="service_name" name="service_name" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" class="form-control" id="price" name="price" required>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Service Image</label>
            <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Service</button>
    </form>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
