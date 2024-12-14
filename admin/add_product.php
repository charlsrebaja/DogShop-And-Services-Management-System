<?php
include '../config/db.php'; // Include database connection

$product = null; // Initialize product data

// Check if editing an existing product
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch product data
}

// Handle form submission
if (isset($_POST['submit'])) {
    // Collect form data
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $stock = $_POST['stock'];
    $category = $_POST['category'];
    $id = $_POST['id'] ?? null;

    // Handle file upload
    $target_dir = "../uploads/";
    $image_name = $product['image_url'] ?? null; // Default to existing image
    if (!empty($_FILES["image"]["name"])) {
        $image_name = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_name;
        $uploadOk = 1;

        // Validate image file
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        if (isset($_FILES["image"])) {
            $check = getimagesize($_FILES["image"]["tmp_name"]);
            if ($check === false) {
                echo "File is not an image.";
                $uploadOk = 0;
            }
        }

        // Check file size
        if ($_FILES["image"]["size"] > 2000000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow only specific file types
        if (!in_array($imageFileType, ["jpg", "png", "jpeg", "gif"])) {
            echo "Sorry, only JPG, JPEG, PNG, and GIF files are allowed.";
            $uploadOk = 0;
        }

        // Upload file
        if ($uploadOk && move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            echo "Image uploaded successfully.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }

    // Insert or update product in the database
    if ($id) {
        $stmt = $pdo->prepare("UPDATE products SET product_name = :product_name, price = :price, description = :description, stock = :stock, category = :category, image_url = :image_url WHERE id = :id");
        $stmt->execute([
            ':product_name' => $product_name,
            ':price' => $price,
            ':description' => $description,
            ':stock' => $stock,
            ':category' => $category,
            ':image_url' => $image_name,
            ':id' => $id
        ]);
        echo "Product updated successfully!";
    } else {
        $stmt = $pdo->prepare("INSERT INTO products (product_name, price, description, stock, category, image_url) 
            VALUES (:product_name, :price, :description, :stock, :category, :image_url)");
        $stmt->execute([
            ':product_name' => $product_name,
            ':price' => $price,
            ':description' => $description,
            ':stock' => $stock,
            ':category' => $category,
            ':image_url' => $image_name
        ]);
        echo "Product added successfully!";
    }

    header("Location: dashboard.php#manage_products.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>

<div class="container">
    <h2>Add Product</h2>
    <form action="add_product.php" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="product_name" class="form-label">Product Name</label>
            <input type="text" class="form-control" id="product_name" name="product_name" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" class="form-control" id="price" name="price" step="0.01" required>
        </div>
        <div class="mb-3">
            <label for="stock" class="form-label">Stock</label>
            <input type="number" class="form-control" id="stock" name="stock" required>
        </div>
        <div class="mb-3">
            <label for="category" class="form-label">Category</label>
            <select class="form-control" id="category" name="category" required>
                <option value="Health">Health</option>
                <option value="Accessories">Accessories</option>
                <option value="Food">Food</option>
                <option value="Prevention">Prevention</option>
                <option value="Popular">Popular</option>
            </select>
        </div>
        <!-- Image Upload Field -->
        <div class="mb-3">
            <label for="image" class="form-label">Product Image</label>
            <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
        </div>
        <button type="submit" name="submit" class="btn btn-primary w-100">Add Product</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

