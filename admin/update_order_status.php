<?php
// Include the database connection file
include '../config/db.php';

// Ensure the request is valid
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'] ?? null; // Retrieve order_id from POST
    $new_status = $_POST['status'] ?? null; // Retrieve new status from POST

    // Validate inputs
    if (!$order_id || !$new_status) {
        die("Invalid request: Missing order ID or status.");
    }

    try {
        // Update the order status in the database
        $update_query = "UPDATE orders SET status = ? WHERE order_id = ?";
        $update_stmt = $pdo->prepare($update_query);
        $update_stmt->execute([$new_status, $order_id]);

        // Fetch the user_id associated with the order
        $user_query = "SELECT user_id FROM orders WHERE order_id = ?";
        $user_stmt = $pdo->prepare($user_query);
        $user_stmt->execute([$order_id]);
        $user = $user_stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $user_id = $user['user_id'];
            $message = "The status of your order (Order ID: $order_id) has been updated to: $new_status.";

            // Insert a notification into the notifications table
            $notification_query = "INSERT INTO notifications (user_id, message) VALUES (?, ?)";
            $notification_stmt = $pdo->prepare($notification_query);
            $notification_stmt->execute([$user_id, $message]);
        }

        // Redirect back with success message
        header("Location: order_management.php?status=success");
        exit();
    } catch (PDOException $e) {
        die("Error updating order status: " . $e->getMessage());
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $order_id = $_GET['order_id'] ?? null;

    // Fetch the order details
    if ($order_id) {
        $query = "SELECT status FROM orders WHERE order_id = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$order_id]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$order) {
            die("Order not found!");
        }
    } else {
        die("Invalid request: Missing order ID.");
    }
} else {
    die("Invalid request method.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Order Status</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <!-- Update Order Status Form -->
        <h3>Update Order Status</h3>
        <form method="POST" action="update_order_status.php">
            <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order_id); ?>">
            <div class="form-group">
                <label for="status">Order Status</label>
                <select name="status" id="status" class="form-control" required>
                    <option value="Pending" <?php echo $order['status'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="Processing" <?php echo $order['status'] === 'Processing' ? 'selected' : ''; ?>>Processing</option>
                    <option value="Completed" <?php echo $order['status'] === 'Completed' ? 'selected' : ''; ?>>Completed</option>
                    <option value="Cancelled" <?php echo $order['status'] === 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="order_management.php" class="btn btn-secondary">Back to Orders</a>
        </form>
    </div>

    <!-- JS Libraries -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
