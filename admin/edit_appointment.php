<?php
include '../config/db.php';

// Check if an appointment ID is provided
if (isset($_GET['id'])) {
    $appointment_id = $_GET['id'];

    // Fetch the appointment details
    $query = "SELECT a.*, u.username, s.name AS staff_name, serv.service_name 
              FROM appointments a
              JOIN users u ON a.user_id = u.id
              JOIN staff s ON a.staff_id = s.id
              JOIN services serv ON a.service_id = serv.id
              WHERE a.id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':id', $appointment_id);
    $stmt->execute();
    $appointment = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$appointment) {
        die('Appointment not found.');
    }

    // Handle form submission to update status
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $new_status = $_POST['status'];

        // Validate the status to prevent invalid data
        $valid_statuses = ['Pending', 'Completed', 'Cancelled'];
        if (!in_array($new_status, $valid_statuses)) {
            $error_message = "Invalid status selected.";
        } else {
            // Update the status in the database
            $update_query = "UPDATE appointments SET status = :status WHERE id = :id";
            $update_stmt = $pdo->prepare($update_query);
            $update_stmt->bindValue(':status', $new_status);
            $update_stmt->bindValue(':id', $appointment_id);
            $update_stmt->execute();

            // Redirect to the appointments page after updating
            header('Location: dashboard.php#manage_appointment');
            exit();
        }
    }
} else {
    die('Appointment ID is required.');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Appointment</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container my-4">
        <h1 class="text-center">Edit Appointment</h1>

        <!-- Success or Error Messages -->
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
        <?php elseif (isset($_GET['status']) && $_GET['status'] === 'success'): ?>
            <div class="alert alert-success">Appointment status updated successfully!</div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="customer" class="form-label">Customer</label>
                <input type="text" class="form-control" id="customer" value="<?= htmlspecialchars($appointment['username']) ?>" disabled>
            </div>
            <div class="mb-3">
                <label for="service" class="form-label">Service</label>
                <input type="text" class="form-control" id="service" value="<?= htmlspecialchars($appointment['service_name']) ?>" disabled>
            </div>
            <div class="mb-3">
                <label for="staff" class="form-label">Staff</label>
                <input type="text" class="form-control" id="staff" value="<?= htmlspecialchars($appointment['staff_name']) ?>" disabled>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-select">
                    <option value="Pending" <?= $appointment['status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="Completed" <?= $appointment['status'] === 'Completed' ? 'selected' : '' ?>>Completed</option>
                    <option value="Cancelled" <?= $appointment['status'] === 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
                </select>
            </div>
            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary">Update Status</button>
                <a href="dashboard.php#manage_appointment" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

    <!-- Include Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
