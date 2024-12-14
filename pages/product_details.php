<?php
session_start();
include '../config/db.php'; // Include database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header('Location: login.php');
    exit;
}

// Get product ID from the URL
if (!isset($_GET['product_id']) || empty($_GET['product_id'])) {
    header('Location: index.php'); // Redirect to home if no product ID is provided
    exit;
}

$product_id = $_GET['product_id'];

// Fetch the product's details from the database
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

// If no product is found, redirect back
if (!$product) {
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['product_name']); ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f8f8f8 !important;
        }

        .product-image img {
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            0% { opacity: 0; transform: scale(0.9); }
            100% { opacity: 1; transform: scale(1); }
        }

        .btn-success {
            transition: transform 0.3s ease-in-out;
        }
        
        .btn-success:hover {
            transform: scale(1.05);
        }

        .custome-btn {
            background-color: #04BACC !important;
            color: white;
            transition: all 0.3s ease;
        }

        .custome-btn:hover {
            transform: scale(1.05);
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
        }

        #total-price {
            transition: color 0.3s ease, transform 0.3s ease;
            color: #fa7a24 !important;
        }

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

        .stock-info {
            font-size: 0.9rem;
            color: #6c757d;
        }

        .out-of-stock {
            color: red;
        }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container mt-5">
    <div class="row">
        <!-- Product Image Section -->
        <div class="col-md-6 d-flex align-items-center justify-content-center" style="min-height: 100px;">
            <img src="../uploads/<?php echo htmlspecialchars($product['image_url']); ?>" 
                 class="img-fluid animate__animated animate__zoomIn" 
                 alt="<?php echo htmlspecialchars($product['product_name']); ?>" 
                 style="max-height: 70%; max-width: 80%; object-fit: contain;">
        </div>
        
        <!-- Product Details and Cart Form -->
        <div class="col-md-6 d-flex align-items-center justify-content-center" style="min-height: 400px;">
            <div>
                <h1 class="animate__animated animate__fadeInDown"><?php echo htmlspecialchars($product['product_name']); ?></h1>
                <p class="text-muted animate__animated animate__fadeInLeft">P<?php echo htmlspecialchars($product['price']); ?></p>
                <p class="animate__animated animate__fadeInRight"><?php echo htmlspecialchars($product['description']); ?></p>

                <!-- Product Stock Information -->
                <p class="stock-info">
                    <?php if ($product['stock'] > 0): ?>
                        <span>Stock: <?php echo $product['stock']; ?> available</span>
                    <?php else: ?>
                        <span class="out-of-stock">Out of stock</span>
                    <?php endif; ?>
                </p>
                
                <!-- Add to Cart Form -->
                <form action="add_to_cart.php" method="POST">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <div class="form-group">
                        <label for="quantity" class="animate__animated animate__fadeInUp">Quantity:</label>
                        <div class="input-group input-group-sm animate__animated animate__fadeInUp" style="max-width: 150px;">
                            <button type="button" class="btn btn-outline-secondary px-2 py-1" id="decrease-btn" style="font-size: 0.9rem;">-</button>
                            <input type="number" 
                                   name="quantity" 
                                   id="quantity" 
                                   class="form-control text-center" 
                                   value="1" 
                                   min="1" 
                                   max="<?php echo $product['stock']; ?>" 
                                   required 
                                   style="font-size: 0.9rem;">
                            <button type="button" class="btn btn-outline-secondary px-2 py-1" id="increase-btn" style="font-size: 0.9rem;">+</button>
                        </div>
                    </div>
                    <p><strong>Total Price: <span id="total-price">P<?php echo htmlspecialchars($product['price']); ?></span></strong></p>
                    <input type="hidden" name="total_price" id="total_price" value="<?php echo htmlspecialchars($product['price']); ?>">
                    <button type="submit" class="btn custome-btn animate__animated animate__pulse text-white">Add to Cart</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const decreaseBtn = document.getElementById('decrease-btn');
    const increaseBtn = document.getElementById('increase-btn');
    const quantityInput = document.getElementById('quantity');
    const totalPriceDisplay = document.getElementById('total-price');
    const totalPriceInput = document.getElementById('total_price');
    const price = <?php echo htmlspecialchars($product['price']); ?>;
    const maxStock = <?php echo $product['stock']; ?>;

    function updateTotalPrice() {
        const quantity = parseInt(quantityInput.value);
        const total = quantity * price;
        totalPriceDisplay.innerText = `P${total.toFixed(2)}`;
        totalPriceInput.value = total.toFixed(2);
        // Add animation to price change
        totalPriceDisplay.style.transform = 'scale(1.1)';
        totalPriceDisplay.style.color = '#04BACC';
        setTimeout(() => {
            totalPriceDisplay.style.transform = 'scale(1)';
            totalPriceDisplay.style.color = 'black';
        }, 300);
    }

    decreaseBtn.addEventListener('click', () => {
        let currentQuantity = parseInt(quantityInput.value);
        if (currentQuantity > 1) {
            quantityInput.value = currentQuantity - 1;
            updateTotalPrice();
        }
    });

    increaseBtn.addEventListener('click', () => {
        let currentQuantity = parseInt(quantityInput.value);
        if (currentQuantity < maxStock) {
            quantityInput.value = currentQuantity + 1;
            updateTotalPrice();
        }
    });
</script>

<?php include 'footer.php'; ?>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
