<?php
// Include database connection
include '../config/db.php';

// Check if the appointment was successfully saved and fetch the appointment details
if (isset($_GET['appointment_id'])) {
    $appointment_id = $_GET['appointment_id'];

    // Fetch appointment details from the database
    $query = "SELECT a.appointment_date, a.appointment_time, s.service_name, st.name AS staff_name, u.username 
              FROM appointments a
              JOIN services s ON a.service_id = s.id
              JOIN staff st ON a.staff_id = st.id
              JOIN users u ON a.user_id = u.id
              WHERE a.id = :appointment_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':appointment_id' => $appointment_id]);
    $appointment = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$appointment) {
        // If no appointment is found, redirect to appointment form
        header('Location: appointment_form.php');
        exit;
    }
} else {
    // If no appointment ID is provided, redirect to appointment form
    header('Location: appointment_form.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Confirmation</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>
<body>

<div class="container my-5">
    <h2>Appointment Confirmation</h2>
    <div class="alert alert-success">
        <p><strong>Thank you, <?php echo htmlspecialchars($appointment['username']); ?>!</strong></p>
        <p>Your grooming appointment has been successfully booked. Here are the details:</p>
        <ul>
            <li><strong>Service:</strong> <?php echo htmlspecialchars($appointment['service_name']); ?></li>
            <li><strong>Groomer:</strong> <?php echo htmlspecialchars($appointment['staff_name']); ?></li>
            <li><strong>Appointment Date:</strong> <?php echo htmlspecialchars($appointment['appointment_date']); ?></li>
            <li><strong>Appointment Time:</strong> <?php echo htmlspecialchars($appointment['appointment_time']); ?></li>
        </ul>
        <a href="appointment_form.php" class="btn btn-primary">Back to Appointment Form</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
