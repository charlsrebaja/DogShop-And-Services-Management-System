<?php
// admin/bookings.php

include '../config/db.php';

// Fetch bookings with service and staff details
$query = "
    SELECT r.id, s.service_name, st.name AS staff_name, u.username AS customer_name, 
           r.reservation_date, r.reservation_time
    FROM reservations r
    JOIN services s ON r.service_id = s.id
    JOIN staff st ON r.staff_id = st.id
    JOIN users u ON r.user_id = u.id
    ORDER BY r.reservation_date, r.reservation_time";
$stmt = $pdo->query($query);
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Bookings</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Bookings</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Service</th>
                    <th>Staff</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $booking): ?>
                <tr>
                    <td><?= $booking['id'] ?></td>
                    <td><?= $booking['service_name'] ?></td>
                    <td><?= $booking['staff_name'] ?></td>
                    <td><?= $booking['customer_name'] ?></td>
                    <td><?= $booking['reservation_date'] ?></td>
                    <td><?= $booking['reservation_time'] ?></td>
                    <td>
                        <a href="edit_booking.php?id=<?= $booking['id'] ?>" class="btn btn-primary btn-sm">Edit</a>
                        <a href="delete_booking.php?id=<?= $booking['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Cancel</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
