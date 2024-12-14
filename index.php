<?php
session_start();
include '../config/db.php'; // Include database connection

// Check if the user is an admin and redirect them if they are
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    // Redirect admin to another page (e.g., admin dashboard)
    header('Location: /admin/dashboard.php');
    exit();
}

try {
    // Fetch all products
    $sql = "SELECT * FROM products";
    $stmt = $pdo->query($sql);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching products: " . $e->getMessage();
}

// Handle form submission for adding products
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

// Fetch featured dogs for adoption
$featured_dogs_stmt = $pdo->query("SELECT * FROM dogs ORDER BY adoption_fee DESC LIMIT 3");
$featured_dogs = $featured_dogs_stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch products
$products_stmt = $pdo->query("SELECT * FROM products ORDER BY stock DESC LIMIT 8");
$products = $products_stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch health-related products (assuming a 'category' field in your table)
$preventions_products_stmt = $pdo->query("SELECT * FROM products WHERE category = 'Prevention' ORDER BY stock DESC LIMIT 20");
$preventions_products = $preventions_products_stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch popular products (assuming a 'category' field in your table)
$popular_products_stmt = $pdo->query("SELECT * FROM products WHERE category = 'Popular' ORDER BY stock DESC LIMIT 15");
$popular_products = $popular_products_stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch featured services or highlights for display on the homepage
$stmtServices = $pdo->query("SELECT service_name, description, price, image_url FROM services LIMIT 3");
$featuredServices = $stmtServices->fetchAll(PDO::FETCH_ASSOC);

