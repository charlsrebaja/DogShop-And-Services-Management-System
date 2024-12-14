<?php
// Fetch service bookings for staff or admin
$staff_id = $_SESSION['staff_id']; // Get staff ID from session (staff should be logged in)
$bookingsStmt = $pdo->prepare("SELECT * FROM service_bookings WHERE staff_id = ?");
$bookingsStmt->execute([$staff_id]);
$bookings = $bookingsStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-5">
    <h2>Service Bookings</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Customer Name</th>
                <th>Service</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bookings as $booking): ?>
                <tr>
                    <td><?php echo htmlspecialchars($booking['customer_name']); ?></td>
                    <td><?php echo htmlspecialchars($booking['service_name']); ?></td>
                    <td><?php echo htmlspecialchars($booking['status']); ?></td>
                    <td>
                        <?php if ($booking['status'] === 'Pending'): ?>
                            <a href="accept_booking.php?id=<?php echo $booking['id']; ?>" class="btn btn-success">Accept</a>
                            <a href="reject_booking.php?id=<?php echo $booking['id']; ?>" class="btn btn-danger">Reject</a>
                        <?php else: ?>
                            <span class="badge badge-secondary">Completed</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
