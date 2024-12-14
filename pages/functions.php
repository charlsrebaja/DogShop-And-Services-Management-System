<?php
function saveSessionCartToDatabase($user_id, $session_cart, $pdo) {
    foreach ($session_cart as $product_id => $quantity) {
        $stmt = $pdo->prepare("SELECT * FROM carts WHERE user_id = :user_id AND product_id = :product_id");
        $stmt->execute(['user_id' => $user_id, 'product_id' => $product_id]);
        $cart_item = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($cart_item) {
            $stmt = $pdo->prepare("UPDATE carts SET quantity = quantity + :quantity WHERE user_id = :user_id AND product_id = :product_id");
            $stmt->execute(['quantity' => $quantity, 'user_id' => $user_id, 'product_id' => $product_id]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO carts (user_id, product_id, quantity) VALUES (:user_id, :product_id, :quantity)");
            $stmt->execute(['user_id' => $user_id, 'product_id' => $product_id, 'quantity' => $quantity]);
        }
    }
    unset($_SESSION['cart']); // Clear the session cart
}

function loadCartFromDatabase($user_id, $pdo) {
    $stmt = $pdo->prepare("SELECT product_id, quantity FROM carts WHERE user_id = :user_id");
    $stmt->execute(['user_id' => $user_id]);
    $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $_SESSION['cart'] = [];
    foreach ($cart_items as $item) {
        $_SESSION['cart'][$item['product_id']] = $item['quantity'];
    }
}
?>