$stmtStaff = $pdo->query("SELECT name, position, image_url FROM staff LIMIT 3");
$featuredStaff = $stmtStaff->fetchAll(PDO::FETCH_ASSOC);
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to the Dog Shop</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/mainstyle.css">
    <!-- For High-Resolution Displays -->
    <link rel="apple-touch-icon" sizes="180x180" href="../uploads/logo (2).png">
    <link rel="icon" type="image/png" sizes="32x32" href="../uploads/logo (2).png">
    <style>

        /* Additional styles for cards if needed */
        .card-body, .card-body1 {
            padding: 20px;
            
        }
        .staff-card {
            position: relative;
            overflow: hidden;
            height: 320px; /* Adjust this value as needed */
        }

        .staff-card img {
            width: 100%;
            height: 100%; /* Ensure the image covers the card's height */
            object-fit: cover; /* Prevent distortion of the image */
        }

        .staff-info {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%; /* Match the card's height */
            background: rgba(0, 0, 0, 0.6);
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .staff-card:hover .staff-info {
            opacity: 1;
        }

        .staff-card:hover img {
            transform: scale(1.1);
        }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

    <!-- Banner Section -->
<div class="banner">
    <h2>Welcome to the Doggobox</h2>
    <p>Your trusted place for dog adoption, grooming, and care services.</p>
    <a href="product_shop.php" class="btn">Shop Now</a>
</div>

 <!-- Product Section -->
<div class="container my-5">
    <h3 class="section-title mb-4">Popular Products</h3>

    <!-- Carousel -->
    <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
        
        <!-- Indicators -->
        <div class="carousel-indicators">
            <?php 
            $chunked_popular_products = array_chunk($popular_products, 4); // Divide products into chunks of 4
            foreach ($chunked_popular_products as $index => $product_chunk): 
            ?>
            <button type="button" data-bs-target="#productCarousel" data-bs-slide-to="<?php echo $index; ?>" 
                class="<?php echo $index === 0 ? 'active' : ''; ?>" 
                aria-current="<?php echo $index === 0 ? 'true' : 'false'; ?>" 
                aria-label="Slide <?php echo $index + 1; ?>"></button>
            <?php endforeach; ?>
        </div>

        <!-- Carousel Items -->
        <div class="carousel-inner">
            <?php 
            foreach ($chunked_popular_products as $index => $product_chunk): 
            ?>
            <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                <div class="row g-4">
                    <?php foreach ($product_chunk as $product): ?>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="card">
                            <div class="card-img-container">
                                <img src="../uploads/<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>" class="d-block w-100">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($product['product_name']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($product['description']); ?></p>
                                <p><strong>₱<?php echo htmlspecialchars($product['price']); ?></strong></p>
                                <a href="product_details.php?product_id=<?php echo $product['id']; ?>" class="btn btn-custome btn-sm">View Product</a>
                            </div>
                        </div>

                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Carousel Controls -->
         <br>
         <br>
        <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</div>


<!-- Prevention Products Section -->
<div class="container my-5">
    <h3 class="section-title mb-4">Prevention Products</h3>

    <!-- Carousel -->
    <div id="preventionCarousel" class="carousel slide" data-bs-ride="carousel">
        
        <!-- Indicators -->
        <div class="carousel-indicators">
            <?php 
            $chunked_preventions_products = array_chunk($preventions_products, 4); // Divide products into chunks of 4
            foreach ($chunked_preventions_products as $index => $product_chunk): 
            ?>
            <button type="button" data-bs-target="#preventionCarousel" data-bs-slide-to="<?php echo $index; ?>" 
                class="<?php echo $index === 0 ? 'active' : ''; ?>" 
                aria-current="<?php echo $index === 0 ? 'true' : 'false'; ?>" 
                aria-label="Slide <?php echo $index + 1; ?>"></button>
            <?php endforeach; ?>
        </div>

        <!-- Carousel Items -->
        <div class="carousel-inner">
            <?php 
            foreach ($chunked_preventions_products as $index => $product_chunk): 
            ?>
            <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                <div class="row g-4">
                    <?php foreach ($product_chunk as $product): ?>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="card">
                                <div class="card-img-container">
                                    <img src="../uploads/<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>" class="d-block w-100">
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($product['product_name']); ?></h5>
                                    <p class="card-text"><?php echo htmlspecialchars($product['description']); ?></p>
                                    <p><strong>₱<?php echo htmlspecialchars($product['price']); ?></strong></p>
                                    <a href="product_details.php?product_id=<?php echo $product['id']; ?>" class="btn btn-custome btn-sm">View Product</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <br>
        <br>
        <!-- Carousel Controls -->
        <button class="carousel-control-prev" type="button" data-bs-target="#preventionCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#preventionCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</div>



 <!-- Appointment Banner Section -->
    <div class="appointment-banner text-center mt-5 p-4">
        <h4 class="text-light mb-3">You can now book your appointment online!</h4>
        <a href="appointment_form.php" class="btn btn-light px-4 py-2">Book Now</a>
    </div>


 <!-- Services  Section -->
 <!-- Featured Services Section -->
<section id="featured-services" class="my-5">
    <div class="container">
        <h3 class="section-title mb-4">Featured Services</h3>
        <div class="row">
            <?php foreach ($featuredServices as $service): ?>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0 rounded-lg overflow-hidden">
                    <img src="../uploads/<?= htmlspecialchars($service['image_url']) ?>" alt="<?= htmlspecialchars($service['service_name']) ?>" class="img-fluid mb-3">
                    <div class="card-body">
                        <h4 class="card-title text-center" style="font-weight: bold;"><?= htmlspecialchars($service['service_name']) ?></h4>
                        <p class="card-text text-center" style="font-size: 1rem; color: #333;"><?= htmlspecialchars($service['description']) ?></p>
                        <p class="text-center">
                            
                            <span style="font-size: 1.25rem; font-weight: 500;">₱<?= htmlspecialchars($service['price']) ?></span>
                        </p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Featured Staff Section -->
<section id="featured-staff" class="my-5">
    <div class="container">
        <h3 class="section-title mb-4">Meet Our Staff</h3>
        <div class="row">
            <?php foreach ($featuredStaff as $staff): ?>
            <div class="col-md-4 mb-4">
                <div class="staff-card card shadow-sm border-0 rounded-lg overflow-hidden position-relative">
                    <img src="../uploads/<?= htmlspecialchars($staff['image_url']) ?>" alt="<?= htmlspecialchars($staff['name']) ?>" class="img-fluid">
                    <div class="staff-info position-absolute d-flex flex-column justify-content-center align-items-center w-100 h-100" style="top: 0; left: 0;">
                        <h4 class="staff-name text-white mb-2" style="font-weight: 600; color:blanchedalmond"><?= htmlspecialchars($staff['name']) ?></h4>
                        <p class="staff-position text-white" style="font-size: 1rem;"><?= htmlspecialchars($staff['position']) ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>





<div class="container my-5">
    <h2 class="section-title mb-4">Featured Dogs for Adoption</h2>
    <div class="row g-4">
        <?php foreach ($featured_dogs as $dog): ?>
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="card">
                    <div class="card-img">
                        <a href="dog_details.php?dog_id=<?php echo $dog['id']; ?>">
                            <img src="../dogimg/<?php echo htmlspecialchars($dog['image_url']); ?>" alt="Dog Image" class="img-fluid" style="border-radius: 5px;">
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>




    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
