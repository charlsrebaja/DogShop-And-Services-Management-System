<?php
// Fetch bookings assigned to the current staff
$staff_id = $_SESSION['staff_id']; // Assuming the staff member is logged in
$bookingsStmt = $pdo->prepare("SELECT * FROM service_bookings WHERE staff_id = ? AND status = 'Pending'");
$bookingsStmt->execute([$staff_id]);
$bookings = $bookingsStmt->fetchAll(PDO::FETCH_ASSOC);

// Accept or Reject booking
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $booking_id = $_POST['booking_id'];
    $status = $_POST['status'];

    $updateStmt = $pdo->prepare("UPDATE service_bookings SET status = ? WHERE id = ?");
    $updateStmt->execute([$status, $booking_id]);

    echo "<script>alert('Booking status updated successfully!'); window.location.href='staff_dashboard.php';</script>";
    exit;
}
?>

<h2>Service Bookings</h2>
<ul>
    <?php foreach ($bookings as $booking): ?>
        <li>
            Service: <?php echo $booking['service_type']; ?> - Date: <?php echo $booking['service_date']; ?>
            <form method="POST" style="display:inline;">
                <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                <button type="submit" name="status" value="Accepted">Accept</button>
                <button type="submit" name="status" value="Rejected">Reject</button>
            </form>
        </li>
    <?php endforeach; ?>
</ul>
