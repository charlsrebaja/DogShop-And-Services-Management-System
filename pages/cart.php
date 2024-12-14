<?php
include '../config/db.php';

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
// Check if the user is logged in
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;


// Load cart from cookie or session
if ($user_id) {
    // Retrieve the cart from the database if logged in
    $cart = getCartFromDatabase($user_id);
} else {
    // Use session or cookie for non-logged-in users
    if (isset($_COOKIE['cart'])) {
        $cart = json_decode($_COOKIE['cart'], true);
    } else {
        $cart = [];
    }
}

function getCartFromDatabase($user_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT cart.*, products.product_name, products.price, products.image_url FROM cart 
                           JOIN products ON cart.product_id = products.id WHERE cart.user_id = :user_id");
    $stmt->execute(['user_id' => $user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC); // Return an array of cart items with product details
}

function saveCartToDatabase($user_id, $cart) {
    global $pdo;
    
    // First, delete the existing cart for the user
    $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = :user_id");
    $stmt->execute(['user_id' => $user_id]);

    // Insert updated cart items
    foreach ($cart as $item) {
        $stmt = $pdo->prepare("INSERT INTO cart (user_id, product_id,product_name,price,image_url,quantity) VALUES (:user_id, product_id,product_name,price,image_url,quantity)");
        $stmt->execute([
            'user_id' => $user_id,
            'product_id' => $item['product_id'],
            'quantity' => $item['quantity'],
            'product_name' => $item['product_name'],
            'price' => $item['price'],
            'image_url' => $item['image_url'],
        ]);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Sliding Cart */
        .sliding-cart {
            height: 100%;
            width: 0;
            position: fixed;
            right: 0;
            top: 0;
            background-color: rgba(243, 243, 243, 0.86);
            z-index: 1000;
            overflow-x: hidden;
            transition: 0.5s;
            padding-top: 60px;
            border-radius: 15px 0 0 15px;
        }

        .sliding-cart-content {
            margin: 20px;
            max-width: 250px;
        }

        .sliding-cart img {
            width: 50px;
            border-radius: 10px;
            transition: transform 0.3s;
        }

        .sliding-cart img:hover {
            transform: scale(1.1);
        }

        .sliding-cart h2 {
            font-size: 1.8rem;
            text-align: center;
            margin-bottom: 20px;
        }

        .sliding-cart .close-btn {
            position: absolute;
            top: 20px;
            left: 20px;
            font-size: 30px;
            color: white;
            cursor: pointer;
        }

        .sliding-cart a {
            font-size: 1rem;
        }

        /* Table Styling */
        .table {
            border-radius: 10px;
            overflow: hidden;
        }

        .table-dark th, .table-dark td {
            vertical-align: middle;
        }

        /* Buttons */
        .btn-sm {
            border-radius: 20px;
        }

        /* Cart Button */
        .cart-btn {
            border: none;
            background: none;
            position: relative;
            cursor: pointer;
        }

        .cart-btn .badge {
            font-size: 0.8rem;
            padding: 0.2rem 0.5rem;
            transform: translate(-50%, -50%);
        }

        .btn-custome {
            background-color: #04BACC !important;
            color: white;
        }
        span{
            color:#ff7b23;
        }
    </style>
</head>
<body>
    <!-- Sliding Cart Panel -->
    <div id="slidingCart" class="sliding-cart">
        <span class="close-btn" onclick="closeCart()">&times;</span>
        <div class="sliding-cart-content">
            <?php if (empty($cart)): ?>
                <p class="text-center">Your cart is empty.</p>
            <?php else: ?>
                <table class="table table-bordered table-dark">
                <thead>
                    <tr>
                        <th style="font-size: 0.80rem; padding: 5px;">Image</th>
                        <th style="font-size: 0.85rem; padding: 5px;">Price</th>
                        <th style="font-size: 0.85rem; padding: 5px;">Qty</th>
                        <th style="font-size: 0.85rem; padding: 5px;">Total</th>
                        <th style="font-size: 0.85rem; padding: 5px;">Action</th>
                    </tr>
                </thead>

                        <tbody>
                        <?php $grand_total = 0; ?>
                        <?php foreach ($cart as $item): ?>
                            <tr>
                                <td>
                                    <img src="../uploads/<?php echo isset($item['image_url']) && !empty($item['image_url']) ? htmlspecialchars($item['image_url']) : 'default_image.jpg'; ?>" alt="Item Image">
                                </td>
                                <td>P<?php echo isset($item['price']) ? htmlspecialchars($item['price']) : '0.00'; ?></td>
                                <td><?php echo isset($item['quantity']) ? htmlspecialchars($item['quantity']) : 0; ?></td>
                                <td>P<?php echo isset($item['price']) && isset($item['quantity']) ? htmlspecialchars($item['price'] * $item['quantity']) : '0.00'; ?></td>
                                <td>
                                    <a href="remove_from_cart.php?id=<?php echo $item['product_id']; ?>" class="btn btn-custome btn-sm">Remove</a>
                                </td>
                            </tr>
                            <?php $grand_total += $item['price'] * $item['quantity']; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div>
                    <h5>Total: <span>P<?php echo number_format($grand_total, 2); ?></span></h5>
                    <!-- Update the Proceed to Checkout button to pass the total -->
                    <a href="checkout.php?total=<?php echo number_format($grand_total, 2); ?>" class="btn btn-primary">Proceed to Checkout</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <script>
        let isCartOpen = false; // To track the cart state

        // Function to open the sliding cart
        function openCart() {
            document.getElementById("slidingCart").style.width = "350px";
            isCartOpen = true;
        }

        // Function to close the sliding cart
        function closeCart() {
            document.getElementById("slidingCart").style.width = "0";
            isCartOpen = false;
        }

        // Toggle cart open/close on single-click
        function toggleCart() {
            if (isCartOpen) {
                closeCart(); // Close if it's open
            } else {
                openCart(); // Open if it's closed
            }
        }
    </script>
</body>
</html>
