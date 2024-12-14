<?php
session_start();
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the product ID and quantity from POST request
    $product_id = (int) $_POST['product_id'];
    $quantity = (int) $_POST['quantity'];

    // Fetch product details from the database
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if the product exists and quantity is valid
    if (!$product || $quantity <= 0 || $quantity > $product['stock']) {
        echo "<script>alert('Invalid product or quantity'); window.history.back();</script>";
        exit;
    }

    // Check if the user is logged in
    if (isset($_SESSION['user_id'])) {
        // Logged-in user: save cart to database
        $user_id = $_SESSION['user_id'];

        // Check if the product is already in the user's cart
        $stmt = $pdo->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$user_id, $product_id]);
        $existing_cart_item = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing_cart_item) {
            // If the product is already in the cart, update the quantity
            $new_quantity = $existing_cart_item['quantity'] + $quantity;
            $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
            $stmt->execute([$new_quantity, $existing_cart_item['id']]);
        } else {
            // If not in the cart, add it to the cart
            $stmt = $pdo->prepare("INSERT INTO cart (user_id, product_id, product_name, price, image_url, quantity) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $user_id,
                $product_id,
                $product['product_name'],  // Use the product name from the database
                $product['price'],          // Use the price from the database
                $product['image_url'],      // Use the image URL from the database
                $quantity
            ]);
        }
    } else {
        // For guest users, store in session (cart will be lost after logout)
        if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Check if the product is already in the cart (session)
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = [
                'id' => $product['id'],
                'product_name' => $product['product_name'],
                'price' => $product['price'],
                'quantity' => $quantity,
                'image_url' => $product['image_url']
            ];
        }
    }

    // Update the stock in the products table
    $new_stock = $product['stock'] - $quantity;
    $stmt = $pdo->prepare("UPDATE products SET stock = ? WHERE id = ?");
    $stmt->execute([$new_stock, $product_id]);

    echo "<script>alert('Product added to cart successfully!'); window.location.href='index.php';</script>";
}
?>
