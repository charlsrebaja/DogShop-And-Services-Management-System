<?php
session_start(); // Start the session

// Initialize the cart
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// Check if the user is logged in
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Check if a product ID is provided in the URL
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    if ($user_id) {
        // If the user is logged in, remove the product from the database
        include '../config/db.php'; // Include database connection
        
        $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = :user_id AND product_id = :product_id");
        $stmt->execute(['user_id' => $user_id, 'product_id' => $product_id]);
    } else {
        // If the user is not logged in, remove the product from the session or cookie-based cart
        if (isset($cart[$product_id])) {
            unset($cart[$product_id]); // Remove the product from the cart in the session
            $_SESSION['cart'] = $cart; // Update the session with the modified cart
        } else if (isset($_COOKIE['cart'])) {
            $cart = json_decode($_COOKIE['cart'], true); // Decode the cart from the cookie
            if (isset($cart[$product_id])) {
                unset($cart[$product_id]); // Remove the product from the cart
                setcookie('cart', json_encode($cart), time() + 3600, '/'); // Update the cart cookie
            }
        }
    }
}

// Redirect to the cart page or wherever you want
header("Location: index.php");
exit;
?>
