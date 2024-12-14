<?php
// Include the database connection file
include '../config/db.php';

// Query to fetch order details
$query = "SELECT orders.order_id, users.username, orders.payment_method, orders.status, orders.created_at, orders.total_price
          FROM orders
          JOIN users ON orders.user_id = users.id
          ORDER BY orders.created_at DESC";

// Execute the query and fetch data
try {
    // Prepare the statement
    $stmt = $pdo->prepare($query);
    // Execute the statement
    $stmt->execute();
    // Fetch all results as an associative array
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching orders: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Management</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <!-- Back to Dashboard Button -->
        <h2>Order Management</h2>
        <div class="my-3">
            <a href="dashboard.php#order_management" class="btn btn-dark">Back to Dashboard</a>
        </div>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Username</th>
                    <th>Total Price</th>
                    <th>Payment Method</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order) : ?>
                    <?php
                    // Calculate the grand total by fetching order items for each order
                    $order_id = $order['order_id'];
                    $item_query = "SELECT oi.quantity, oi.price
                                   FROM order_items oi
                                   WHERE oi.order_id = ?";
                    $item_stmt = $pdo->prepare($item_query);
                    $item_stmt->execute([$order_id]);
                    $order_items = $item_stmt->fetchAll(PDO::FETCH_ASSOC);

                    $grand_total = 0;
                    foreach ($order_items as $item) {
                        $grand_total += $item['quantity'] * $item['price'];
                    }
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                        <td><?php echo htmlspecialchars($order['username']); ?></td>
                        <td>$<?php echo number_format($grand_total, 2); ?></td>
                        <td><?php echo htmlspecialchars($order['payment_method']); ?></td>
                        <td><?php echo htmlspecialchars($order['status']); ?></td>
                        <td><?php echo htmlspecialchars($order['created_at']); ?></td>
                        <td>
                            <button 
                                class="btn btn-warning btn-sm" 
                                data-toggle="modal" 
                                data-target="#updateModal<?php echo $order['order_id']; ?>">
                                Update Status
                            </button>
                        </td>
                    </tr>

                    <!-- Modal for Updating Status -->
                    <div class="modal fade" id="updateModal<?php echo $order['order_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel<?php echo $order['order_id']; ?>" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                <h5 class="modal-title w-100 text-center" id="updateModalLabel<?php echo $order['order_id']; ?>">Update Order Status</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form method="POST" action="update_order_status.php">
                                    <div class="modal-body">
                                        <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                        <div class="form-group">
                                            <label for="status">Order Status</label>
                                            <select name="status" id="status" class="form-control">
                                                <option value="Pending" <?php echo $order['status'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                                <option value="Processing" <?php echo $order['status'] === 'Processing' ? 'selected' : ''; ?>>Processing</option>
                                                <option value="Completed" <?php echo $order['status'] === 'Completed' ? 'selected' : ''; ?>>Completed</option>
                                                <option value="Cancelled" <?php echo $order['status'] === 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
