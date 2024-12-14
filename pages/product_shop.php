<?php
session_start();
include '../config/db.php'; // Include database connection

try {
    $sql = "SELECT * FROM products"; // Fetch all products
    $stmt = $pdo->query($sql);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching products: " . $e->getMessage();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data
    $product_name = $_POST['product_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $category = $_POST['category'];
    $image = $_FILES['image'];

    // Handle image upload
    $target_dir = "uploads/";
    $image_name = basename($image['name']);
    $target_file = $target_dir . $image_name;

    if (move_uploaded_file($image['tmp_name'], $target_file)) {
        // Insert product into database
        $sql = "INSERT INTO products (product_name, price, description, stock, category, image_url) 
                VALUES (:product_name, :price, :description, :stock, :category, :image_url)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':product_name' => $product_name,
            ':price' => $price,
            ':description' => $description,
            ':stock' => $stock,
            ':category' => $category,
            ':image_url' => $image_name // Store just the image file name
        ]);

        echo "<script>alert('Product added successfully');</script>";
    } else {
        echo "<script>alert('Error uploading image');</script>";
    }
}

// Fetch health-related products (assuming a 'category' field in your table)
$food_products_stmt = $pdo->query("SELECT * FROM products WHERE category = 'Food' ORDER BY stock DESC LIMIT 10");
$food_products = $food_products_stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch health-related products (assuming a 'category' field in your table)
$health_products_stmt = $pdo->query("SELECT * FROM products WHERE category = 'Health' ORDER BY stock DESC LIMIT 10");
$health_products = $health_products_stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch dog accessories products (assuming a 'category' field in your table)
$accessories_products_stmt = $pdo->query("SELECT * FROM products WHERE category = 'Accessories' ORDER BY stock DESC LIMIT 20");
$accessories_products = $accessories_products_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to the Dog Shop</title>

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Bundle JS (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
        <!-- For High-Resolution Displays -->
    <link rel="apple-touch-icon" sizes="180x180" href="../uploads/logo (2).png">
    <link rel="icon" type="image/png" sizes="32x32" href="../uploads/logo (2).png">
    <style>
        body{
            background-color: #f1f1f1 !important;
        }
        .card {
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: scale(1.02);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }
        .card-img-container {
            height: 150px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .card-img-container img {
            width: auto;
            height: 100%;
            max-width: 100%;
            object-fit: contain; /* Ensures image fits without distortion */
        }
        .card-body {
            padding: 0.5rem; /* Reduce padding around the text */
            font-size: 0.9rem; /* Slightly smaller text size */
            line-height: 1.2; /* Compress the spacing between lines */
        }
        .card-body strong {
            color: #f68b45 !important;
        }
        .card-title {
            font-size: 1rem; /* Slightly smaller title */
            margin-bottom: 0.5rem; /* Reduce bottom margin */
            font-weight: 600; /* Bold title */
        }
        .card-text {
            font-size: 0.85rem; /* Smaller description text */
            margin-bottom: 0.5rem; /* Compress spacing */
            font-weight: 300; /* Lighter text for description */
        }
        .btn {
            font-size: 0.85rem;
            padding: 0.5rem 1rem;
            font-weight: 600; /* Make button text bold */
            background-color: #04BACC !important; /* Custom color for the navbar */
            margin-left: -3px;
        }
        .btn:hover {
            background-color: #17a7b4;
            font-size: 15px;
        }
        /* Footer */
        footer {
            background: #158691 !important;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 50px;
            font-size: 0.9rem;
        }
        footer a {
            color: #dce7f7;
            text-decoration: none;
        }
        footer a:hover {
            text-decoration: underline;
        }

        
.product_shop-banner {
    background-color: #04BACC; /* Matches the site's theme color */
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    color: white;
    width: 85%;
    margin: auto;
    min-height: 150px; /* Ensures a minimum height */

}
.section-title{
    font-size: 22px;
}
span{
    color: #f68b45;
    font-weight: bold;
}
    </style>
</head>

<body>
    <?php include 'navbar.php'; // Include the navbar ?>

    
 <!-- Appointment Banner Section -->
<div class="product_shop-banner text-center mt-3 p-4" style="background-image: url('../uploads/Premium\ Photo\ _\ Accessories\ for\ training\ and\ pet\ care.jpg'); background-size: cover; background-position: center;">
    <h3 class="text-light mb-3"><span>You can now buy a product!</span></h3>
</div>


<div class="container my-5">
    <!-- Popular Products Section -->
    <h3 class="section-title mb-3">Royal Canin</h3>
    <div class="row g-4">
        <?php foreach ($food_products as $product): ?>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="card">
                    <div class="card-img-container">
                        <img src="../uploads/<?php echo htmlspecialchars($product['image_url']); ?>" alt="Product Image">
                    </div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($product['product_name']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($product['description']); ?></p>
                        <p><strong>₱<?php echo htmlspecialchars($product['price']); ?></strong></p>
                        <a href="product_details.php?product_id=<?php echo $product['id']; ?>" class="btn btn-primary btn-sm">View Product</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Health Products Section -->
    <h3 class="section-title mb-3 mt-5">Health Products</h3>
    <div class="row g-4">
        <?php foreach ($health_products as $product): ?>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="card">
                    <div class="card-img-container">
                        <img src="../uploads/<?php echo htmlspecialchars($product['image_url']); ?>" alt="Health Product Image">
                    </div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($product['product_name']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($product['description']); ?></p>
                        <p><strong>₱<?php echo htmlspecialchars($product['price']); ?></strong></p>
                        <a href="product_details.php?product_id=<?php echo $product['id']; ?>" class="btn btn-primary btn-sm">View Product</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

   <!-- Dog Accessories Products Section -->
    <h3 class="section-title mb-3 mt-5">Accessories</h3>
    <div class="row g-4">
        <?php foreach ($accessories_products as $product): ?>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="card">
                    <div class="card-img-container">
                        <img src="../uploads/<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>" width="200">
                    </div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($product['product_name']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($product['description']); ?></p>
                        <p><strong>₱<?php echo htmlspecialchars($product['price']); ?></strong></p>
                        <a href="product_details.php?product_id=<?php echo $product['id']; ?>" class="btn btn-primary btn-sm">View Product</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>



</div>

<!-- Footer Section -->
<footer class="bg text-white py-3">
    <div class="container">
        <div class="row">
            <!-- Contact Section -->
            <div class="col-md-4 mb-3">
                <h6>Contact Us</h6>
                <ul class="list-unstyled mb-2 small">
                    <li><i class="bi bi-envelope"></i> info@dogshop.com</li>
                    <li><i class="bi bi-phone"></i> +123 456 7890</li>
                    <li><i class="bi bi-geo-alt"></i> 123 Dog Street, Dogtown</li>
                </ul>
            </div>

            <!-- About Section -->
            <div class="col-md-4 mb-3">
                <h6>About Us</h6>
                <p class="small mb-2">We are a passionate team dedicated to finding loving homes for dogs. Our mission is to provide a safe and caring environment for dogs and owners alike.</p>
            </div>

            <!-- Social Media Section -->
            <div class="col-md-4 mb-3">
                <h6>Follow Us</h6>
                <ul class="list-unstyled small">
                    <li><a href="#" class="text-white"><i class="bi bi-facebook"></i> Facebook</a></li>
                    <li><a href="#" class="text-white"><i class="bi bi-twitter"></i> Twitter</a></li>
                    <li><a href="#" class="text-white"><i class="bi bi-instagram"></i> Instagram</a></li>
                </ul>
            </div>
        </div>
    </div>
</footer>
</body>
</html>
