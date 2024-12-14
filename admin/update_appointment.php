<?php
include '../config/db.php';

// Fetch the appointment ID from the URL
$appointment_id = $_GET['id'] ?? null;

// Redirect if appointment ID is not provided
if (!$appointment_id) {
    header('Location: manage_appointments.php');
    exit;
}

// Fetch the appointment details from the database
$query = "SELECT a.*, u.username, s.name AS staff_name, serv.service_name 
          FROM appointments a
          JOIN users u ON a.user_id = u.id
          JOIN staff s ON a.staff_id = s.id
          JOIN services serv ON a.service_id = serv.id
          WHERE a.id = :appointment_id";

$stmt = $pdo->prepare($query);
$stmt->bindValue(':appointment_id', $appointment_id);
$stmt->execute();
$appointment = $stmt->fetch(PDO::FETCH_ASSOC);

// If appointment not found, redirect
if (!$appointment) {
    header('Location: manage_appointments.php');
    exit;
}

// Fetch the list of services for the dropdown
$services_query = "SELECT id, service_name FROM services";
$services_stmt = $pdo->prepare($services_query);
$services_stmt->execute();
$services = $services_stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission for updating the appointment
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $service_id = $_POST['service_id'];
    $staff_id = $_POST['staff_id'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $status = $_POST['status'];

    // Update the appointment details in the database
    $update_query = "UPDATE appointments 
                     SET service_id = :service_id, staff_id = :staff_id, appointment_date = :appointment_date, 
                         appointment_time = :appointment_time, status = :status
                     WHERE id = :appointment_id";
    $update_stmt = $pdo->prepare($update_query);
    $update_stmt->bindValue(':service_id', $service_id);
    $update_stmt->bindValue(':staff_id', $staff_id);
    $update_stmt->bindValue(':appointment_date', $appointment_date);
    $update_stmt->bindValue(':appointment_time', $appointment_time);
    $update_stmt->bindValue(':status', $status);
    $update_stmt->bindValue(':appointment_id', $appointment_id);

    if ($update_stmt->execute()) {
        // Redirect after successful update
        header('Location: manage_appointments.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Appointment</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container my-4">
        <h4>Update Appointment</h4>

        <!-- Update Appointment Form -->
        <form method="POST">
            <div class="mb-3">
                <label for="service_id" class="form-label">Service</label>
                <select name="service_id" id="service_id" class="form-select" required>
                    <?php foreach ($services as $service): ?>
                        <option value="<?= $service['id'] ?>" <?= $appointment['service_id'] == $service['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($service['service_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="staff_id" class="form-label">Staff</label>
                <select name="staff_id" id="staff_id" class="form-select" required>
                    <option value="">Select Staff</option>
                    <?php
                    // Fetch staff members for the staff dropdown
                    $staff_query = "SELECT id, name FROM staff";
                    $staff_stmt = $pdo->prepare($staff_query);
                    $staff_stmt->execute();
                    $staff_members = $staff_stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($staff_members as $staff) {
                        echo "<option value='{$staff['id']}' " . ($appointment['staff_id'] == $staff['id'] ? 'selected' : '') . ">{$staff['name']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="appointment_date" class="form-label">Appointment Date</label>
                <input type="date" name="appointment_date" id="appointment_date" class="form-control" value="<?= $appointment['appointment_date'] ?>" required>
            </div>

            <div class="mb-3">
                <label for="appointment_time" class="form-label">Appointment Time</label>
                <input type="time" name="appointment_time" id="appointment_time" class="form-control" value="<?= $appointment['appointment_time'] ?>" required>
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-select" required>
                    <option value="Pending" <?= $appointment['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="Completed" <?= $appointment['status'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
                    <option value="Cancelled" <?= $appointment['status'] == 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Update Appointment</button>
            <a href="manage_appointments.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

</body>
</html>
