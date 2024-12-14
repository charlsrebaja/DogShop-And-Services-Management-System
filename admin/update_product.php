<?php
include '../config/db.php'; // Include database connection

// Check if form is submitted
if (isset($_POST['id'])) {
    // Get the posted data
    $product_id = $_POST['id'];
    $product_name = $_POST['product_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $category = $_POST['category'];

    // Handle image upload if a new image is provided
    if ($_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $image = $_FILES['image'];
        $image_name = basename($image['name']);
        $image_path = '../uploads/' . $image_name;
        move_uploaded_file($image['tmp_name'], $image_path);
    } else {
        // If no image was uploaded, keep the existing image
        $image_name = $_POST['existing_image'];
    }

    // Prepare and execute the update query
    $stmt = $pdo->prepare("UPDATE products SET product_name = :product_name, description = :description, price = :price, stock = :stock, category = :category, image_url = :image_url WHERE id = :id");
    $stmt->bindParam(':product_name', $product_name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':stock', $stock);
    $stmt->bindParam(':category', $category);
    $stmt->bindParam(':image_url', $image_name);
    $stmt->bindParam(':id', $product_id);

    if ($stmt->execute()) {
        // Redirect to the product management page or display success message
        header('Location: dashboard.php#manage_products.php');
        exit;
    } else {
        echo 'Error updating product';
    }
} else {
    echo 'Product ID is missing.';
}
?>
