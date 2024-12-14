<?php
include '../config/db.php'; // Include database connection

// Check if an ID is provided in the URL
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Prepare and execute the query to fetch product details
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
    $stmt->bindParam(':id', $product_id);
    $stmt->execute();

    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        // Redirect or handle error if product not found
        die('Product not found.');
    }
} else {
    // Redirect or handle error if ID is not provided
    die('Product ID is missing.');
}
 // Redirect back to the manage product page
 header("Location: dashboard.php#manage-products");
 exit;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h2>Edit Product</h2>
    <form action="update_product.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $product['id']; ?>">

        <div class="mb-3">
            <label for="product_name" class="form-label">Product Name</label>
            <input type="text" class="form-control" id="product_name" name="product_name" value="<?php echo htmlspecialchars($product['product_name']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3" required><?php echo htmlspecialchars($product['description']); ?></textarea>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" class="form-control" id="price" name="price" value="<?php echo $product['price']; ?>" step="0.01" required>
        </div>

        <div class="mb-3">
            <label for="stock" class="form-label">Stock</label>
            <input type="number" class="form-control" id="stock" name="stock" value="<?php echo $product['stock']; ?>" required>
        </div>

        <div class="mb-3">
            <label for="category" class="form-label">Category</label>
            <select class="form-control" id="category" name="category" required>
                <option value="Health" <?php echo $product['category'] == 'Health' ? 'selected' : ''; ?>>Health</option>
                <option value="Accessories" <?php echo $product['category'] == 'Accessories' ? 'selected' : ''; ?>>Accessories</option>
                <option value="Food" <?php echo $product['category'] == 'Food' ? 'selected' : ''; ?>>Food</option>
                <option value="Prevention" <?php echo $product['category'] == 'Prevention' ? 'selected' : ''; ?>>Prevention</option>
                <option value="Popular" <?php echo $product['category'] == 'Popular' ? 'selected' : ''; ?>>Popular</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Product Image</label>
            <input type="file" class="form-control" id="image" name="image" accept="image/*">
            <?php if ($product['image_url']): ?>
                <img src="../uploads/<?php echo htmlspecialchars($product['image_url']); ?>" alt="Product Image" style="max-width: 100px; margin-top: 10px;">
            <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-dark">Update Product</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
