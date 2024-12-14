<?php
session_start();
include '../config/db.php';


$appointmentId = $_GET['id'];
$stmt = $pdo->prepare("
    SELECT appointments.*, 
           staff.name AS staff_name, 
           users.username AS user_name, 
           users.email AS owner_email, 
           users.contact AS owner_contact, 
           users.address AS owner_address 
    FROM appointments
    JOIN staff ON appointments.staff_id = staff.id
    JOIN users ON appointments.user_id = users.id
    WHERE appointments.id = :appointment_id
");
$stmt->execute([':appointment_id' => $appointmentId]);
$appointment = $stmt->fetch();

if (!$appointment) {
    echo "<div class='container my-5'><h1>Appointment not found.</h1></div>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Appointment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <h1 class="text-center">Appointment Details</h1>
        <div class="row">
            <div class="col-md-6">
                <h4>Customer Information</h4>
                <p><strong>Name:</strong> <?= $appointment['user_name'] ?></p>
                <p><strong>Email:</strong> <?= $appointment['owner_email'] ?? 'Not available' ?></p>
                <p><strong>Contact:</strong> <?= $appointment['owner_contact'] ?? 'Not available' ?></p>
                <p><strong>Address:</strong> <?= $appointment['owner_address'] ?? 'Not available' ?></p>
            </div>
            <div class="col-md-6">
                <h4>Dog Information</h4>
                <p><strong>Name:</strong> <?= $appointment['dog_name'] ?? 'Not specified' ?></p>
                <p><strong>Breed:</strong> <?= $appointment['dog_breed'] ?? 'Not specified' ?></p>
                <p><strong>Age:</strong> <?= $appointment['dog_age'] ?? 'Not specified' ?></p>
            </div>
        </div>
        <h4>Appointment Details</h4>
        <p><strong>Service:</strong> <?= $appointment['service'] ?></p>
        <p><strong>Staff:</strong> <?= $appointment['staff_name'] ?></p>
        <p><strong>Date:</strong> <?= $appointment['appointment_date'] ?></p>
        <p><strong>Time:</strong> <?= $appointment['appointment_time'] ?></p>
        <p><strong>Status:</strong> <?= $appointment['status'] ?></p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
