<?php
session_start();
include '../config/db.php';

// Get the order ID from the query string
$order_id = $_GET['order_id'];

// Fetch order details from the database
$stmt = $pdo->prepare("SELECT * FROM orders WHERE order_id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch();

// Ensure the order exists before proceeding
if (!$order) {
    die("Order not found.");
}

// Fetch order items
$stmt = $pdo->prepare("SELECT oi.*, p.product_name, (oi.quantity * oi.price) AS total_price 
                       FROM order_items oi 
                       JOIN products p ON oi.product_id = p.id 
                       WHERE oi.order_id = ?");
$stmt->execute([$order_id]);
$order_items = $stmt->fetchAll();

// Assuming the order status is 'processing' or 'shipped'
$processing_time = 3; // Process time in days (adjust as needed)

// Calculate the estimated delivery date (add days to the current date)
$delivery_date = date('Y-m-d', strtotime("+$processing_time days"));

// Update the estimated delivery date in the database
$stmt = $pdo->prepare("UPDATE orders SET estimated_delivery_date = ? WHERE order_id = ?");
$stmt->execute([$delivery_date, $order_id]);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            animation: fadeIn 0.6s ease-out;
        }

        h2, h3 {
            text-align: center;
            color: #333;
            font-size: 1.5rem;
        }

        table {
            margin-top: 15px;
            width: 100%;
        }

        th {
            background-color: #ff8c00;
            color: #fff;
            font-size: 0.9rem;
        }

        tr {
            background-color: #f9f9f9;
            border-bottom: 1px solid #e1e1e1;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        td {
            padding: 8px;
            font-size: 0.9rem;
        }

        .btn {
            background-color: #ff8c00;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 8px 16px;
            transition: background-color 0.3s ease;
            font-size: 0.9rem;
        }

        .btn:hover {
            background-color: #e07b00;
        }

        @keyframes fadeIn {
            0% { opacity: 0; transform: translateY(10px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        .order-summary {
            margin-top: 15px;
            padding: 10px;
            background-color: #fafafa;
            border-radius: 8px;
            animation: slideIn 0.6s ease-out;
            font-size: 0.9rem;
        }

        .order-summary h4 {
            margin-bottom: 10px;
            font-size: 1.2rem;
            color: #333;
        }

        .order-summary p {
            margin-bottom: 8px;
        }

        @keyframes slideIn {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(0); }
        }

        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }

            h2, h3 {
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <?php if ($order) : ?>
        <h2>Order Confirmation</h2>
        <p>Thank you for your order! Your order ID is <strong>#<?php echo $order['order_id']; ?></strong>.</p>
        <h3>Order Summary</h3>
        <table class="table">
            <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Total</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($order_items as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td>P<?php echo number_format($item['total_price'], 2); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <div class="order-summary">
            <h4>Shipping Address:</h4>
            <p><?php echo nl2br(htmlspecialchars($order['shipping_address'])); ?></p>
            <p><strong>Total Price: </strong>P<?php echo number_format($order['total_price'], 2); ?></p>
            <p><strong>Payment Method: </strong><?php echo ucfirst(str_replace('_', ' ', $order['payment_method'])); ?></p>
            <h5>Estimated Delivery Date: <?php echo htmlspecialchars($order['estimated_delivery_date']); ?></h5>
        </div>
        <a href="index.php" class="btn mt-4">Back to Shop</a>
    <?php else: ?>
        <p>Order not found!</p>
    <?php endif; ?>
</div>
</body>
</html>
