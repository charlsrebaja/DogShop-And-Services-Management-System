<?php
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $userId = $_POST['user_id'];
    $serviceId = $_POST['service_name']; // service_name contains the service_id
    $staffId = $_POST['staff_id'];
    $appointmentDate = $_POST['appointment_date'];
    $appointmentTime = $_POST['appointment_time'];

    // Insert appointment into the database
    $stmt = $pdo->prepare("INSERT INTO appointments (user_id, service_id, staff_id, appointment_date, appointment_time) 
                           VALUES (:user_id, :service_id, :staff_id, :appointment_date, :appointment_time)");
    $stmt->execute([
        ':user_id' => $userId,
        ':service_id' => $serviceId, // Using service_id here
        ':staff_id' => $staffId,
        ':appointment_date' => $appointmentDate,
        ':appointment_time' => $appointmentTime
    ]);

    // Redirect to confirmation page
    header('Location: ../pages/appointment_form.php?success=1');
    exit();
}
?>
