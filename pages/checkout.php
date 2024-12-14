<?php
session_start();
include '../config/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get the user ID
$user_id = $_SESSION['user_id'];

// Fetch the cart items from the session or database
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// Function to fetch cart items for logged-in users
function getCartFromDatabase($user_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT cart.*, products.product_name, products.price, products.image_url 
                           FROM cart 
                           JOIN products ON cart.product_id = products.id 
                           WHERE cart.user_id = :user_id");
    $stmt->execute(['user_id' => $user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get cart items if user is logged in
if ($user_id) {
    $cart = getCartFromDatabase($user_id);
}

// Calculate the total price
$total_price = 0;
foreach ($cart as $item) {
    $total_price += $item['price'] * $item['quantity'];
}

// Handle order placement
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate address and ensure the payment method is 'cash_on_hand'
    if ($total_price <= 0 || !isset($_POST['address']) || empty($_POST['address'])) {
        echo "<script>alert('Invalid order request. Please try again.'); window.location.href='cart.php';</script>";
        exit;
    }

    $address = htmlspecialchars($_POST['address']);
    $payment_method = 'cash_on_hand';  // Set payment method to "cash_on_hand"

    // Insert order into the orders table
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_price, payment_method, shipping_address) 
                           VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $total_price, $payment_method, $address]);
    $order_id = $pdo->lastInsertId();

    // Insert order details for each cart item
    foreach ($cart as $item) {
        $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) 
                               VALUES (?, ?, ?, ?)");
        $stmt->execute([$order_id, $item['product_id'], $item['quantity'], $item['price']]);
    }

    // Clear the cart after placing the order
    if ($user_id) {
        // Remove cart items from the database for logged-in user
        $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
        $stmt->execute([$user_id]);
    } else {
        // Clear the cart from session for guest users
        unset($_SESSION['cart']);
    }

    // Insert notification for the user about order placement
    $message = "Your order has been placed successfully! Estimated delivery time: 3-5 days.";
    $stmt = $pdo->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
    $stmt->execute([$user_id, $message]);

    // Redirect to the order confirmation page
    header("Location: order_confirmation.php?order_id=$order_id");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #04BACC !important; /* Custom color for the navbar */
            font-family: 'Arial', sans-serif;
        }
        .container {
            max-width: 600px;  /* Reduced width for compression */
            margin-top: 20px;
            padding: 20px;  /* Reduced padding for compression */
            background-color: #fff;
            border-radius: 8px;  /* Slightly smaller radius */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            
            text-align: center;
            margin-bottom: 15px;
        }
        .form-group label {
            font-weight: bold;
            color: #333;
        }
        .form-control {
            border-radius: 5px;
            border: 1px solid #ddd;
            padding: 8px;
        }
        .form-control:focus {
            border-color: #ff8c00;
            box-shadow: 0 0 5px rgba(255, 140, 0, 0.5);
        }
        .total-price {
            font-size: 1.1em;
            font-weight: bold;
            color: #ff8c00;
            text-align: center;
            margin-top: 15px;
        }
        .btn-primary {
            background-color: #ff8c00;
            border: none;
            padding: 10px 25px;
            font-size: 16px;
            width: 100%;
            border-radius: 5px;
        }
        .btn-primary:hover {
            background-color: #e07b00;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Checkout</h2>

        <!-- Display the cart items and calculate the total -->
        <form action="checkout.php" method="POST">
            <div class="form-group">
                <label for="address">Shipping Address:</label>
                <textarea class="form-control" name="address" id="address" rows="3" required></textarea>
            </div>

            <!-- Payment Method Field - Only Cash on Hand (no other options) -->
            <div class="form-group">
                <label for="payment_method">Payment Method:</label>
                <select name="payment_method" id="payment_method" disabled>
                    <option value="cash_on_hand" selected>Cash on Hand</option>
                </select>
            </div>

            <!-- Display the total price -->
            <div class="total-price">
                Total: P<?php echo number_format($total_price, 2); ?>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Place Order</button>
        </form>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
